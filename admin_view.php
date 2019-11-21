<!DOCTYPE html>
<html lang="fr-FR">
<head>
  <title>Team 39 - Admin view</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="lightweight-charts.standalone.production.js"></script>
</head>
<body>
    <div id="listClient"><?php/*
include('META-INF/config.php');
$sql="SELECT * FROM users";
$result=mysqli_query($link,$sql);
var_dump(mysqli_fetch_all($result,MYSQLI_ASSOC));
mysqli_free_result($result);
mysqli_close($link);*/?> </div>
    <div id="clients"></div>
    
    <script>
clients = [];
graphs = [];

//Function will create a new graph in realTime
function createGraph (name, start, end) {
    client = {
        name: name,
        start_info: "",
        end_info: ""
    }
    receiveInfo([client], "2019-01-01", "2019-01-01", true);
}

createGraph("A", 1, 3);
createGraph("B", 1, 3);
    
//Function will enable to receive more information from database
function receiveInfo(clients, start_date, end_date, newClient){
    data = {clients: clients,
            date: start_date,
            end: end_date}
    $.ajax({
        type: "POST",
        url: "updateData.php",
        data: data,
        dataType: "json"
    }).done(function(result){
        var rc = result.clients;
        for (client in rc) {
            console.log("Name: "+ rc[client].name+"\nData: "+rc[client].data+"\nGraph: "+graphs[rc[client].name]+"\n");
            if (newClient) {
                var div = document.createElement("div");
                div.id = rc[client].name;
                var graph = LightweightCharts.createChart(div, { width: 400, height: 300 });
                graphs[rc[client].name] = graph.addHistogramSeries({
                    base: 0,
                    localization: {locale: 'fr-FR'}
                });
                graphs[rc[client].name].setData(rc[client].data);
                document.querySelector("#clients").appendChild(div);
            }
            else graphs[rc[client].name].updateData(rc[client]['data']);
        }
    });
}
</script>
</body>
</html>