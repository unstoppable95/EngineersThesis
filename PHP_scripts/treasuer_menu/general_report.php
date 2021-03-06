<?php
session_start();
require "../tfpdf/tfpdf.php";
require_once "../connection.php";

$width1Col = 70;
$width2Col = $width3Col = 40;
$height = 10;


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
        $tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
        
        $id = mysqli_fetch_array($tmpID);
        $_SESSION['userID'] = $id["id"]; //userID = treasuerID
        /////////////////////
        $tmpbalance = $conn->query(sprintf("SELECT id, balance,cash FROM class_account WHERE class_id = (SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id = " . $_SESSION['userID'] . " )"));
	    $bal = mysqli_fetch_array($tmpbalance);
	    $balance = $bal["balance"]; //ilość pieniędzy klasowych na koncie
	    $cash = $bal["cash"]; //ilość pieniędzy klasowych w gotówce

        $class_account_id = $bal["id"];
        $class_money =  doubleval($balance) + doubleval($cash);

        $kids_account_balance = $conn->query(sprintf("SELECT SUM(balance) as balance , SUM(cash) as cash FROM account join child on (account.child_id = child.id) where child.class_id = (SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id = " . $_SESSION['userID'] . " )"));
        $kids_account_balance_all = mysqli_fetch_array($kids_account_balance);
        $class_kids_money = doubleval($kids_account_balance_all["balance"]) + doubleval($kids_account_balance_all["cash"]);
        $whole_cash = doubleval($cash) + doubleval($kids_account_balance_all["cash"]);
        $whole_balance = doubleval($balance) + doubleval($kids_account_balance_all["balance"]);
        /////////////////////
        $this->addTitle("Stan kont dzieci");
        $this->Cell($this->pageWidth(), 10, "Suma pieniędzy zebranych na koncie klasowym: " . number_format($class_money, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->pageWidth(), 10, "Suma pieniędzy na kontach dzieci: " . number_format($class_kids_money, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->pageWidth(), 10, "Suma pieniądzy zebranych w gotówce: " . number_format($whole_cash, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->pageWidth(), 10, "Suma pieniądzy zebranych na koncie: " . number_format($whole_balance, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Ln();
        $this->centerTable($GLOBALS['width1Col'] + $GLOBALS['width2Col'] + $GLOBALS['width3Col']);
        $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], 'Imię i nazwisko', 1, 0, 'C');
        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], 'Konto klasowe', 1, 0, 'C');
        $this->Cell($GLOBALS['width3Col'], $GLOBALS['height'], 'Konto dziecka', 1, 0, 'C');
        $this->stopCenterTable();
        $this->Ln();
    }

    function viewTable($conn)
    {
        $result = $conn->query(sprintf("SELECT * FROM child WHERE class_id=(SELECT id FROM class WHERE school_year_id=" . $_SESSION["school_year_id"] . " AND parent_id='" . $_SESSION['userID'] . "') order by surname, name"));
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $class_account_balanceTMP = $conn->query(sprintf("SELECT IFNULL(SUM(amount),0) AS x FROM class_account_payment WHERE school_year_id=" . $_SESSION["school_year_id"] . " AND child_id = " . $row["id"] ));
                $class_account_balance = mysqli_fetch_array($class_account_balanceTMP);
                $account_balanceTMP = $conn->query(sprintf("SELECT cash,balance  FROM account WHERE child_id = ".$row["id"] ));
                $account_balance = mysqli_fetch_array($account_balanceTMP);
                /////////////////////
                $current_my_q = $conn->query(sprintf("select month(curdate()) as m , year(curdate()) as y from dual"));
                $current_my = mysqli_fetch_array($current_my_q);
                $current_month = intval($current_my['m']);
                $current_year = intval($current_my['y']);
                if($current_month>=1 and $current_month<= 8)
                {
                    $current_year= $current_year - 1; 
                }
                $month_count = $conn->query(sprintf("SELECT TIMESTAMPDIFF(MONTH,concat(" . $current_year . " ,'-09-01'),CURDATE()) as date FROM DUAL"));
                $months = mysqli_fetch_array($month_count);
                $monthly_fee = $conn->query(sprintf("SELECT monthly_fee AS fee FROM class_account WHERE class_id=(SELECT id FROM class WHERE school_year_id=" . $_SESSION["school_year_id"] . " AND parent_id='" . $_SESSION['userID'] . "') " ));
                $fee = mysqli_fetch_array($monthly_fee);
                $kid_cash_whole = doubleval($account_balance["cash"]) + doubleval($account_balance["balance"]);
          
                $child_class_account = '';
                $expected_value = intval($months["date"]) * doubleval($fee["fee"]); 
                $child_class_account = doubleval($class_account_balance["x"]) - $expected_value;
                /////////////////////
                $this->centerTable($GLOBALS['width1Col'] + $GLOBALS['width2Col'] + $GLOBALS['width3Col']);
                $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], $row["name"] . ' ' . $row["surname"], 1, 0, 'C');
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], number_format($child_class_account, 2, ".", "") . ' zł', 1, 0, 'C');
                $this->Cell($GLOBALS['width3Col'], $GLOBALS['height'], number_format($kid_cash_whole, 2, ".", "")  . ' zł', 1, 0, 'C');
                $this->stopCenterTable();
                $this->Ln();
            }
        }
        else
	    {
            $sumOfWidth = $GLOBALS['width1Col'] + $GLOBALS['width2Col'] + $GLOBALS['width3Col'];
            $this->centerTable($sumOfWidth);
            $this->Cell($sumOfWidth, $GLOBALS['height'], 'Nie dodano jeszcze uczniów do tej klasy', 1, 0, 'C');
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