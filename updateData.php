<?php
include("META-INF/config.php");
$foyer = $_POST['clients'];
$len = count($foyer);
?>
{
    "date": "<?php echo $link->query('SELECT now() AS n FROM Data')->fetch_array()['n']; ?>",
    "clients": [ <?php for ($i=0; $i<$len; $i++) { ?>
        {
            "name": "<?php echo $foyer[$i]['name']; ?>",
            "data": [
        <?php
        $result = $link->query('SELECT Value, Date FROM Data WHERE Foyer="'.$foyer[$i]['name'].'" AND Date BETWEEN "'.$_POST['start'].'" AND "'.$_POST['end'].'";');
        $out = "";
        while($row = $result->fetch_array()){
            $out.='{"time": "'.$row['Date'].'", "value":'.$row['Value'].'},';
        }
        echo (rtrim($out, ","));
        ?>
            ]
        }
    <?php if ($i!=$len-1) echo ',';}; ?>
    ]
}
<?php
$result->free();
$link->close();
?>