<?php
include("META-INF/config.php");
$img = $_POST['img'];

	$decoded = base64_decode(substr($img,22));
imagepng($decoded,"/var/tmp/image1.png");
?>
