<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
// Chargement des donn�es
function LoadData($file)
{
	// Lecture des lignes du fichier
	$lines = file($file);
	$data = array();
	foreach($lines as $line)
		$data[] = explode(';',trim($line));
	return $data;
}

// Tableau color�
function FancyTable($header, $data)
{
	// Couleurs, �paisseur du trait et police grasse
	$this->SetFillColor(255,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(128,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('','B');
	// En-t�te
	$w = array(40, 35, 45);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
	$this->Ln();
	// Restauration des couleurs et de la police
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('');
	// Donn�es
	$fill = false;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
		$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
		$this->Cell($w[2],6,utf8_decode($row[2]),'LR',0,'L',$fill);
		$this->Ln();
		$fill = !$fill;
	}
	// Trait de terminaison
	$this->Cell(array_sum($w),0,'','T');
}
function Header()
{
	$monfichier = fopen('donnees.txt', 'r+');
 
	$ligne = fgets($monfichier);
	fclose($monfichier);
	$data1 = explode(";", $ligne);
	$tab_ligne = file('donnees.txt');
	$monfichier = fopen('donnees.txt', 'r+'); 
	for($i=0;$i<count($tab_ligne);$i++)
	{
		$ligne2 = fgets($monfichier);
		$data2 = explode(";", $ligne2);
	}
	fclose($monfichier);
    // Logo
    $this->Image('logo.png',10,6,30);
    // Police Arial gras 15
    $this->SetFont('Arial','B',15);
    // Décalage à droite
    $this->Cell(1);
	// Titre
    $this->Cell(145,10,utf8_decode("Liste des données relevées du ".$data1[0]." au ".$data2[0]),1,0,'C');
    // Saut de ligne
    $this->Ln(20);
}
}

$pdf = new PDF();
$pdf->AliasNbPages();
// Titres des colonnes
$header = array('Date', 'Heure', utf8_decode("valeur"));
// Chargement des donn�es
$data = $pdf->LoadData('donnees.txt');
$pdf->SetLeftMargin(45);
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->SetFont('');
$pdf->FancyTable($header,$data);
$pdf->Output();
?>
