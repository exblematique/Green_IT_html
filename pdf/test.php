<?php
$db = new mysqli('localhost:3306','GHT','azerty1234','GHT');
$result = $db->query('SELECT AVG(Value) FROM Data WHERE Foyer="A"');
while($row = $result->fetch_array())
{
	$moyenne = $row['AVG(Value)'];
	echo $moyenne;
}

$db->close();
?>