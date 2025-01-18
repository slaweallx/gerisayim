<?php
// generate_image.php
header('Content-Type: image/png');

// GET parametreleri al
$day     = isset($_GET['day'])     ? (int) $_GET['day']     : 0;
$month   = isset($_GET['month'])   ? (int) $_GET['month']   : 0;
$year    = isset($_GET['year'])    ? (int) $_GET['year']    : 0;
$days    = isset($_GET['days'])    ? (int) $_GET['days']    : 0;
$hours   = isset($_GET['hours'])   ? (int) $_GET['hours']   : 0;
$minutes = isset($_GET['minutes']) ? (int) $_GET['minutes'] : 0;

// Resim boyutu
$width  = 800;
$height = 400;

// Resim oluştur
$im  = imagecreatetruecolor($width, $height);

// Renkleri tanımla
$bgColor       = imagecolorallocate($im, 240, 240, 240);
$textColor     = imagecolorallocate($im, 0, 0, 0);
$highlightColor= imagecolorallocate($im, 0, 80, 200);

// Arka planı doldur
imagefilledrectangle($im, 0, 0, $width, $height, $bgColor);

// TTF font kullanmak istersek path belirtmemiz gerekir. Yoksa imagestring() ile basit 5x9 font kullanacağız.
// Örnek basit text:
$fontSize = 5; // imagestring için font id

// Yazılacak metinler
$title     = "Hedef Tarih: $day/$month/$year";
$remaining = "Kalan Süre: $days gün, $hours saat, $minutes dakika";

// Metni yerleştirme (basit, sabit konum)
imagestring($im, $fontSize, 20, 20, $title, $textColor);
imagestring($im, $fontSize, 20, 50, $remaining, $highlightColor);

// En sonda resmi oluşturalım
imagepng($im);
imagedestroy($im);
