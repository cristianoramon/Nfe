<?php
include('image.class.php');
$image = new image(320, 200);
for($i = 1; $i <= 15; $i++) {
	$image->drawFilledRectangle((10 + (10 * $i)), (170 - (10 * $i)), (30 + (10 * $i)), (190 - (10 * $i)), '0000ff50');
}
for($i = 1; $i <= 15; $i++) {
	$image->drawFilledRectangle((110 + (10 * $i)), (170 - (10 * $i)), (130 + (10 * $i)), (190 - (10 * $i)), '0000ff50');
}
for($i = 1; $i <= 15; $i++) {
	$image->drawFilledRectangle((10 + (10 * $i)), (10 + (10 * $i)), (30 + (10 * $i)), (30 + (10 * $i)), 'ff000050');
}
for($i = 1; $i <= 15; $i++) {
	$image->drawFilledRectangle((110 + (10 * $i)), (10 + (10 * $i)), (130 + (10 * $i)), (30 + (10 * $i)), 'ff000050');
}


$image->saveImage('demo.png');
echo "<img hspace='0' vspace='0' src='demo.png' border='0'>";
?>