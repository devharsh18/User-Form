<?php
session_start();

header('Content-type: image/png');

$text = substr(md5(rand()), 0, 6);
$_SESSION['captcha'] = $text;

$font_size = 20;
$width = 100;
$height = 40;

$image = imagecreate($width, $height);
$background_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);

imagettftext($image, $font_size, 0, 10, 30, $text_color, './arial.ttf', $text);
imagepng($image);
imagedestroy($image);
?>
