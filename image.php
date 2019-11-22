<?php
include("META-INF/config.php");
$img = $_POST['img'];
$decoded = base64_decode(substr($img,22));
file_put_contents('/var/tmp/image1.png', $decoded);
$img2 = $_POST['img2'];
$decoded = base64_decode(substr($img2,22));
file_put_contents('/var/tmp/image2.png', $decoded);
//$result = imagecreatefromstring($img);
/*if ($im !== false) {
    header('Content-Type: image/png');
    imagepng($img);
    imagedestroy($img);
}
else {
    echo 'An error occurred.';
}*/
echo "coucou";
/*
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $file = uniqid() . '.png';

$img2 = $_POST['img2'];
                $img2 = str_replace('data:image/png;base64,', '', $img2);
                $img2 = str_replace(' ', '+', $img2);
                $data2 = base64_decode($img2);
                $file2 = uniqid() . '.png';
*/
?>
