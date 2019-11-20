<?php
// Initialize the session
session_start();

require_once "META-INF/config.php"; //Connexion BDD

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: accueil.php");
    exit;
}

$sql = "SELECT * FROM Data WHERE Foyer='A'";
/*
if($stmt = mysqli_prepare($link, $sql)){
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt); // Store result
        if(mysqli_stmt_num_rows($stmt) > 0){
            for($i=0; i<mysqli_stmt_num_rows($stmt);i++){

            }
        }
    }  
}*/
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
    <div class="ChartBar">
        <script>
            <?php echo("console.log('test');"); ?>

            const chart = LightweightCharts.createChart(document.body, { width: 400, height: 300 });
            const barSeries = chart.addBarSeries({
                thinBars: false,
                localization: {
                    locale: 'fr-FR',
                    dateFormat: 'dd/MM/yyyy',
                },
            });

// set the data
barSeries.setData([
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
    ]);

</script>
</div>
<div class="CharGraph">
    <script>
        const chart2 = LightweightCharts.createChart(document.body, { width: 400, height: 300 });
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