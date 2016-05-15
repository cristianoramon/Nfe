<?php
require('class/BCGFont.php');
require('class/BCGColor.php');
require('class/BCGDrawing.php');
require('class/BCGcode128.barcode.php');

$font = new BCGFont('./class/font/Arial.ttf', 18);
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255);

$code = new BCGcode128();
$code->setScale(1);
$code->setThickness(30);
$code->setForegroundColor($color_black);
$code->setBackgroundColor($color_white);
$code->setFont($font);
$code->setStart('C');
$code->setTilde(true);
$code->parse('27080412213922000166550000000000140006797993');

// Drawing Part
$drawing = new BCGDrawing('teste.png', $color_white);
$drawing->setBarcode($code);
$drawing->draw();

header('Content-Type: image/png');

$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
?> 
