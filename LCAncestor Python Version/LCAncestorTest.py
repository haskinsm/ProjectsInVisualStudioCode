import unittest
## target = __import__("LCAncestor.py")

from LCAncestor import BinaryTree
class LCAncestorTest(unittest.TestCase):

    def BST = BinaryTree('d')

    BST.root.left_Node = Node('c')
    BST.root.left_Node.Parent = BST.root
    BST.root.right_Node = Node('e')
    BST.root.right_Node.Parent = BST.root

    BST.root.left_Node.left_Node = Node('a')
    BST.root.left_Node.left_Node.Parent = BST.root.left_Node
    BST.root.left_Node.right_Node = Node('b')
    BST.root.left_Node.right_Node.Parent = BST.root.left_Node

    BST.root.right_Node.left_Node = Node('f')
    BST.root.right_Node.left_Node.Parent = BST.root.right_Node
    BST.root.right_Node.right_Node = Node('g')
    BST.root.right_Node.right_Node.Parent = BST.root.right_Node

    def test_BST(self)
        self.assertEqual(lowest_common_ancestor(BST.root.right_Node.left_Node,BST.root.right_Node.right_Node), 'f', "Should be f")