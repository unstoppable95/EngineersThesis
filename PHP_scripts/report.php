<?php

require "fpdf/fpdf.php";
require_once "connection.php";

$width1Col = $width3Col = $width4Col = 50;
$width2Col = 30;
$height = 10;

session_start();
$connect = new mysqli($servername, $username, $password, $dbName);
class myPDF extends FPDF {
    
    function headerTable()
    {
        $this->SetFont('Times', 'B', 12);
        $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], 'Imię i Nazwisko ', 1, 0, 'C');
        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], 'Konto klasowe', 1, 0, 'C');
        $this->Cell($GLOBALS['width3Col'], $GLOBALS['height'], 'Konto dziecka gotówka', 1, 0, 'C');
        $this->Cell($GLOBALS['width4Col'], $GLOBALS['height'], 'Konto dziecka konto', 1, 0, 'C');
        $this->Ln();
    }

    function viewTable($connect)
    {
        $this->SetFont('Times', '', 12);
        $result = $connect->query(sprintf("SELECT * FROM child WHERE class_id=(SELECT id FROM class WHERE parent_id='" . $_SESSION['userID'] . "') order by surname desc"));
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $class_account_balanceTMP = $connect->query(sprintf("SELECT IFNULL(SUM(amount),0) AS x FROM class_account_payment WHERE child_id = ".$row["id"] ));
                $class_account_balance = mysqli_fetch_array($class_account_balanceTMP);
                $account_balanceTMP = $connect->query(sprintf("SELECT cash,balance  FROM account WHERE child_id = ".$row["id"] ));
                $account_balance = mysqli_fetch_array($account_balanceTMP);
                /////////////////////
                $current_my_q = $connect->query(sprintf("select month(curdate()) as m , year(curdate()) as y from dual"));
                $current_my = mysqli_fetch_array($current_my_q);
                $current_month = intval($current_my['m']);
                $current_year = intval($current_my['y']);
                if($current_month>=1 and $current_month<= 8)
                {
                    $current_year= $current_year - 1; 
                }
                $month_count = $connect->query(sprintf("SELECT TIMESTAMPDIFF(MONTH,concat(" . $current_year . " ,'-09-01'),CURDATE()) as date FROM DUAL"));
                $months=mysqli_fetch_array($month_count);
                $monthly_fee = $connect->query(sprintf("SELECT monthly_fee AS fee FROM class_account WHERE class_id=(SELECT id FROM class WHERE parent_id='" . $_SESSION['userID'] . "') " ));
                $fee=mysqli_fetch_array($monthly_fee);
                $expected_value = intval($months["date"]) * intval($fee["fee"]); 
			    $child_class_account = intval($class_account_balance["x"]) - $expected_value;
                /////////////////////
                $bla = utf8_decode($row["surname"]);
                $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], $row["name"] . ' ' . $bla, 1, 0, 'C');
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], $child_class_account . ' zł', 1, 0, 'C');
                $this->Cell($GLOBALS['width3Col'], $GLOBALS['height'], $account_balance['cash'], 1, 0, 'C');
                $this->Cell($GLOBALS['width4Col'], $GLOBALS['height'], $account_balance['balance'], 1, 0, 'C');
                $this->Ln();
            }
        }
        else
	    {
            $sumOfWidth = $GLOBALS['width1Col'] + $GLOBALS['width2Col'] + $GLOBALS['width3Col'] + $GLOBALS['width4Col'];
            $this->Cell($sumOfWidth, $GLOBALS['height'], 'Nie dodano jeszcze uczniów do tej klasy', 1, 0, 'C');
        }
    }
}

$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->headerTable();
$pdf->viewTable($connect);
$pdf->Output();

?>