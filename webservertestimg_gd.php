<?
# Web Server Test Image GD PNG

$sz=100;

$img=imagecreatetruecolor($sz, $sz);
imagealphablending($img, false);
imagefill($img, 0, 0, imagecolorallocatealpha($img, 0, 0, 0, 127));
imagesavealpha($img, true);

# imagefilledrectangle($img, 0, 0, $sz, $sz, imagecolorallocate($img, 255, 255, 0));
imagefilledellipse($img, floor($sz/2), floor($sz/2), $sz, $sz, imagecolorallocate($img, 0, 255, 0));

header('Content-Type: image/png');
imagepng($img);
imagedestroy($img); 
?>