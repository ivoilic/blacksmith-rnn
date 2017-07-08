<?php
require("./output_handling.php");
echo "\r\nWhere is your blacksmith-rnn output file?\r\n";
$handle = fopen ("php://stdin","r");
$dir = trim(fgets($handle));

$cardArray = blacksmithOutToArray($dir);
print_r($cardArray);

echo "\r\nSet title:\r\n";
$handle = fopen ("php://stdin","r");
$setTitle = trim(fgets($handle));

echo "\r\nTitle Index:\r\n";
$handle = fopen ("php://stdin","r");
$titleIndex = trim(fgets($handle));

echo "\r\nBody Index:\r\n";
$handle = fopen ("php://stdin","r");
$bodyIndex = trim(fgets($handle));

echo "\r\nCost Index:\r\n";
$handle = fopen ("php://stdin","r");
$costIndex = trim(fgets($handle));

if (createCardSet($setTitle, $cardArray, $titleIndex, $bodyIndex, $costIndex)){
	echo "\r\nSet Created!";
};
?>
