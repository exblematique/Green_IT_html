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
<html lang="en">
<head>
    <script src="lightweight-charts.standalone.production.js"></script>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <!-- NavBar -->
    <div class="topnav">
        <a class="accueil" href="welcome.php">Accueil</a>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </div>
    <!-- Body -->
    <div class="page-header">
        <h1>Bienvenue, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> sur votre interface de gestion.</h1>
    </div>
    <div class="charts">
        <script>
            const chart = LightweightCharts.createChart(document.body, { width: 500, height: 300 });
            const histogramSeries = chart.addHistogramSeries({
                base: 0,
            });

// set the data
histogramSeries.setData([
    { time: "2018-12-20", value: 20.31 },
    { time: "2018-12-21", value: 30.27 },
    { time: "2018-12-22", value: 70.28 },
    { time: "2018-12-23", value: 49.29 },
    { time: "2018-12-24", value: 40.64 },
    { time: "2018-12-25", value: 57.46 },
    { time: "2018-12-26", value: 50.55 },
    { time: "2018-12-27", value: 34.85 },
    { time: "2018-12-28", value: 56.68 },
    { time: "2018-12-29", value: 51.60 },
    { time: "2018-12-30", value: 75.33 },
    { time: "2018-12-31", value: 54.85 }
    ]);

        const chart2 = LightweightCharts.createChart(document.body, { width: 500, height: 300 });
        const areaSeries = chart2.addAreaSeries();

// set the data
areaSeries.setData([
    { time: "2018-12-22", value: 32.51 },
    { time: "2018-12-23", value: 31.11 },
    { time: "2018-12-24", value: 27.02 },
    { time: "2018-12-25", value: 27.32 },
    { time: "2018-12-26", value: 25.17 },
    { time: "2018-12-27", value: 28.89 },
    { time: "2018-12-28", value: 25.46 },
    { time: "2018-12-29", value: 23.92 },
    { time: "2018-12-30", value: 22.68 },
    { time: "2018-12-31", value: 22.67 },
    ]);
</script>
</div>
</body>
</html>