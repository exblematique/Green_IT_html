<!DOCTYPE html>
<html lang="fr-FR">
<head>
  <title>Team 39 - Index</title>
  <script src="lightweight-charts.standalone.production.js"></script>
</head>
<body>
    <div id="listClient"><?php
include('META-INF/config.php');
$sql="SELECT * FROM users";
$result=mysqli_query($link,$sql);
var_dump(mysqli_fetch_all($result,MYSQLI_ASSOC));
mysqli_free_result($result);
mysqli_close($link);?> </div>
    <div id="client"></div>
    
    <script>
    //Function will enable to receive more information from database
    function receiveInfo(client, start_date, end_date){
        data = {"client": client}
        if (start_date) data+= 
    }
    </script>
</body>
</html>