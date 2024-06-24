<?php
function webpcevir($fileinfo, $quality)
{

    $image_info = getimagesize($fileinfo);
    if ($image_info === false) {
        die("Dosya geçerli bir resim dosyası değil.");
    }

    $mime = $image_info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $jpg = imagecreatefromjpeg($fileinfo);
            break;
        case 'image/png':
            $jpg = imagecreatefrompng($fileinfo);
            break;
        // case 'image/gif':
        //     $jpg = imagecreatefromgif($fileinfo);
        //     break;
        default:
            die("Desteklenmeyen formatı: $mime");
    }

    $w = imagesx($jpg);
    $h = imagesy($jpg);
    $webp = imagecreatetruecolor($w, $h);
    imagecopy($webp, $jpg, 0, 0, 0, 0, $w, $h);
    imagewebp($webp, $fileinfo.'.webp', $quality);
    imagedestroy($jpg);
    imagedestroy($webp);
}


// CURRENT DIR
$dizin = './';
$dosyalar = scandir($dizin);
$jpg_png_dosyalari = array();

foreach ($dosyalar as $dosya) {
    $uzanti = pathinfo($dosya, PATHINFO_EXTENSION);
    // if (strtolower($uzanti) == 'jpg' || strtolower($uzanti) == 'jpeg' || strtolower($uzanti) == 'png'|| strtolower($uzanti) == 'gif') {
    if (strtolower($uzanti) == 'jpg' || strtolower($uzanti) == 'jpeg' || strtolower($uzanti) == 'png') {
        $jpg_png_dosyalari[] = $dosya;
    }
}

echo "Dizinindeki JPG ve PNG dosyaları:<br>";
foreach ($jpg_png_dosyalari as $dosya) {
    webpcevir($dosya, "80");
    echo $dosya . "<br>";
}
