<?php
require('fpdf.php');

class PDF extends FPDF
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

// Instanciation de la classe dérivée
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$En_cours = 200;
$En_previous = 300;
$pdf->Cell(0,10,'Consommation du mois en cours : '.$En_cours,0,1);
$pdf->Cell(0,10,'Consommation du dernier mois : '.$En_previous,0,1);
$pdf->Cell(0,10,'facture du dernier mois : '.$En_previous*0.135,0,1);
$pdf->Ln(20);
$pdf->Image('romain.jpg',10,80,200);


$pdf->Output();
?>