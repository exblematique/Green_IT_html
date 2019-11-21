<?php
include("META-INF/config.php");
?>
{
    "date": "<?php echo $_POST["date"]; ?>",
    "clients": [
    <?php for ($i=0; i<=count($_POST['clients']); $i++){
        $curFoyer = $_POST['clients'][i];
        $result = $link->query('SELECT Value, DATE_FORMAT(Date, "%Y-%m-%d") as DateFormat FROM Data WHERE Foyer="'.$foyer.'"');
        ?>
        {
            "name": "<?php echo $curFoyer['name']; ?>",
            "data": [
        <?php
        $out = "";
        while($row = $result->fetch_array()){
            $out.='{"time": "'.$row['DateFormat'].'", "value":'.$row['Value'].'},';
        }
        echo (rtrim($out, ","));
        ?>
            ]
        }
    <?php } ?>
    ]
}
<?php
$result->free();
$link->close();
?>