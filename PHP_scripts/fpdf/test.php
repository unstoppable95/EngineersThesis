<?php
require "fpdf.php";

class myPDF extends FPDF {
	
}

$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('L', 'A4', 0);
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(20, 10, 'Potem to dokoncz!',1,0,'C');
$pdf->Cell(40, 30, 'Obczajka tabeli',1,0,'C');
$pdf->Output();