<?php
require 'C:\laragon\www\quoros\vendor\autoload.php';
$app = require 'C:\laragon\www\quoros\bootstrap\app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$img = imagecreatetruecolor(400, 400);
$bg = imagecolorallocate($img, 20, 20, 20);
imagefilledrectangle($img, 0, 0, 400, 400, $bg);
$red = imagecolorallocate($img, 255, 80, 80);
imagefilledellipse($img, 200, 200, 220, 220, $red);
$path = 'C:\laragon\www\quoros\storage\app\cloud-debug-valid.png';
imagepng($img, $path);
imagedestroy($img);
$file = new Illuminate\Http\UploadedFile($path, 'cloud-debug-valid.png', 'image/png', null, true);
try {
    $svc = new App\Services\CloudinaryService();
    $result = $svc->uploadProfile($file);
    var_dump($result);
} catch (Throwable $e) {
    echo "CLASS: " . get_class($e) . PHP_EOL;
    echo "MESSAGE: " . $e->getMessage() . PHP_EOL;
    $prev = $e->getPrevious();
    echo "PREV: " . ($prev ? get_class($prev) . ': ' . $prev->getMessage() : 'none') . PHP_EOL;
}
