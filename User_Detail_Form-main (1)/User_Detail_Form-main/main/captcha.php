<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CAPTCHA
    if (isset($_POST['captcha'])) {
        if ($_POST['captcha'] == $_SESSION['captcha']) {
            echo 'success';
        } else {
            echo 'failure';
        }
    }
    exit;
}

// Generate CAPTCHA image
$captcha_code = '';
$captcha_code_length = 6;
$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$characters_length = strlen($characters);
for ($i = 0; $i < $captcha_code_length; $i++) {
    $captcha_code .= $characters[rand(0, $characters_length - 1)];
}
$_SESSION['captcha'] = $captcha_code;

// Create CAPTCHA image
$captcha_image = imagecreate(200, 50);
$background_color = imagecolorallocate($captcha_image, 255, 255, 255);
$text_color = imagecolorallocate($captcha_image, 0, 0, 0);
$font = __DIR__ . '/arial.ttf'; // Adjust path to a valid font file
imagettftext($captcha_image, 20, 0, 10, 35, $text_color, $font, $captcha_code);
header('Content-Type: image/png');
imagepng($captcha_image);
imagedestroy($captcha_image);
?>
