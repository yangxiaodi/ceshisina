<?php

$BookTitles = new SplDoublyLinkedList();
$BookTitles->push("Introduction to Algorithm");
$BookTitles->push("Introduction to PHP and Data structures");
$BookTitles->push("Programming Intelligence");
$BookTitles->push("Mediawiki Administrative tutorial guide");
$BookTitles->add(1, "Introduction to Calculus");
$BookTitles->add(3, "Introduction to Graph Theory");
for ($BookTitles->rewind(); $BookTitles->valid(); $BookTitles->next()) {
    echo $BookTitles->current() . "\n";
}


var_dump($BookTitles);