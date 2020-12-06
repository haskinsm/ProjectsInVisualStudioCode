
{-# LANGUAGE DataKinds            #-}
{-# LANGUAGE FlexibleContexts     #-}
{-# LANGUAGE FlexibleInstances    #-}
{-# LANGUAGE OverloadedStrings    #-}
{-# LANGUAGE LambdaCase #-}
{-# LANGUAGE DuplicateRecordFields     #-}

module Lib
    ( someFunc
    ) where

import qualified GitHub as GH
import qualified Servant.Client               as SC
import           System.Environment           (getArgs)
import Data.Text ( Text, pack, unpack )
import Data.List (intercalate, groupBy, sortBy)
import Data.Either ( partitionEithers )
import           Servant.API                (BasicAuthData (..))
import Data.ByteString.UTF8 (fromString)

someFunc :: IO ()
someFunc = do
  putStrLn "Let's try a GitHubCall"
  (rName:user:token:_) <- getArgs --User => username, token => not sure if this is password or like an API key, rNmae => The account we want to get info about so username
  putStrLn $ "name is " ++ rName -- one would enter in the arguments by using cd to get into src, then stack run -- <parameters>
  putStrLn $ "github account for API call is " ++ user --All 3 parameters would need to be passed in or build/run will fail
  putStrLn $ "github token for api call is " ++ token --E.g.: stack run -- esjmb esjmb <token>   (esjmb is lecturers username I believe). Run will fail for my username as I dont have a name set on github account and code cant handle the null, would need a maybe text to handle this I believe.
                                                                --Find my token on OneNote, if cant just generate your own personal access token on github
  let auth = BasicAuthData (fromString user) (fromString token)

  testGitHubCall auth $ pack rName
  putStrLn "end."

testGitHubCall :: BasicAuthData -> Text -> IO ()
testGitHubCall auth name = 
  GH.runClientM (GH.getUser (Just "haskell-app") auth name) >>= \case 

    Left err -> do
      putStrLn $ "heuston, we have a problem: " ++ show err
    Right res -> do
      putStrLn $ "the votes of the github jury are " ++ show res

      --If getting the user info works then get.....
      -- now lets get the users repositories. Note this is now running paged cass.
      GH.runClientPagedM (GH.getUserRepos (Just "haskell-app") auth name) >>= \case
        Left err -> do
          putStrLn $ "heuston, we have a problem (getting repos): " ++ show err  
        Right repos -> do
          putStrLn $ " repositories are:" ++
            intercalate ", " (map (\(GH.GitHubRepo n _ _ ) -> unpack n) repos) --Ends up as a comma seperated list of all repos found (Inlcudes cloned repos)

          -- now lets get the full list of collaborators from repositories
          (partitionEithers <$> mapM (getContribs auth name) repos) >>= \case  ---The dollar yokie here is a way of saying do this IO thing (the brackets right after)
          -- and then pass the result of that to the preceding call and then return all of that in the IO space 
          -- See getContribs function below

          -- Case matching return of above function (I believe)
            ([], contribs) -> -- Contribs is a list of list of contributers hwere each list of contributers is the response to a call to a particular repo
              putStrLn $ " contributors are: " ++
              (intercalate "\n\t" . --Intercalating-> just putting it on a new line with a tab
               map (\(GH.RepoContributor n c) -> "[" ++ show n ++ "," ++ show c ++ "]") . -- internal bit here maps this into a string for each (cntributors i think) and then mapping into a string for each where each is just showing the name and the count
               groupContributors $ concat contribs) -- Concat turns the list of lists into a list => now have list of contributers
                --groupContributers is a function defined below

            (ers, _)-> do
              putStrLn $ "heuston, we have a problem (getting contributors): " ++ show ers

      --Below created by me
      GH.runClientPagedM (GH.getGitHubFollowers (Just "haskell-app") auth name) >>= \case
        Left err -> do
          putStrLn $ "heuston, we have a problem (getting GitHub followers): " ++ show err
        Right users -> do
          putStrLn $ " followers are: " ++ 
            intercalate ", " (map (\(GH.GitHubFollower n ) -> unpack n) users) --Not sure here if map is just a data structure yoke or like the actual name of the map/ data structure.
          
          --"followers are: " ++
            --(intercalate "\n\t" .
            --map(\(GH.GitHubFollower n) -> "[" ++ show n ++ "]") )


            --May try generating a list of Followers Followers and then send it to a server or something and use JS to count the length of the lists as that would be easier for me
            {-
            --Now attempting to count how many followers each follower of the originally inputted user at program start has
            (partitionEithers <$> mapM (getGitHubFollowers auth name) users) >>= \case --Need to write where getGitHubFollowers section below

              ([], followers) ->
                putStrLn $ " followers are: " ++
                (intercalate "\n\t" . --Intercalating-> just putting it on a new line with a tab
                map (\(GH.GitHubFollower n) -> "[" ++ show n ++ "," ++ show n ++ "]") ) -- internal bit here maps this into a string for each (cntributors i think) and then mapping into a string for each where each is just showing the name and the count

              (ers, _)-> do
                putStrLn $ "heuston, we have a problem (getting GitHub Followers): " ++ show ers
-}
  where getContribs :: BasicAuthData -> GH.Username -> GH.GitHubRepo -> IO (Either SC.ClientError [GH.RepoContributor])
        getContribs auth name (GH.GitHubRepo repo _ _) =
          GH.runClientPagedM (GH.getRepoContribs (Just "haskell-app") auth name repo)

        groupContributors :: [GH.RepoContributor] -> [GH.RepoContributor] --Uses some standard functions, first groups by name of contributer
        groupContributors  = sortBy (\(GH.RepoContributor _ c1) (GH.RepoContributor _ c2) -> compare c1 c2) . -- then maps over a map function which is defined below
                             map mapfn .
                             groupBy (\(GH.RepoContributor l1 _) (GH.RepoContributor l2 _) -> l1 == l2) -- Then just doing a sorting
         where mapfn :: [GH.RepoContributor] -> GH.RepoContributor -- Takes a list of repo contributers (All of same name thanks to above sorting/grouping) and 
                -- .. creates a single repo contributer element with the right name and sum of all the contributions across the list
               mapfn xs@((GH.RepoContributor l _):_) = GH.RepoContributor l .sum $
                                                       map (\(GH.RepoContributor _ c) -> c)  xs
{-
  where getGitHubFollowers :: BasicAuthData -> GH.Username -> GH.GitHubFollower -> IO (Either SC.ClientError [GH.GitHubFollower])
        getGitHubFollowers auth name (GH.GitHubFollower n) =
          GH.runClientPagedM (GH.getGitHubFollowers (Just "haskell-app") auth name n)

  groupGitHubFollowers :: [GH.GitHubFollower] -> [GH.GitHubFollower] --Uses some standard functions, first groups by name of follower
  groupGitHubFollowers  =  sortBy (\(GH.GitHubFollower n1) (GH.GitHubFollower n2) -> compare n1 n2) -- then maps over a map function which is defined below
                     -- map mapfn .
                     -- groupBy (\(GH.RepoContributor l1 _) (GH.RepoContributor l2 _) -> l1 == l2) -- Then just doing a sorting
 -- where mapfn :: [GH.RepoContributor] -> GH.RepoContributor -- Takes a list of repo contributers (All of same name thanks to above sorting/grouping) and 
          -- .. creates a single repo contributer element with the right name and sum of all the contributions across the list
       -- mapfn xs@((GH.RepoContributor l _):_) = GH.RepoContributor l .sum $
                                              --  map (\(GH.RepoContributor _ c) -> c)  xs
-}



                                               
                                               








