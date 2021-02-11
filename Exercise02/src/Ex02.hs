{- butrfeld Andrew Butterfield -}
module Ex02 where
  --Also not sure if you wanted me to replace your name with mine up there, apologies if I have got this wrong

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

--Var is of type String and Val of type Double

eval :: EDict -> Expr -> Either String Double 
eval d (Var a) = find d a --Need Dict here
eval _ (Val a) = Right a --Do not need the dict 

eval d (Add x y) 
   = case (eval d x, eval d y) of --Evaluates both expressions and pattern matches the case
     (Right a, Right b) -> Right (a+b) --Add values 
     (_, Left b)  -> Left (b) --Need to have this so prints error message that may arrive from eval d y
     (Left a, _)  -> Left (a)
          
eval d (Mul x y)
   = case (eval d x, eval d y) of
     (Right a, Right b) -> Right(a*b)
     (_, Left b)  -> Left (b) --Need to have this so prints error message that may arrive from eval d y
     (Left a, _)  -> Left (a)

eval d (Sub x y)
   = case (eval d x, eval d y) of 
     (Right a, Right b) -> Right(a-b)
     (_, Left b)  -> Left (b) --Need to have this so prints error message that may arrive from eval d y
     (Left a, _)  -> Left (a)

eval _ (Dvd x (Val 0)) = Left "div by zero" --So as to prohibit deviding by 0
eval d (Dvd x y) 
    = case (eval d x, eval d y) of 
      (Right a, Right b) -> Right(a/b)
      (_, Left b)  -> Left (b) --Need to have this so prints error message that may arrive from eval d y
      (Left a, _)  -> Left (a)
     

eval d (Def a x y) 
    = let z = eval d x --Evaluates expression x
    in case (z) of 
      (Right z) -> eval (define d a z) y --Adds the result of eval. expr x to the dict and uses newly created dict for expr b
      (Left z)  -> Left (z) --Need to have this so prints error message that may arrive from eval d y
      


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

law4 :: Expr -> Maybe Expr 
law4 (Mul (Add x y) (Sub z q)) 
        | x == z && y == q = Just(Sub (Mul x z) (Mul y q)) 
        | otherwise = Nothing
law4 _ = Nothing
         
