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
// set the data
/*barSeries.setData([
  { time: "2018-12-19", open: 141.77, high: 170.39, low: 120.25, close: 145.72 },
  { time: "2018-12-20", open: 145.72, high: 147.99, low: 100.11, close: 108.19 },
  { time: "2018-12-21", open: 108.19, high: 118.43, low: 74.22, close: 75.16 },
  { time: "2018-12-22", open: 75.16, high: 82.84, low: 36.16, close: 45.72 },
  { time: "2018-12-23", open: 45.12, high: 53.90, low: 45.12, close: 48.09 },
  { time: "2018-12-24", open: 60.71, high: 60.71, low: 53.39, close: 59.29 },
  { time: "2018-12-25", open: 68.26, high: 68.26, low: 59.04, close: 60.50 },
  { time: "2018-12-26", open: 67.71, high: 105.85, low: 66.67, close: 91.04 },
  { time: "2018-12-27", open: 91.04, high: 121.40, low: 82.70, close: 111.40 },
  { time: "2018-12-28", open: 111.51, high: 142.83, low: 103.34, close: 131.25 },
  { time: "2018-12-29", open: 131.33, high: 151.17, low: 77.68, close: 96.43 },
  { time: "2018-12-30", open: 106.33, high: 110.20, low: 90.39, close: 98.10 },
  { time: "2018-12-31", open: 109.87, high: 114.69, low: 85.66, close: 111.26 },
  ]);*/

//Function will create a new graph in realTime
function createGraph (name, start, end) {
    var div = document.createElement("div");
    var chart = LightweightCharts.createChart(div, { width: 400, height: 300 });
    client = {
        name: name,
        start_info: "",
        end_info: "",
        graph: chart.addBarSeries({
            thinBars: false,
            localization: {locale: 'fr-FR', dateFormat: 'dd/MM/yyyy'},
        })
    }
    receiveInfo([client], "2019-01-01", "2019-01-01");
    document.querySelector("#clients").appendChild(div);
    return client;
}

var clients = [];
clients.push(createGraph("Jean", 1, 3));
clients.push(createGraph("René", 1, 3));
    
//Function will enable to receive more information from database
function receiveInfo(clients, start_date, end_date){
    data = {clients: clients,
            date: start_date,
            end: end_date}
    $.ajax({
        type: "POST",
        url: "updateData.php",
        data: data,
        dataType: "json"
    }).done(function(result){
        for (client in result.clients) {
            clients[client.name].graph.update(clients);
        }
        //if (start_date) data+= 
    });
}
    </script>
</body>
</html>