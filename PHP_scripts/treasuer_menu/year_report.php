<?php
session_start();
require "../tfpdf/tfpdf.php";
require_once "../connection.php";

$width1Col = 70;
$width2Col = $width3Col = 40;
$height = 10;

$yearID = $_POST['school_year_id'];

$conn = new MyDB();
class myPDF extends tFPDF {

    // 1. Lista dzieci w klasie z saldami obecnymi
    // 2. Wydarzenia pokolei - lista uczniow, wplaty kazdego
    // 3. Wydatki klasowe
    // 4. Transfery klasy

    function pageWidth()
    {
        $width = $this->w;
        $leftMargin = $this->lMargin;
        $rightMargin = $this->rMargin;
        return $width-$rightMargin-$leftMargin;
    }

    function horizontalLine()
    {
        $this->Ln();
        $this -> Line(0, $this->GetY(), $this -> w, $this->GetY());
        $this->Ln();
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
        $this->Cell($this->pageWidth(), 20, $text, 0, 0, 'C');
        $this->Ln();
        $this->SetFont('DejaVu', '', 12);
    }

    function mainHeader($conn)
    {
        $schoolYears = $conn->query(sprintf("SELECT * FROM school_year WHERE id = '" . $GLOBALS['yearID'] . "'"));
        $year = mysqli_fetch_array($schoolYears);

        $this->addTitle("Raport roczny " . $year['start_year'] . "/" . $year['end_year']);

        $result = $conn->query(sprintf("SELECT * FROM class WHERE parent_id=" . $_SESSION['userID'] . " AND school_year_id=" . $GLOBALS['yearID']));
        $class = mysqli_fetch_array($result);
        $this->Cell($this->pageWidth(), 10, "Klasa " . $class['name'], 0, 0, 'C');
        $this->Ln();
        $parts = array(0 => 2, 2 => 4, 6 => 4, 10 => 4, 14 => 4, 18 => 4, 22 => 4);
	    $newNumber = '';
	    foreach ($parts as $key => $val)
        {
            $newNumber .= substr($class["bank_account_number"], $key, $val) . ' ';
        }
        $this->Cell($this->pageWidth(), 10, "Numery konta bankowego: " . trim($newNumber), 0, 0, 'C');
        $this->Ln();
    }

