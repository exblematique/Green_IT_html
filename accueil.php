<!DOCTYPE html>
<html lang="fr-FR">
<head>
  <title>Team 39 - Index</title>
  <script src="lightweight-charts.standalone.production.js"></script>
  <style>
    * {box-sizing: border-box;}

    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    .topnav {
      overflow: hidden;
      background-color: #e9e9e9;
    }

    .topnav a {
      float: left;
      display: block;
      color: black;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
      font-size: 17px;
    }

    .topnav a:hover {
      background-color: #ddd;
      color: black;
    }

    .topnav a.accueil {
      background-color: #2196F3;
      color: white;
    }

    .topnav a.inscription {
      background-color: #2196F3;
      float: right;
      color: white;
    }

    .topnav .login-container {
      float: right;
    }

    .topnav input[type=text] {
      padding: 6px;
      margin-top: 8px;
      font-size: 17px;
      border: none;
      width:120px;
    }

    .topnav .login-container button {
      float: right;
      padding: 6px 10px;
      margin-top: 8px;
      margin-right: 16px;
      background-color: #555;
      color: white;
      font-size: 17px;
      border: none;
      cursor: pointer;
    }

    .topnav .login-container button:hover {
      background-color: green;
    }

    @media screen and (max-width: 600px) {
      .topnav .login-container {
        float: none;
      }
      .topnav a, .topnav input[type=text], .topnav .login-container button {
        float: none;
        display: block;
        text-align: left;
        width: 100%;
        margin: 0;
        padding: 14px;
      }
      .topnav input[type=text] {
        border: 1px solid #ccc;  
      }
    }
  </style>
</head>
<body>

  <div class="topnav">
    <a class="accueil" href="index.php">Accueil</a>
    <a class="inscription" href="register.php">Inscription</a>
    <div class="login-container">

      <form action="login.php" method="POST">
        <input type="text" placeholder="Username" name="username">
        <input type="text" placeholder="Password" name="password">
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
  <div>
    <script>
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

 <?php
$servername = "localhost:3306";
$username = "GHT";
$password = "azerty1234";
$dbname = "GHT";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Foyer, Username, Password FROM Account";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["Foyer"]. " - Name: " . $row["Username"]. " " . $row["Password"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?> 
</div>
</body>
</html>
