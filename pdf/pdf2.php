<?php
require('mysql_table.php');

class PDF extends PDF_MySQL_Table
{
function Header()
{
    // Titre
    $this->SetFont('Arial','',18);
    $this->Cell(0,6,'Populations mondiales',0,1,'C');
    $this->Ln(10);
    // Imprime l'en-tête du tableau si nécessaire
    parent::Header();
}
}

// Connexion à la base
$link = mysqli_connect('server','login','password','db');

$pdf = new PDF();
$pdf->AddPage();
// Premier tableau : imprime toutes les colonnes de la requête
$pdf->Table($link,'select * from country order by name');
$pdf->AddPage();
// Second tableau : définit 3 colonnes
$pdf->AddCol('rank',20,'Rang','C');
$pdf->AddCol('name',40,'Pays');
$pdf->AddCol('pop',40,'Pop (2001)','R');
$prop = array('HeaderColor'=>array(255,150,100),
            'color1'=>array(210,245,255),
            'color2'=>array(255,255,210),
            'padding'=>2);
$pdf->Table($link,'select name, format(pop,0) as pop, rank from country order by rank limit 0,10',$prop);
$pdf->Output();
?>