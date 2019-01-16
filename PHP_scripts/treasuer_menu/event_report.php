<?php

require "../tfpdf/tfpdf.php";
require_once "../connection.php";

$width1Col = 70;
$width2Col = 40;
$height = 10;

session_start();
$conn = new MyDB();
class myPDF extends tFPDF {
    
    function pageWidth()
    {
        $width = $this->w;
        $leftMargin = $this->lMargin;
        $rightMargin = $this->rMargin;
        return $width-$rightMargin-$leftMargin;
    }

    function centerTable($contentWidth)
    {
        $lMargin = ($this->w - $contentWidth) / 2;
        $this->SetLeftMargin($lMargin);
    }

    function stopCenterTable()
    {
        $this->SetLeftMargin(10.00125);
    }

    function Footer()
    {
        $this->stopCenterTable();
        $this->SetY(-15);
        $this->SetFont('Arial','', 8);
        $this->Cell(0, 10, $this->PageNo() . '/{nb}', 0, 0,'C');
    }

    function addTitle($text)
    {
        $this->SetFont('DejaVu', 'B', 14);
        $this->Cell($this->pageWidth(), 10, $text, 0, 0, 'C');
        $this->Ln();
        $this->SetFont('DejaVu', '', 12);
    }

    function headerTable($conn)
    {
        $result = ($conn->query(sprintf("select count(*) as total from participation where event_id ='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $resultAmount = ($conn->query(sprintf("select price, completed, name from event where id ='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $name = $resultAmount["name"];
        $this->addTitle($name);
        $this->Cell($this->pageWidth(), 10, "Liczba uczestników zbiórki: " . $result["total"], 0, 0, 'C');
        $this->Ln();

        $totalAmount = $resultAmount["price"] * $result["total"];
        $resultAmountPaid = ($conn->query(sprintf("select sum(amount_paid) as totalPaid from participation where event_id='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $totalAmountPaid = $resultAmountPaid["totalPaid"];

        $cash = ($conn->query(sprintf("select sum(cash) as totalCash from participation where event_id='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $account = ($conn->query(sprintf("select sum(balance) totalAccount from participation where event_id='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $this->Cell($this->pageWidth(), 10, "Całkowity koszt zbiórki: " . number_format($totalAmount, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->pageWidth(), 10, "Suma wpłat uczestników: " . number_format($totalAmountPaid, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->pageWidth(), 10, "W tym na koncie: " . number_format($cash['totalCash'], 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->pageWidth(), 10, "W tym gotówka: " . number_format($account['totalAccount'], 2, ".", "")  . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Ln();
        $this->centerTable($GLOBALS['width1Col'] + $GLOBALS['width2Col']);
        $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], 'Imię i nazwisko', 1, 0, 'C');
        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], 'Kwota wpłacona', 1, 0, 'C');
        $this->stopCenterTable();
        $this->Ln();
    }

    function viewTable($conn)
    {
        $resultAmount = ($conn->query(sprintf("select price,completed from event where id ='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
        $result = $conn->query(sprintf("select ch.id as childID, ch.name as name , ch.surname as surname, p.amount_paid as amount_paid , (p.amount_paid+'" . $resultAmount["price"] . "') as idx from child ch, participation p where ch.id = p.child_id and p.event_id='" . $_SESSION['selectedID']. "' order by surname,idx asc"));

        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $this->centerTable($GLOBALS['width1Col'] + $GLOBALS['width2Col']);
                $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], $row["name"] . ' ' . $row["surname"], 1, 0, 'C');
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], number_format($row["amount_paid"], 2, ".", "") . ' zł', 1, 0, 'C');
                $this->stopCenterTable();
                $this->Ln();
            }
        }
        else
	    {
            $sumOfWidth = $GLOBALS['width1Col'] + $GLOBALS['width2Col'];
            $this->centerTable($sumOfWidth);
            $this->Cell($sumOfWidth, $GLOBALS['height'], 'Nie dodano jeszcze zbiórek do tej klasy', 1, 0, 'C');
            $this->stopCenterTable();
            $this->Ln();
        }
    }
}

$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
$pdf->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);

$pdf->headerTable($conn);
$pdf->viewTable($conn);
$pdf->Output();

?>