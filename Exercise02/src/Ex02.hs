{- butrfeld Andrew Butterfield -}
module Ex02 where
  --Also not sure if you wanted me to replace your name with mine up there, apologies if I have got this wrong
import Data.List hiding (find)  --Imported as used concat 

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

-- Datatypes and key functions -----------------------------------------------

-- do not change anything in this section !

type Id = String

data Expr
  = Val Double
  | Add Expr Expr
  | Mul Expr Expr
  | Sub Expr Expr
  | Dvd Expr Expr
  | Var Id
  | Def Id Expr Expr
  deriving (Eq, Show)

type Dict k d  =  [(k,d)]

define :: Dict k d -> k -> d -> Dict k d
define d s v = (s,v):d

find :: Dict String d -> String -> Either String d
find []             name              =  Left ("undefined var "++name)
find ( (s,v) : ds ) name | name == s  =  Right v
                         | otherwise  =  find ds name

type EDict = Dict String Double

v42 = Val 42 ; j42 = Just v42

-- do not change anything above !

-- Part 1 : Evaluating Expressions -- (50 test marks, worth 25 Exercise Marks) -

-- Implement the following function so all 'eval' tests pass.

-- eval should return `Left msg` if:
  -- (1) a divide by zero operation was going to be performed;
  -- (2) the expression contains a variable not in the dictionary.
  -- see test outcomes for the precise format of those messages

eval :: EDict -> Expr -> Either String Double --Might need to change the last to Maybe Double, but returns string on occasion so think its ok as is
eval d (Var a) = find d a
eval _ (Val a) = Right a --Presume do not need the dict as i think theres no need for it here

eval d (Add x y) 
   = case (eval d x, eval d y) of --Evaluates both expressions and pattern matches the case
     (Right a, Right b) -> Right (a+b) --Add values
     (Left a, Left b) -> Left(concat(a,b)) --Add/Concat Strings, might take this out 
     (Right a, _)     -> Left "undefined var y"
     (_, Right b)     -> Left "undefined var x"
     _                -> Left "undefined"
          

eval d (Mul x y)
   = case (eval d x, eval d y) of
     (Right a, Right b) -> Right(a*b)
     (Right a, _)       -> Left "undefined var y"
     (_, Right a)       -> Left "undefined var x"
     _                  -> Left "undefined"

eval d (Sub x y)
   = case (eval d x, eval d y) of 
     (Right a, Right b) -> Right(a-b)
     (Right a, _)       -> Left "undefined var y"
     (_, Right a)       -> Left "undefined var x"
     _                  -> Left "undefined"

eval d (Dvd x (Val 0)) = Left "div by zero" --So as to prohibit deviding by 0
eval d (Dvd x y) 
    = case (eval d x, eval d y) of 
      (Right a, Right b) -> Right(a/b)
      (Right a, _)       -> Left "undefined var y"
      (_, Right a)       -> Left "undefined var x"
      _                  -> Left "undefined"

eval d (Def a x y) 
    = let z = eval d x --Evaluates expression x
    in case (z) of 
      (Right z) -> eval (define d a z) y --Adds the result of eval. expr x to the dict and uses newly created dict for expr b
      _         -> Left "undefined"

-- Part 1 : Expression Laws -- (15 test marks, worth 15 Exercise Marks) --------

{-

There are many, many laws of algebra that apply to our expressions, e.g.,

  x + y            =  y + x         Law 1
  x + (y + z)      =  (x + y) + z   Law 2
  x - (y + z)      =  (x - y) - z   Law 3
  (x + y)*(x - y)  =  x*x - y*y     Law 4
  ...

  We can implement these directly in Haskell using Expr

  Function LawN takes an expression:
    If it matches the "shape" of the law lefthand-side,
    it replaces it with the corresponding righthand "shape".
    If it does not match, it returns Nothing

    Implement Laws 1 through 4 above
-}


law1 :: Expr -> Maybe Expr
law1 e = case e of 
          (Add x y) -> Just (Add y x) --Use Just/Nothing now not Right/Left
          _         -> Nothing

law2 :: Expr -> Maybe Expr
law2 e = case e of 
          (Add x (Add y z)) -> Just (Add (Add x y) z)
          _                 -> Nothing

law3 :: Expr -> Maybe Expr
law3 e = case e of 
          (Sub x (Add y z)) -> Just(Sub(Sub x y) z)
          _                 -> Nothing

law4 :: Expr -> Maybe Expr --Will need to write a helper function of some sorts to make sure x == z, y== q
law4 e = case e of    
          (Mul (Add x y) (Sub z q)) -> Just(Sub (Mul x z) (Mul y q)) --Note need x == z and y == q
          _                         -> Nothing
