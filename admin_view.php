<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if($_SESSION["Username"] != "D4G2019"){
    header("location: accueil.php");
    exit;
}
?>

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
            $curDate = date("Y-m-d");
            //Display buttons to select courbes to monitoring
            while($row = $result->fetch_array())
                echo '<div id="'.$row['fd'].'" onclick="displayGraph(\''.$row['fd'].'\')">'.$row['fd'].'</div>';
            $result->free();
            $link->close();
        ?></div>
        <div id="settings">
            <div>
                <div>Data from <input type="date" id="start" value="2019-01-12" onclick="updateDate()" /></div>
                <div> to<input type="date" id="end" value="<?php echo $curDate;?>" onclick="updateDate()" /></div>
            </div>
            <button type="button" onclick="document.location.href='admin_viewaccount.php'">Gérer les utilisateurs</button>
            <button type="button" onclick="document.location.href='logout.php'">Déconnexion</button>
        </div>
    </header>
    <div id="clients"><!-- All graphs of clients --></div>
    <script>
    
graphs = [];
curClient = [];

//Launch to display Graph, used the class hidden to display or not div-buttons
function displayGraph(client){
    console.log(client);
    //Create graph if the graph is not download yet.
    if (!graphs[client]) {curClient[name]=0;createGraph(client);}
    curClient[name]=1-curClient[name];
    document.querySelector("#listClient #"+client).classList.toggle("checked");
    document.querySelector("#clients #"+client).classList.toggle("hidden");
}

function updateDate(){
    clients = Array();
    for (c in curClient){
        if (curClient[c]) clients.push({name:c}); //graphs[c].updateData(graphs[c]['data']);
    }
    receiveInfo(clients, false);
}


//Function will create a new graph
function createGraph (name) {
    client = {name: name}
    curClient[name]=1;
    receiveInfo([client], true);
}

//Function will enable to receive more information from database
function receiveInfo(clients, newClient){
    data = {clients: clients,
            start: document.querySelector("#start").value,
            end: document.querySelector("#end").value}
    $.ajax({
        type: "POST",
        url: "updateData.php",
        data: data,
        dataType: "json"
    }).done(function(result){
        var rc = result.clients;
        for (client in rc) {
            if (newClient) {
                var div = document.createElement("div");
                div.id = rc[client].name;
                var chart = LightweightCharts.createChart(div, {
                    width: 400,
                    height: 300,
                    
                });
                graphs[rc[client].name] = chart.addAreaSeries({
                    base: 0,
                    width: 600,
                    height: 380,
                    autoScale: true,
                    localization: {locale: 'fr-FR'},
                    topColor: 'rgba(38, 198, 218, 0.56)',
                    bottomColor: 'rgba(38, 198, 218, 0.04)',
                    lineColor: 'rgba(38, 198, 218, 1)',
                    lineWidth: 2,
                    crossHairMarkerVisible: false,
                });
                graphs[rc[client].name].setData(rc[client].data);
                /************* Legends *********************/
                var legend = document.createElement('div');
                legend.classList.add('legend');
                div.appendChild(legend);

                var firstRow = document.createElement('div');
                firstRow.innerText = rc[client].name;
                firstRow.style.color = 'black';
                legend.appendChild(firstRow);
                /*********************************************/
                chart.timeScale().fitContent();
                document.querySelector("#clients").appendChild(div);
            }
            else graphs[rc[client].name].update(rc[client]['data']);
        }
    });
}
</script>
</body>
</html>