<?php

function wordWrapAnnotation(&$image, &$draw, $text, $maxWidth)
{
		$words = explode(" ", $text);
		$lines = array();
		$i = 0;
		$lineHeight = 0;
		while($i < count($words) )
		{
				$currentLine = $words[$i];
				if($i+1 >= count($words))
				{
						$lines[] = $currentLine;
						break;
				}
				//Check to see if we can add another word to this line
				$metrics = $image->queryFontMetrics($draw, $currentLine . ' ' . $words[$i+1]);
				while($metrics['textWidth'] <= $maxWidth)
				{
						//If so, do it and keep doing it!
						$currentLine .= ' ' . $words[++$i];
						if($i+1 >= count($words))
								break;
						$metrics = $image->queryFontMetrics($draw, $currentLine . ' ' . $words[$i+1]);
				}
				//We can't add the next word to this line, so loop to the next line
				$lines[] = $currentLine;
				$i++;
				//Finally, update line height
				if($metrics['textHeight'] > $lineHeight)
						$lineHeight = $metrics['textHeight'];
		}
		return array($lines, $lineHeight);
}

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

		$ppi = 300;
    $cardWidth = 2.36;
    $cardHeight = 3.28;
    $cardBorder = 0.11;

    $cardBorderColor = "black";

		$title = $value[$titleIndex];
	 	$body = $value[$bodyIndex];

    //All measurements are in inches

    $canvas = new Imagick();
    $canvas->newImage($cardWidth*$ppi, $cardHeight*$ppi, "red");
    $cardArt = new Imagick("https://unsplash.it/".$cardWidth*$ppi."/".$cardHeight*$ppi."/?random");
    $canvas->compositeImage($cardArt, Imagick::COMPOSITE_DEFAULT, 0, 0);

    $draw = new ImagickDraw();

    $draw->setStrokeColor("transparent");
    $draw->setFillColor($cardBorderColor);

    $draw->rectangle(0, 0, $cardBorder*$ppi, $cardHeight*$ppi);
    $draw->rectangle(0, 0, $cardWidth*$ppi, $cardBorder*$ppi);
    $draw->rectangle(($cardWidth-$cardBorder)*$ppi,0, $cardWidth*$ppi, $cardHeight*$ppi);
    $draw->rectangle(0,($cardHeight-$cardBorder)*$ppi, $cardWidth*$ppi, $cardHeight*$ppi);

    $draw->setStrokeColor("transparent");
    $draw->setFillColor("white");

    $draw->setFillOpacity(0.8);

    $draw->rectangle(0.18*$ppi,0.18*$ppi, $cardWidth*$ppi-0.18*$ppi, 0.18*$ppi+0.18*$ppi);
    $draw->rectangle(0.18*$ppi,2.06*$ppi, $cardWidth*$ppi-0.18*$ppi, $cardHeight*$ppi-0.18*$ppi);

    $draw->setFillColor("black");

    $draw->setFillOpacity(1);

    $draw->setFontSize( 0.11*$ppi );
    $draw->annotation(0.22*$ppi, 0.31*$ppi, $title);

    $draw->annotation(1.80*$ppi, 0.31*$ppi, "@");
    $draw->annotation(1.92*$ppi, 0.31*$ppi, "@");
    $draw->annotation(2.04*$ppi, 0.31*$ppi, "@");
    $canvas->drawImage($draw);

    list($lines, $lineHeight) = wordWrapAnnotation($canvas, $draw, $body, $cardWidth*$ppi-0.18*$ppi-0.18*$ppi-0.18*$ppi);
    for($i = 0; $i < count($lines); $i++){
        $canvas->annotateImage($draw, 0.27*$ppi, 2.23*$ppi + $i*$lineHeight, 0, $lines[$i]);
    }

		$canvas->setImageFormat('jpg');
		$fileName = $setTitle.'-'.$cardCount.'.jpg';
		file_put_contents ($fileLocation."/".$fileName, $canvas);

		$cardCount++;

	}

	unset($value);

	return true;
}
?>