    function generalReportHeaderTable($conn)
    {
        $this->addTitle("1. Stan kont dzieci");
        $tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
        
        $id = mysqli_fetch_array($tmpID);
        $_SESSION['userID'] = $id["id"]; //userID = treasuerID
        /////////////////////
        $tmpbalance = $conn->query(sprintf("SELECT id, balance,cash FROM class_account WHERE class_id = (SELECT id FROM class WHERE parent_id = " . $_SESSION['userID'] . " AND school_year_id=" . $GLOBALS['yearID'] . " )"));
        $bal = mysqli_fetch_array($tmpbalance);
        $balance = $bal["balance"]; //ilość pieniędzy klasowych na koncie
        $cash = $bal["cash"]; //ilość pieniędzy klasowych w gotówce

        $class_account_id = $bal["id"];
        $class_money =  doubleval($balance) + doubleval($cash);

        $kids_account_balance = $conn->query(sprintf("SELECT SUM(balance) as balance , SUM(cash) as cash FROM account join child on (account.child_id = child.id) where child.class_id = (SELECT id FROM class WHERE parent_id = " . $_SESSION['userID'] . " AND school_year_id=" . $GLOBALS['yearID'] . " )"));
        $kids_account_balance_all = mysqli_fetch_array($kids_account_balance);
        $class_kids_money = doubleval($kids_account_balance_all["balance"]) + doubleval($kids_account_balance_all["cash"]);
        $whole_cash = doubleval($cash) + doubleval($kids_account_balance_all["cash"]);
        $whole_balance = doubleval($balance) + doubleval($kids_account_balance_all["balance"]);
        /////////////////////
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

    function generalReportViewTable($conn)
    {
        $result = $conn->query(sprintf("SELECT * FROM child WHERE class_id=(SELECT id FROM class WHERE parent_id=" . $_SESSION['userID'] . " AND school_year_id=" . $GLOBALS['yearID'] . ") order by surname, name"));
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $class_account_balanceTMP = $conn->query(sprintf("SELECT IFNULL(SUM(amount),0) AS x FROM class_account_payment WHERE child_id = ".$row["id"] ));
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
                $monthly_fee = $conn->query(sprintf("SELECT monthly_fee AS fee FROM class_account WHERE class_id=(SELECT id FROM class WHERE parent_id=" . $_SESSION['userID'] . " AND school_year_id=" . $GLOBALS['yearID'] . ") " ));
                $fee = mysqli_fetch_array($monthly_fee);
                $expected_value = intval($months["date"]) * intval($fee["fee"]); 
                $child_class_account = intval($class_account_balance["x"]) - $expected_value;
                $kid_cash_whole = doubleval($account_balance["cash"]) + doubleval($account_balance["balance"]);
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

    function eventReportTable($conn)
    {
        $this->addTitle("2. Wydarzenia");

        $events = $conn->query(sprintf("select * from event where class_id=(select id from class where school_year_id=" . $GLOBALS['yearID'] . " AND parent_id='" . $_SESSION['userID'] . "') order by date desc"));
        if (mysqli_num_rows($events) > 0)
	    {
		    while ($row = mysqli_fetch_array($events))
		    {
                $eventID = $row['id'];
                $eventName = $row['name'];

                $result = ($conn->query(sprintf("select count(*) as total from participation where event_id ='" . $eventID . "' ")))->fetch_assoc();
                $resultAmount = ($conn->query(sprintf("select price, completed, name from event where id ='" . $eventID . "' ")))->fetch_assoc();

                $this->Cell($this->pageWidth(), 10, $eventName, 0, 0, 'C');
                $this->Ln();
                $this->SetFont('DejaVu', '', 12);
                $this->Cell($this->pageWidth(), 10, "Liczba uczestników zbiórki: " . $result["total"], 0, 0, 'C');
                $this->Ln();

                $totalAmount = $resultAmount["price"] * $result["total"];
                $resultAmountPaid = ($conn->query(sprintf("select sum(amount_paid) as totalPaid from participation where event_id='" . $eventID . "' ")))->fetch_assoc();
                $totalAmountPaid = $resultAmountPaid["totalPaid"];

                $cash = ($conn->query(sprintf("select sum(cash) as totalCash from participation where event_id='" . $eventID . "' ")))->fetch_assoc();
                $account = ($conn->query(sprintf("select sum(balance) totalAccount from participation where event_id='" . $eventID . "' ")))->fetch_assoc();
                $this->Cell($this->pageWidth(), 10, "Całkowity koszt zbiórki: " . number_format($totalAmount, 2, ".", "") . " zł", 0, 0, 'C');
                $this->Ln();
                $this->Cell($this->pageWidth(), 10, "Suma wpłat uczestników: " . number_format($totalAmountPaid, 2, ".", "") . " zł", 0, 0, 'C');
                $this->Ln();
                $this->Cell($this->pageWidth(), 10, "W tym gotówka: " . number_format($cash['totalCash'], 2, ".", "") . " zł", 0, 0, 'C');
                $this->Ln();
                $this->Cell($this->pageWidth(), 10, "W tym na koncie: " . number_format($account['totalAccount'], 2, ".", "")  . " zł", 0, 0, 'C');
                $this->Ln();
                $this->Ln();
                $this->centerTable($GLOBALS['width1Col'] + $GLOBALS['width2Col']);
                $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], 'Imię i nazwisko', 1, 0, 'C');
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], 'Kwota wpłacona', 1, 0, 'C');
                $this->stopCenterTable();
                $this->Ln();

                $resultAmount = ($conn->query(sprintf("select price,completed from event where id ='" . $eventID . "' ")))->fetch_assoc();
                $result = $conn->query(sprintf("select ch.id as childID, ch.name as name , ch.surname as surname, p.amount_paid as amount_paid , (p.amount_paid+'" . $resultAmount["price"] . "') as idx from child ch, participation p where ch.id = p.child_id and p.event_id='" . $eventID . "' order by surname,idx asc"));
                if (mysqli_num_rows($result) > 0)
                {
                    while ($row2 = mysqli_fetch_array($result))
                    {
                        $this->centerTable($GLOBALS['width1Col'] + $GLOBALS['width2Col']);
                        $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], $row2["name"] . ' ' . $row2["surname"], 1, 0, 'C');
                        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], number_format($row2["amount_paid"], 2, ".", "") . ' zł', 1, 0, 'C');
                        $this->stopCenterTable();
                        $this->Ln();
                    }
                }
                $this->horizontalLine();
            }
        }
    }

    function expensesHeaderTable($conn)
    {
        $this->addTitle("3. Wydatki klasowe");
        $tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
        $id = mysqli_fetch_array($tmpID);
        $_SESSION['userID'] = $id["id"]; //userID = treasuerID

        $tmpbalance = $conn->query(sprintf("SELECT id, balance,cash,monthly_fee FROM class_account WHERE class_id = (SELECT id FROM class WHERE school_year_id=" . $GLOBALS['yearID'] . " and parent_id = " . $_SESSION['userID'] . " )"));
        $bal = mysqli_fetch_array($tmpbalance);
        $balance = $bal["balance"]; //ilość pieniędzy klasowych na koncie
        $cash = $bal["cash"]; //ilość pieniędzy klasowych w gotówce
        $monthly_fee = $bal["monthly_fee"];
        $class_account_id = $bal["id"];
        $class_money =  doubleval($balance) + doubleval($cash);

        $kids_account_balance = $conn->query(sprintf("SELECT SUM(balance) as balance , SUM(cash) as cash FROM account join child on (account.child_id = child.id) where child.class_id = (SELECT id FROM class WHERE school_year_id=" . $GLOBALS['yearID'] . " and parent_id = " . $_SESSION['userID'] . " )"));
        $kids_account_balance_all = mysqli_fetch_array($kids_account_balance);
        $class_kids_money = doubleval($kids_account_balance_all["balance"]) + doubleval($kids_account_balance_all["cash"]);
        $this->Cell($this->pageWidth(), $GLOBALS['height'], "Ilość pieniędzy zebranych na koncie klasowym: " . number_format($class_money, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->pageWidth(), $GLOBALS['height'], "W tym gotówka: " . number_format($cash, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->pageWidth(), $GLOBALS['height'], "W tym na koncie: " . number_format($balance, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Cell($this->pageWidth(), $GLOBALS['height'], "Wartość miesięcznej składki: " . number_format($monthly_fee, 2, ".", "") . " zł", 0, 0, 'C');
        $this->Ln();
        $this->Ln();
        $this->centerTable($GLOBALS['width1Col'] + 2*$GLOBALS['width2Col']);
        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], "Data", 1, 0, 'C');
        $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], "Nazwa", 1, 0, 'C');
        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], "Cena", 1, 0, 'C');
        $this->Ln();
        $this->stopCenterTable();
    }

    function expensesViewTable($conn)
    {
        $tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
        $id = mysqli_fetch_array($tmpID);
        $_SESSION['userID'] = $id["id"]; //userID = treasuerID
        $result = $conn->query(sprintf("SELECT date, name, SUM(price) as price from expense WHERE class_account_id = (SELECT id FROM class_account WHERE class_id = (SELECT id FROM class WHERE school_year_id=" . $GLOBALS['yearID'] . " AND parent_id= " . $_SESSION['userID'] . "))  group by name,date order by date desc"));
        if (mysqli_num_rows($result) > 0)
	    {
            while ($row = mysqli_fetch_array($result))
            {
                $this->centerTable($GLOBALS['width1Col'] + 2*$GLOBALS['width2Col']);
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], $row["date"], 1, 0, 'C');
                $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], $row["name"], 1, 0, 'C');
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], number_format($row["price"], 2, ".", "") . ' zł', 1, 0, 'C');
                $this->stopCenterTable();
                $this->Ln();
            }
        }
        else
        {
            $sumOfWidth = $GLOBALS['width1Col'] + 2*$GLOBALS['width2Col'];
            $this->centerTable($sumOfWidth);
            $this->Cell($sumOfWidth, $GLOBALS['height'], 'Brak wydatków klasowych', 1, 0, 'C');
            $this->stopCenterTable();
            $this->Ln();
        }
    }

    function transferReportHeaderTable($conn)
    {
        $this->addTitle("4. Transfery");
        $this->centerTable($GLOBALS['width1Col'] + 3*$GLOBALS['width2Col']);
        $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], 'Data', 1, 0, 'C');
        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], 'Kwota', 1, 0, 'C');
        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], 'Rodzaj przelewu', 1, 0, 'C');
        $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], 'Rachunek', 1, 0, 'C');
        $this->stopCenterTable();
        $this->Ln();
    }

    function transferReportViewTable($conn)
    {
        $result = $conn->query(sprintf("SELECT * FROM transfer WHERE class_id=(SELECT id FROM class WHERE school_year_id=" . $GLOBALS['yearID'] . " AND parent_id='" . $_SESSION['userID'] . "') order by date desc"));
       
        if (mysqli_num_rows($result) > 0)
        {
            while ($row = mysqli_fetch_array($result))
            {
                $cash = $row["cash"];
                if($cash>=0){
                    $type="Wypłata z konta";
                    //z konta na gotówke
                }else{
                    $type="Wpłata na konto";
                    //z gotowki na konto
                    $cash = $cash *(-1);
                }
                $class_account = $row["class_account"];
                if($class_account==1){
                    $account="Klasowe";
                }
                else{
                    $account="Uczniowski";
                }
                $this->centerTable($GLOBALS['width1Col'] + 3*$GLOBALS['width2Col']);
                $this->Cell($GLOBALS['width1Col'], $GLOBALS['height'], $row["date"], 1, 0, 'C');
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], number_format($cash, 2, ".", "") . " zł", 1, 0, 'C');
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], $type, 1, 0, 'C');
                $this->Cell($GLOBALS['width2Col'], $GLOBALS['height'], $account, 1, 0, 'C');
                $this->stopCenterTable();
                $this->Ln();
            }
        }
        else
        {
            $sumOfWidth = $GLOBALS['width1Col'] + 3 * $GLOBALS['width2Col'];
            $this->centerTable($sumOfWidth);
            $this->Cell($sumOfWidth, $GLOBALS['height'], 'Brak transferów w tym roku', 1, 0, 'C');
            $this->stopCenterTable();
            $this->Ln();
        }
    }
}

//pdf settings
$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);
$pdf->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.ttf', true);

$pdf->mainHeader($conn);

$pdf->generalReportHeaderTable($conn);
$pdf->generalReportViewTable($conn);

$pdf->eventReportTable($conn);

$pdf->expensesHeaderTable($conn);
$pdf->expensesViewTable($conn);

$pdf->transferReportHeaderTable($conn);
$pdf->transferReportViewTable($conn);
$pdf->Output();

?>