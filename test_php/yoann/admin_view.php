<!DOCTYPE html>
<html lang="fr-FR">
<head>
  <title>Team 39 - Admin view</title>
  <link rel="stylesheet" type="text/css" href="admin_view.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="lightweight-charts.standalone.production.js"></script>
</head>
<body>
    <header>
        <div id="listClient"><?php
            include('META-INF/config.php');
            //Return the list of foyer available in database
            $result = $link->query('SELECT DISTINCT(Foyer) AS fd FROM Data;');
            //Display buttons to select courbes to monitoring
            while($row = $result->fetch_array()){
                echo '<div id="'.$row['fd'].'" onclick="displayGraph(\''.$row['fd'].'\')">
                <a id="'.$row['fd'].'" value="false">'.$row['fd'].'</a></div>';
            }
            $result->free();
            $link->close();
        ?></div>
    </header>
    <div id="clients"></div>
    <!-- All graphs of clients -->
    <script>
clients = [];
graphs = [];

function displayGraph(client){
    var divClient = document.querySelector("#clients #"+client);
    var divGraph = document.querySelector("#listClient #"+client);
    divClient.className ? divClient.className = "" : divClient.className = "checked";
    
    if (graphs[client] == undefined) clients[client] = createGraph(client, -1, -1);
    else if (divClient.value) {
        divGraph.className = "";
        receiveInfo([client], -1, -1);
            
    } else divGraph.className = "hidden";
}

//Function will create a new graph in realTime
function createGraph (name, start, end) {
    client = {
        name: name,
        start_info: "",
        end_info: ""
    }
    return receiveInfo([client], "2019-01-01", "2019-01-01", true);
}
    
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
        return client;
    });
}
</script>
</body>
</html>