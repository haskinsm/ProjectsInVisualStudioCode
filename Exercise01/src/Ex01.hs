module Ex01 where
import Data.Char (toUpper)
import Data.List(group)

name, idno, username :: String
name      =  "Michael Haskins"  -- replace with your name
idno      =  "18323076"    -- replace with your student id
username  =  "haskinsm"   -- replace with your TCD username


declaration -- do not modify this
 = unlines
     [ ""
     , "@@@ This exercise is all my own work."
     , "@@@ Signed: " ++ name
     , "@@@ "++idno++" "++username
     ]


{- Part 1

Write a function 'raise' that converts a string to uppercase

Hint: 'toUpper :: Char -> Char' converts a character to uppercase
if it is lowercase. All other characters are unchanged.
It is imported should you want to use it.

-}
raise :: String -> String  --String is a synonym for [char] --Don't mess with the types here
raise str = helper str

helper :: [Char] -> [Char]   
helper [] = []
--helper (x:_) = x  --Don't think this line is needed. Think the below line will handle 'singletons'
helper (x:xs) = toUpper x : helper xs

 
{- Part 2

Write a function 'nth' that returns the nth element of a list.
Hint: the test will answer your Qs

-}
nth :: Int -> [a] -> a
nth 1 (x:xs) = x
nth i (x:xs) = nth (i-1) xs



{- Part 3

Write a function `commonLen` that compares two sequences
and reports the length of the prefix they have in common.

-}
commonLen :: Eq a => [a] -> [a] -> Int
commonLen [] [] = 0
commonLen [] (y:ys) = 0
commonLen (x:xs) [] = 0
commonLen (x:xs) (y:ys) | x == y = 1 + commonLen xs ys
                        | otherwise = 0

{- Part 4

(TRICKY!) (VERY!)

Write a function `runs` that converts a list of things
into a list of sublists, each containing elements of the same value,
which when concatenated together give the same list

So `runs [1,2,2,1,3,3,3,2,2,1,1,4]`
 becomes `[[1],[2,2],[1],[3,3,3],[2,2],[1,1],[4]]`

Hint:  `elem :: Eq a => a -> [a] -> Bool`

HINT: Don't worry about code efficiency
       Seriously, don't!

-}
runs :: Eq a => [a] -> [[a]]  --Note: could be any type
{- runs [] = [] --Might need to let it equal nothing or something like that
runs x:xs = [x : runsHelper x xs] : runs (runsSecondHelper x xs) --Need to alter xs so doesnt have the elements equal to x when use it recursively here

runsHelper :: a -> [a] -> [a]
runsHelper b [] = []
runsHelper [] (x:xs) = []
runsHelper b (x:xs) | b == x = x : runsHelper b xs
                    | otherwise = []

runsSecondHelper :: a -> [a] -> [a]
runsSecondHelper a [] = []
runsSecondHelper a (x:xs) | a == x = runsSecondHelper a xs
                          | otherwise = xs -}

--import Data.List(group)   Must import at the top of the page or doesn't run
runs [] = []
runs xs = group xs  --Makes use of the Haskell prelude function 'group'
{- check Hoogle when you're looking for a function 
   that might already exist -> can search by type, so in this case 
   search Eq a => [a] -> [[a]] -}