<?php
require('mysql_table.php');

class PDF extends PDF_MySQL_Table
{
// En-tête
function Header()
{	//Couleur
	$titre = 'Rapport de consomation';

    // Arial gras 15
    $this->SetFont('Arial','B',15);
    // Calcul de la largeur du titre et positionnement
    $w = $this->GetStringWidth($titre)+6;
    $this->SetX((210-$w)/2);
    // Couleurs du cadre, du fond et du texte
    $this->SetDrawColor(0,80,180);
    $this->SetFillColor(230,230,0);
    $this->SetTextColor(220,50,50);
    // Epaisseur du cadre (1 mm)
    $this->SetLineWidth(1);
    // Titre
    $this->Cell($w,9,$titre,1,1,'C',true);
    // Saut de ligne
    $this->Ln(30);
    // Logo
    $this->Image('../logo.png',10,6,30);
    
}
// légende 
function legende($R,$V,$B,$message)
{
	$this->SetFont('Arial','',12);
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
    $this->SetFont('Arial','',20);
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

// Connexion à la base
$link = mysqli_connect('localhost:3306','GHT','azerty1234','GHT');
$pdf = new PDF();
$pdf->AddPage();
$pdf->Titre("Relevés du mois");

// Second tableau : définit 3 colonnes
$pdf->AddCol('Date',30,'Date','C');
$pdf->AddCol('Value',20,'Wh');
$prop = array('align'=>'L',
			'HeaderColor'=>array(255,150,100),
            'color1'=>array(116,209,76),
            'color2'=>array(76,43,0),
		      'padding'=>2);
$pdf->Table($link,'select Date,Value from Data where Foyer="A" ',$prop);
$pdf->AliasNbPages();
$pdf->Image('romain.jpg',90,55,95);
$pdf->Image('romain.jpg',90,135,95);
$pdf->legende(255,0,0,"Consomation forte");
$pdf->legende(0,128,0,"Consomation faible");
$moyenne = $pdf->Moyenne();
$pdf->Ligne('Consommation moyenne du mois : '.$moyenne,'Wh');
//$pdf->Cell(120,10,'Consommation moyenne du mois : '.$moyenne,0,0);
//$pdf->Cell(10,10,'Wh',0,1);
$db = new mysqli('localhost:3306','GHT','azerty1234','GHT');
$result = $db->query('SELECT SUM(Value) FROM Data WHERE Foyer="A"');
while($row = $result->fetch_array())
{
	$somme = $row['SUM(Value)'];
	
}
$db->close();

$pdf->Ligne('prix paye sur le mois : '.$somme*0.000135,"Euros");

$pdf->Output();
?>