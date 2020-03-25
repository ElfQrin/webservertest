<?  
# Web Server Test Image ImageMagick (Imagick) PNG

$sz=100;

$draw=new \ImagickDraw();
$draw->setStrokeOpacity(1);
$draw->setStrokeColor('rgb(0, 255, 0)');
$draw->setFillColor('rgb(0, 255, 0)');
$draw->setStrokeWidth(0);
# $draw->setFontSize(16);

# $draw->rectangle(0, 0, $sz, $sz);
$draw->circle(floor($sz/2), floor($sz/2), $sz-floor($sz/2), $sz);

$imagick = new \Imagick();
$imagick->newImage($sz, $sz, 'transparent');
$imagick->setImageFormat('png');
$imagick->drawImage($draw);

header("Content-Type: image/png");
echo $imagick->getImageBlob();
?> 