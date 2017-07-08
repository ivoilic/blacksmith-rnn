<?php
function blacksmithOutToArray($dir){
	$input = file_get_contents($dir);

	$cardArray = explode("\n\n", $input);
	unset($cardArray[count($cardArray)-1]);
	unset($cardArray[0]);
	$cardArray = array_values($cardArray);

	foreach($cardArray as &$value) {
	    $value = explode("|", $value);
			unset($value[count($value)-1]);
			unset($value[0]);
			$value = array_values($value);
			for($i=0; $i<count($value); $i++) {
				$value[$i] = substr($value[$i],1, strlen($value[$i])-1);
			}
	}
	unset($value);

	return $cardArray;
}
?>
