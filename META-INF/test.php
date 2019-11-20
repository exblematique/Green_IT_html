<?php
$db = new mysqli('localhost:3306','GHT','azerty1234','GHT');
$result = $db->query('SELECT * FROM Data WHERE Foyer="A"');
while($row = $result->fetch_array())
{
	echo "Date : " . $row['Date'] . " - Value : " . $row['Value'];
	echo "<br />";
}
$resource->free();
$db->close();
?>