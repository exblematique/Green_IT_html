<?php
// Initialize the session
session_start();

require_once "META-INF/config.php"; //Connexion BDD

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: accueil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <title>Welcome</title>
    <script src="lightweight-charts.standalone.production.js"></script>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="style.css?v=1">
    <script>

        function toggleCharts() {
          var x = document.getElementById("1");
          var y = document.getElementById("2");
          if (x.style.display === "none") {
            x.style.display = "block";
            y.style.display = "none";

        } else {
            x.style.display = "none";
            y.style.display = "block";
        }
    }
</script>
</head>
<body>
    <!-- NavBar -->
    <div class="navbar">
        <a href="#" class="active"><i class="fa fa-fw fa-home"></i> Accueil</a> 
        <a href="pdf.php"><i class="fa fa-file-pdf-o"></i> Télécharger vos données</a>
        <a href="reset-password.php"><i class="fa fa-key"></i> Modifier le mot de passe</a>
        <a href="logout.php"><i class="fa fa-fw fa-user"></i> Déconnexion</a>
    </div>

    <!-- Body -->
    <?php echo"<h2>Bonjour, ".$_SESSION["Username"].". Voilà votre mois de Janvier en détail.</h2>";?>
    <div id="content">
        <div class="infoLogement">
            <?php
            echo "<h2>Logement</h2>";
            $result = $link->query("SELECT DISTINCT Surface, Chauffage, `Année de construction`, Ville FROM Logement WHERE Foyer='".$_SESSION["Foyer"]."'");
            while($row = $result->fetch_array())
            {
                echo "Surface : " . $row['Surface'] . "<br />";
                echo "Chauffage : " . $row['Chauffage'] . "<br />";
                echo "Année de construction : " . $row['Année de construction'] . "<br />";
                echo "Ville : " . $row['Ville'] . "<br />";
            }
            echo "<h2>Propriétaire</h2>";
            $result = $link->query("SELECT DISTINCT Nom, Prenom, Société FROM Propriétaire WHERE Foyer='".$_SESSION["Foyer"]."'");
            while($row = $result->fetch_array())
            {
                if($row['Société']){
                    echo "Propriétaire de : " . $row['Société'] . "<br />";
                } else {
                    echo "Nom : " . $row['Nom'] . "<br />";
                    echo "Prénom : " . $row['Prenom'] . "<br />";
                    echo "N'est pas propriétaire d'entreprise.";
                }
            }
            echo "<h2>Locataire</h2>";
            $result = $link->query("SELECT DISTINCT Nom, Prenom FROM Locataire WHERE Foyer='".$_SESSION["Foyer"]."'");
            while($row = $result->fetch_array())
            {
                echo "Nom : " . $row['Nom'] . "<br />";
                echo "Prénom : " . $row['Prenom'] . "<br />";
            }

            $result->free();
            $link->close(); 
            ?>
        </div>
        <div id="charts">
            <script>
            //AreaSeriesChart
            var graph = createAreaSeriesChart([{
                name: <?php echo '"'.$_SESSION["Foyer"].'"'; ?>,
                start: "-1",
                end: "-1"
            }], -1, -1, true);
            function createAreaSeriesChart(clients, start_date, end_date, newClient){
                data = {clients: clients,
                    start: start_date,
                    end: end_date}
                    $.ajax({
                        type: "POST",
                        url: "updateData.php",
                        data: data,
                        dataType: "json"
                    }).done(function(result){
                        var rc = result.clients;
                        for (client in rc) {
                            var div = document.createElement("div");
                            div.id = 1;
                            var chart = LightweightCharts.createChart(div, { width: 800, height: 300 });
                            graph = chart.addAreaSeries({
                                base: 0,
                                localization: {locale: 'fr-FR'}
                            });
                            graph.setData(rc[client].data);
                            document.querySelector("#charts").appendChild(div);

                        }
                        chart.timeScale().fitContent();
                        img = chart.takeScreenshot().toDataURL("image/png")
                        function Image(){
                            $.ajax({
                                type: "POST",
                                url: "image.php",
                                data: {img: img},
                                dataType: "text"
                            });
                        };
                        Image();
                        return graph;
                        
                    });
                    
                }

            //HistogramChart
            var graph = createHistogramChart([{
                name: <?php echo '"'.$_SESSION["Foyer"].'"'; ?>,
                start_info: "-1",
                end_info: "-1"
            }], -1, -1, true);
            function createHistogramChart(clients, start_date, end_date, newClient){
                data = {clients: clients,
                    start: start_date,
                    end: end_date}
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
                                div.id = 2;
                                div.style.display="none";
                                var chart = LightweightCharts.createChart(div, { width: 800, height: 300 });
                                graph = chart.addHistogramSeries({
                                    base: 0,
                                    localization: {locale: 'fr-FR'},
                                    crosshair: {
                                        horzLine: {
                                            visible: false,
                                            labelVisible: false
                                        },
                                        vertLine: {
                                            visible: true,
                                            style: 0,
                                            width: 2,
                                            color: 'rgba(32, 38, 46, 0.1)',
                                            labelVisible: false,
                                        }
                                    },
                                });
                                graph.setData(rc[client].data);
                                document.querySelector("#charts").appendChild(div);
                            }
                            else graph.updateData(rc[client]['data']);

                        }
                        chart.timeScale().fitContent();
                                                
                        return graph;
                    });
                    
                }
                

                
            </script>
        </div>
    </div>
    <div><button class="btn toggle" onclick="toggleCharts()">Changement d'affichage</button></div>
    <div class="footer">
        © Green High Tea, All rights reserved. | <a href="rgpd.php">Mentions Légales</a>
    </div>
</body>
</html>