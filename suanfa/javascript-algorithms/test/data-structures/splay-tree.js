var STree = require('../../src/data-structures/splay-tree');
var sTree = new STree.SplayTree();
sTree.insert(10);
sTree.insert(5);
sTree.insert(15);
sTree.insert(7);
sTree.insert(12);
sTree.search(10);
console.log(sTree._root);
sTree.remove(10);
console.log(sTree._root);
sTree.search(15);
console.log(sTree._root);