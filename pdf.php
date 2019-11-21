<?php
$img = $_POST['img'];
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $file = uniqid() . '.png';
                 
require('pdf/fpdf.php');
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: accueil.php");
    exit;
}

class PDF extends FPDF
{

// En-tête
function Header()
{	//Couleur
	$titre = 'Rapport de consommation';
	Global $user;
	Global $adress;
    // Arial gras 15
    $this->SetFont('Courier','B',15);
    // Calcul de la largeur du titre et positionnement
    $w = $this->GetStringWidth($titre)+6;

    $this->SetX((210-$w)/2);
    // Couleurs du cadre, du fond et du texte
    $this->SetDrawColor(0,0,0);
    $this->SetFillColor(200,200,200);
    $this->SetTextColor(10,10,10);
    // Epaisseur du cadre (1 mm)
    $this->SetLineWidth(0.5);
    // Titre
    $this->Cell($w,10,$titre,1,0,'C',true);

    // Logo
    $this->Image('logo.png',10,6,35);
    
}
// Info user 
function info()
{	
	Global $user;
	Global $adress;
	$this->SetFont('Courier','',10);
	$x = $this->GetStringWidth($user)+6;
	$this->Cell(20,5,' ',0,2);
	$this->Cell($x,5,'Client :',0,2,'R');
	$this->Cell($x,5,utf8_decode($user),0,2,'R');
	$this->Cell($x,5,utf8_decode($adress),0,1,'R');
	$this->ln(10);
}
// légende 
function legende($R,$V,$B,$message)
{
	$this->SetFont('Courier','',12);
	$this->SetFillColor($R,$V,$B);
	$this->Cell(10,10,"  ",1,0,'L',true);
	$this->Cell(40,10,$message,0,1,false);
}

function Titre($message)
{
    $this->SetFont('Courier','U',20);
    // Couleur de fond
    //$this->SetFillColor(200,220,255);
    // Titre
    $this->Cell(0,6,$message,0,1,'L');
    // Saut de ligne
    $this->Ln(5);
	$this->SetFont('Courier','',11);
}
// Pied de page
function Footer()
{
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
// Début
$pdf = new PDF();
$pdf->AddPage();
//Valeurs
$kWh = 0.145;
$width_date = 30 ;
$width_Wh = 20;
$width_prix = 20;
//Recuperation base de données 
$db = new mysqli('localhost:3306','GHT','azerty1234','GHT');
$result = $db->query('SELECT SUM(Value) FROM Data WHERE Foyer="'.$_SESSION["Foyer"].'"');
while($row = $result->fetch_array())
{
	$somme = $row['SUM(Value)'];
	
}
$result = $db->query('SELECT * FROM Locataire WHERE Foyer="'.$_SESSION["Foyer"].'"');
while($row = $result->fetch_array())
{
	$user = ''.$row['Nom'].' '.$row['Prenom'];
}
$result = $db->query('SELECT Num_voie,Voie FROM Logement WHERE Foyer="'.$_SESSION["Foyer"].'" ');
while($row = $result->fetch_array())
{
	$adress = ''.$row['Num_voie'].' '.$row['Voie'];
}
$result = $db->query('SELECT AVG(Value) FROM Data WHERE Foyer="'.$_SESSION["Foyer"].'"');
while($row = $result->fetch_array())
{
	$moyenne = $row['AVG(Value)'];
}
//En tête
$pdf->info();
$pdf->Titre("Consommation du mois");
$pdf->Cell(10,5,"Prix du kWh : ".$kWh,0,1);

//Créattion du Tableau 
$pdf->Cell($width_date,6,"Date",1,0,"C");
$pdf->Cell($width_Wh,6,"Wh",1,0,"C");
$pdf->Cell($width_prix,6,"Prix",1,1,"C"); 
$result = $db->query('SELECT Date,Value FROM Data WHERE Foyer="'.$_SESSION["Foyer"].'"');
while($row = $result->fetch_array())
{
	
	If($row['Value'] <= $moyenne)
		$Color = [
				0 => 50,
				1 => 205,
				2=> 50,];
	if($row['Value'] >= $moyenne)
		$Color = [
				0 => 238,
				1 => 34,
				2 => 0,];
    $pdf->SetFillColor($Color[0],$Color[1],$Color[2]);
	$pdf->Cell($width_date,5,$row['Date'],1,0,"C");
	$pdf->Cell($width_Wh,5,$row['Value'],1,0,"C",true);
	$pdf->Cell($width_prix,5,round($row['Value']*$kWh/1000,2) ." " .chr(128),1,1,"C");
	
}
$db->close();
// Fin Tableau 

$pdf->AliasNbPages();
$pdf->Image('pdf/romain.jpg',90,60,95);
$pdf->Image('pdf/romain.jpg',90,140,95);
$pdf->ln(5);
$pdf->legende(238,34,0,"Consommation forte");
$pdf->legende(50,205,50,"Consommation faible");
$pdf->Cell(0,10,'Consommation moyenne du mois : '.round($moyenne,2).' Wh',0,1);
$pdf->Cell(0,10,'Solde totale :'.round($somme*$kWh/1000,2) .' '.chr(128),0,1);




$pdf->Output();
?>