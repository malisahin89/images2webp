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
    imagewebp($webp, str_replace($fileinfo->getBasename(), "", $fileinfo->getPathname()).'/'.$fileinfo->getBasename('.' . $fileinfo->getExtension()) . '.webp', $quality);
    imagedestroy($jpg);
    imagedestroy($webp);
}

// ALL DIR
$baseDir = './';
// $fileExtensions = ['png', 'jpeg', 'jpg', 'gif'];
$fileExtensions = ['png', 'jpeg', 'jpg'];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($iterator as $fileinfo) {
    $extension = strtolower(pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION));
    
    if (in_array($extension, $fileExtensions)) {
        echo $fileinfo->getPathname() . "<br>";
        webpcevir($fileinfo, "80");
    }
}