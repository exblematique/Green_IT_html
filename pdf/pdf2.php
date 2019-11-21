<?php
require('mysql_table.php');
require('makefont/makefont.php');


class PDF extends PDF_MySQL_Table
{
// En-tête
function Header()
{	//Couleur
	$titre = 'Rapport de consomation';
	Global $user;
	Global $adress;
    // Arial gras 15
    $this->SetFont('Courier','B',15);
    // Calcul de la largeur du titre et positionnement
    $w = $this->GetStringWidth($titre)+6;

    $this->SetX((210-$w)/2);
    // Couleurs du cadre, du fond et du texte
    $this->SetDrawColor(0,0,0);
    $this->SetFillColor(169,169,169);
    $this->SetTextColor(10,10,10);
    // Epaisseur du cadre (1 mm)
    $this->SetLineWidth(1);
    // Titre
    $this->Cell($w,10,$titre,1,0,'C',true);

    // Logo
    $this->Image('../logo.png',10,6,35);
    
}
// Info user 
function info()
{	
	Global $user;
	Global $adress;
	$this->SetFont('Courier','',10);
	$x = $this->GetStringWidth($user)+6;
	$this->Cell($x,5,'Client :',0,2);
	$this->Cell($x,5,$user,0,2);
	$this->Cell($x,5,$adress,0,1);
	$this->ln(22);
}
// légende 
function legende($R,$V,$B,$message)
{
	$this->SetFont('Courier','',12);
	$this->SetFillColor($R,$V,$B);
	$this->Cell(10,10,"  ",1,0,'L',true);
	$this->Cell(40,10,$message,0,1,false);
}
function Ligne($message,$unit)
{
	$w = $this->GetStringWidth($message)+6;
	$this->Cell($w,10,$message,0,0,'C');
	$this->Cell(5,10,$unit,0,1);
}
function Titre($message)
{
    $this->SetFont('Courier','U',20);
    // Couleur de fond
    //$this->SetFillColor(200,220,255);
    // Titre
    $this->Cell(0,6,$message,0,1,'L');
    // Saut de ligne
    $this->Ln(4);
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
//Recuperation base de données 
$db = new mysqli('localhost:3306','GHT','azerty1234','GHT');
$result = $db->query('SELECT SUM(Value) FROM Data WHERE Foyer="A"');
while($row = $result->fetch_array())
{
	$somme = $row['SUM(Value)'];
	
}
$result = $db->query('SELECT * FROM Locataire WHERE Foyer="A"');
while($row = $result->fetch_array())
{
	$user = ''.$row['Nom'].' '.$row['Prenom'];
}
$result = $db->query('SELECT Num_voie,Voie FROM Logement WHERE Foyer="A"');
while($row = $result->fetch_array())
{
	$adress = ''.$row['Num_voie'].' '.$row['Voie'];
}

$db->close();
// Connexion à la base
$link = mysqli_connect('localhost:3306','GHT','azerty1234','GHT');
$pdf = new PDF();
$pdf->AddPage();
$pdf->info();
$pdf->Titre("Consommation du mois");

// Second tableau : définit 3 colonnes
$pdf->AddCol('Date',30,'Date','C');
$pdf->AddCol('Value',20,'Wh');
$prop = array('align'=>'L',
			'HeaderColor'=>array(255,255,255),
            'color1'=>array(116,209,76),
            'color2'=>array(76,43,0),
		      'padding'=>2);
$pdf->Table($link,'select Date,Value from Data where Foyer="A" ',$prop);
$pdf->AliasNbPages();
$pdf->Image('romain.jpg',90,60,95);
$pdf->Image('romain.jpg',90,140,95);
$pdf->legende(238,34,0,"Consommation forte");
$pdf->legende(50,205,50,"Consommation faible");
$moyenne = $pdf->Moyenne();
$pdf->Ligne('Consommation moyenne du mois : '.$moyenne,'Wh');
//$pdf->Cell(120,10,'Consommation moyenne du mois : '.$moyenne,0,0);
//$pdf->Cell(10,10,'Wh',0,1);


$pdf->Ligne('Solde total: '.$somme*0.000135,"Euros");

$pdf->Output();
?>