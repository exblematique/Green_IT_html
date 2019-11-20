<?php
include('META-INF/config.php');
$sql="SELECT * FROM users";
$result=mysqli_query($link,$sql);

// Fetch all
echo mysqli_fetch_all($result,MYSQLI_ASSOC);

// Free result set
mysqli_free_result($result);

mysqli_close($link);
?> 