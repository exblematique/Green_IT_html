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
            $foyer = array();    //Contain settings for all graphs
            //Display buttons to select courbes to monitoring
            while($row = $result->fetch_array()){
                array_push($foyer,$row['fd']);
                echo '<div id="'.$row['fd'].'" onclick="displayGraph(\''.$row['fd'].'\')">
                <input type="checkbox" name="clients" value="'.$row['fd'].'" />'.$row['fd'].'</div>';
            }
            $result->free();
            $link->close();
        ?></div>
        <div id="settings">
            <div>
            <ul><li class="deroulant"><a id="selFoyer">Select Foyer</a>
                <ul class="sous">
                    <?php foreach ($foyer as $key){
                        echo '<li><a onclick="changeSettings(\''.$key.'\')">'.$key.'</a></li>';
                    }?>
                </ul>
            </ul>
            </div>
            <div>
                <div>Data from <input type="date" id="start" value="2019-01-01" onChange="updateInput('start')" /></div>
                <div> to<input type="date" id="end" value="<?php echo $curDate;?>" onChange="updateInput('end')"/></div>
            </div>
        </div>
    </header>
    <div id="clients"><!-- All graphs of clients --></div>
    <script>
curKey = "";
clients = [];
graphs = [];
settings = {
<?php $out="";
    foreach ($foyer as $key) echo $key.':{start:"2019-01-01", end:"'.$curDate.'"},';
?>
};

function updateInput(setting){
    settings[curKey][setting] = document.querySelector("#"+setting).value;
}

function changeSettings(client){
    curKey = client;
    document.querySelector("#selFoyer").innerHTML = client;
    document.querySelector("#start").value = settings[client]['start'];
    document.querySelector("#end").value = settings[client]['end'];
}

function displayGraph(client){
    var div = document.querySelector("#clients #"+client+" div");
    if (graphs[client] == undefined) clients[client] = createGraph(client, -1, -1);
    else if (document.querySelector("#listClient #"+client+" input").checked) {
        div.className = "";
        receiveInfo([client], -1, -1);  
    } else div.className = "hidden";
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
                    width: 600,
                    height: 380,
                    text: "Graph of "+rc[client].name,
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