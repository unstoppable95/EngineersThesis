<?php

require "../tfpdf/tfpdf.php";
require_once "../connection.php";

$width1Col = 70;
$width2Col = 40;
$height = 10;

session_start();
$conn = new MyDB();
class myPDF extends tFPDF {
    
    function headerTable($conn)
    {
        $result = ($conn->query(sprintf("select count(*) as total from participation where event_id ='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $resultAmount = ($conn->query(sprintf("select price, completed, name from event where id ='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $name = $resultAmount["name"];
        $this->Cell(100, 10, $name, 0, 0, 'C');
        $this->Ln();
        $this->SetFont('DejaVu', '', 12); 
        $this->Cell(100, 10, "Liczba uczestników zbiórki: " . $result["total"], 0, 0, 'C');
        $this->Ln();
        
        $totalAmount = $resultAmount["price"] * $result["total"];
        $resultAmountPaid = ($conn->query(sprintf("select sum(amount_paid) as totalPaid from participation where event_id='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $totalAmountPaid = $resultAmountPaid["totalPaid"];

        $cash = ($conn->query(sprintf("select sum(cash) as totalCash from participation where event_id='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $account = ($conn->query(sprintf("select sum(balance) totalAccount from participation where event_id='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $this->Cell(100, 10, "Całkowity koszt zbiórki: " . number_format($totalAmount, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell(100, 10, "Suma wpłat uczestników: " . $totalAmountPaid . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell(100, 10, "W tym na koncie: " . $cash['totalCash'] . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell(100, 10, "W tym gotówka: " . $account['totalAccount'] . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Ln();
        $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], 'Imię i nazwisko', 1, 0, 'C');
        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], 'Kwota wpłacona', 1, 0, 'C');
        $this->Ln();
    }

    function viewTable($conn)
    {
        $resultAmount = ($conn->query(sprintf("select price,completed from event where id ='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $result = $conn->query(sprintf("select ch.id as childID, ch.name as name , ch.surname as surname, p.amount_paid as amount_paid , (p.amount_paid+'" . $resultAmount["price"] . "') as idx from child ch, participation p where ch.id = p.child_id and p.event_id='" . $_SESSION['selectedID']. "' order by surname,idx asc"));
        //$result = $conn->query(sprintf("SELECT * FROM child WHERE class_id=(SELECT id FROM class WHERE parent_id='" . $_SESSION['userID'] . "') order by surname, name"));
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                /////////////////////
                $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], $row["name"] . ' ' . $row["surname"], 1, 0, 'C');
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], number_format($row["amount_paid"], 2, ".", "") . ' zł', 1, 0, 'C');
                $this->Ln();
            }
        }
        else
	    {
            $sumOfWidth = $GLOBALS['width1Col'] + $GLOBALS['width2Col'];
            $this->Cell($sumOfWidth, $GLOBALS['height'], 'Nie dodano jeszcze zbiórek do tej klasy', 1, 0, 'C');
        }
    }
}

$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
$pdf->SetFont('DejaVu', '', 15); 
$pdf->headerTable($conn);
$pdf->viewTable($conn);
$pdf->Output();

?>