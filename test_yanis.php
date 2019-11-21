<?php
include("META-INF/config.php");
$result = $link->query('SELECT * FROM Data WHERE Foyer="A"');
while($row = $result->fetch_array())
{
	echo "Date : " . $row['Date'] . " - Value : " . $row['Value'];
	echo "<br />";
}
$result->free();
$link->close();
?>