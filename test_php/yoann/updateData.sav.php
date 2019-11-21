<?php
include("META-INF/config.php");
$foyer = $_POST['clients']['0'];
$result = $link->query('SELECT Value, DATE_FORMAT(Date, "%Y-%m-%d") as Date FROM Data WHERE Foyer="'.$foyer['name'].'"');
//SELECT now();
?>
{
    "date": "<?php echo $_POST["date"]; ?>",
    "clients": [
        {
            "name": "<?php echo $foyer['name']; ?>",
            "data": [
        <?php
        $out = "";
        while($row = $result->fetch_array()){
            $out.='{"time": "'.$row['Date'].'", "value":'.$row['Value'].'},';
        }
        echo (rtrim($out, ","));
        ?>
            ]
        }
    ]
}
<?php
$result->free();
$link->close();
?>