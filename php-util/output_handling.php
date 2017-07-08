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

function createCardSet($setTitle, $cardArray, $titleIndex, $bodyIndex, $costIndex){


	$fileLocation = "./sets/".$setTitle;
	mkdir($fileLocation, 0755);
	$cardCount = 0;

	foreach($cardArray as &$value) {

		$canvas = new Imagick();
		$canvas->newImage(500, 700, "white");

		$draw = new ImagickDraw();
		$draw->annotation(20, 50, $value[$titleIndex]);
		$canvas->drawImage($draw);

		$canvas->setImageFormat('jpg');
		$fileName = $setTitle.'-'.$cardCount.'.jpg';
		file_put_contents ($fileLocation."/".$fileName, $canvas);

		$cardCount++;

	}

	unset($value);

	return true;
}
?>
