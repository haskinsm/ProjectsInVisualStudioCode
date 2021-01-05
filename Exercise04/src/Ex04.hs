{- butrfeld Andrew Butterfield -}
module Ex04 where

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

-- Datatypes -------------------------------------------------------------------

-- do not change anything in this section !


-- a binary tree datatype, honestly!
data BinTree k d
  = Branch (BinTree k d) (BinTree k d) k d
  | Leaf k d
  | Empty
  deriving (Eq, Show)


-- Part 1 : Tree Insert -------------------------------

-- Implement:
ins :: Ord k => k -> d -> BinTree k d -> BinTree k d
ins k d Empty  = Leaf k d
ins k d (Leaf key value)
      | k < key  = Branch (Leaf k d) (Empty) key value
      | k > key  = Branch (Empty) (Leaf k d) key value
      | k == key = Leaf k d
ins k d (Branch left right key value)
      | k < key  = Branch (ins k d left) right key value
      | k > key  = Branch left (ins k d right) key value
      | k == key = Branch left right k d

-- Part 2 : Tree Lookup -------------------------------

-- Implement:
lkp :: (Monad m, Ord k) => BinTree k d -> k -> m d
lkp Empty k = fail ( "Couldn't find beacuse the tree was empty")
lkp (Leaf key value) k 
  | k == key = return value
  | otherwise = fail ( "Couldn't find key in tree" )
lkp (Branch left right key value) k
  | k < key  = lkp left k
  | k > key  = lkp right k
  | k == key = return value

-- Part 3 : Tail-Recursive Statistics

{-
   It is possible to compute BOTH average and standard deviation
   in one pass along a list of data items by summing both the data
   and the square of the data.
-}
twobirdsonestone :: Double -> Double -> Int -> (Double, Double)
twobirdsonestone listsum sumofsquares len
 = (average,sqrt variance)
 where
   nd = fromInteger $ toInteger len
   average = listsum / nd
   variance = sumofsquares / nd - average * average

{-
  The following function takes a list of numbers  (Double)
  and returns a triple containing
   the length of the list (Int)
   the sum of the numbers (Double)
   the sum of the squares of the numbers (Double)

   You will need to update the definitions of init1, init2 and init3 here.
-}
getLengthAndSums :: [Double] -> (Int,Double,Double)
getLengthAndSums ds = getLASs init1 init2 init3 ds
init1 = 0
init2 = 0.0
init3 = 0.0

{-
  Implement the following tail-recursive  helper function
-}
getLASs :: Int -> Double -> Double -> [Double] -> (Int,Double,Double)
getLASs a b c [] = (a,b,c)
getLASs a b c (x:xs) = do
    let init1 = a + 1 --The inits above are not in scope here I think, they are initially passed in and then the function is called again below with the new updated inits which will be updated again and so on 
    let init2 = b + x --The arguments are storing the changes (think of them as the updated local variables that you'd have in a while loop)
    let init3 = c + x*x
    getLASs init1 init2 init3 xs

-- Final Hint: how would you use a while loop to do this?
--   (assuming that the [Double] was an array of double)
