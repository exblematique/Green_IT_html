<?php
include("META-INF/config.php");
$img = $_POST['img'];

	$decoded = base64_decode(substr($img,22));
	file_put_contents('/var/www/html/img/image1.png', $decoded);
/* 
imagepng($decoded,"/var/tmp/image1.png"
*/
?>
