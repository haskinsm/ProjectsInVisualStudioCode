import unittest
import LCAncestor


class test_LCA(unittest.TestCase):

    BST = BinaryTree('d')

    BST.root.left_Node = LCAncestor.Node('c')
    BST.root.left_Node.Parent = BST.root
    BST.root.right_Node = LCAncestor.Node('e')
    BST.root.right_Node.Parent = BST.root

    BST.root.left_Node.left_Node = LCAncestor.Node('a')
    BST.root.left_Node.left_Node.Parent = BST.root.left_Node
    BST.root.left_Node.right_Node = LCAncestor.Node('b')
    BST.root.left_Node.right_Node.Parent = BST.root.left_Node

    BST.root.right_Node.left_Node = LCAncestor.Node('f')
    BST.root.right_Node.left_Node.Parent = BST.root.right_Node
    BST.root.right_Node.right_Node = LCAncestor.Node('g')
    BST.root.right_Node.right_Node.Parent = BST.root.right_Node

    def test_BST(self):
        self.assertEqual(LCAncestor.BST.root.val, 'd', "Should be d")

    def test_LCA(self):
        self.assertEqual(LCAncestor.lowest_common_ancestor(BST.root.right_Node.left_Node,BST.root.right_Node.right_Node), 'f', "Should be f")

if __name__ == '__main__':
    unittest.main()