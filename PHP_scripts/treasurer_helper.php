<?php

if ((isset($_POST['changePassword'])))
{
	changePassword();
}

if ((isset($_POST['changeMonthlyFee'])))
{
	changeMonthlyFee();
}

if ((isset($_POST['addEvent'])))
{
	addEvent();
}

if ((isset($_POST['addChildParent'])))
{
	addChildParent();
}

if ((isset($_POST['RequiredNewPasswordAccept'])))
{
	changePassword();
}

if ((isset($_POST['changeParentMail'])))
{
	changeParentMail();
}

if ((isset($_POST['editEvent'])))
{
	editEvent();
}

if ((isset($_POST['addExpense'])))
{
	addExpense();
}

if ((isset($_POST['makePayment2'])))
{
	makePayment2();
}

if ((isset($_POST['payForChildEvent'])))
{
	payForEvent();
}



if ((isset($_POST['function2call'])))
{
	$function2call = $_POST['function2call'];
	switch ($function2call)
	{
	case 'students_list':
		fetch_students_list();
		break;

	case 'treasuer_data':
		fetch_treasurer_data();
		break;

	case 'fetch_event_list':
		fetch_event_list();
		break;

	case 'fetch_class_name':
		fetch_class_name();
		break;

	case 'deleteStudent':
		deleteStudent();
		break;

	case 'btn_pMailChange':
		btn_pMailChange();
		break;

	case 'fetch_event_details':
		fetch_event_details();
		break;

	case 'deleteEvent':
		deleteEvent();
		break;

	case 'saveEditEvent':
		saveEditEventID();
		break;

	case 'fetch_expenses_list':
		fetch_expenses_list();
		break;
		
	case 'makePayment':
		makePayment();
		break;
	
	case 'students_balances_list':
		students_balances_list();
		break;
	
	case 'student_class_acc_payment_details':
		student_class_acc_payment_details();
		break;
		
	case 'payForEventTmp':
		payForEventTmp();
		break;
		
	case 'fetch_accounts_amount':
		fetch_accounts_amount();
		break;
		
	}
}


function payForEventTmp()
{
	session_start();
	$_SESSION['eventToBePaid'] = $_POST["eventID"];
	$_SESSION['childToBePaid'] = $_POST["childID"];
}
 


function payForEvent()
{
	session_start();
	require_once "connection.php";
	$connect = new mysqli($servername, $username, $password, $dbName);
	
	$pricex = $connect->query(sprintf("SELECT price FROM event WHERE id = ".$_SESSION['eventToBePaid'] ));
	$x = mysqli_fetch_array($pricex);
	$price = $x["price"];

	$alreadyPaidx = $connect->query(sprintf("SELECT amount_paid FROM participation WHERE event_id = ".$_SESSION['eventToBePaid']." AND child_id =".$_SESSION['childToBePaid'] ));
	$y = mysqli_fetch_array($alreadyPaidx);
	$alreadyPaid = $y["amount_paid"];	
	
	$wantPay = $_POST["amount"];
	
	$leftToPay = $price - $alreadyPaid;
	
	
	$payAll=0;
	if(isset($_POST['payAll'])){
		$payAll = 1;
	}
	else{
		$payAll = 0;
	}
	if($wantPay == $leftToPay){
		$payAll = 1;
	}
	
	$accountBalancex = $connect->query(sprintf("SELECT balance FROM account WHERE child_id = ".$_SESSION['childToBePaid']));
	$z = mysqli_fetch_array($accountBalancex);
	$accountBalance = $z["balance"];	
	
	$willBePaid =0;
	
	if($payAll == 1){
		if($accountBalance < $leftToPay){
			$willBePaid = $accountBalance;
			$echoo = "Stan Konta ucznia nie pozwolił na opłacenie całej żądanej kwoty. Kwota została opłącona częściowo!";
		}
		else{
			$willBePaid = $leftToPay;
			$echoo = "Wydarzenie zostało w pełni opłacone!";
		}
			
		
	}
	else{ //if treasuer want to pay other amount than all
		if($wantPay > $leftToPay){
			if($accountBalance >= $wantPay){
				$willBePaid = $leftToPay;
				$echoo = "Żądana kwota jest większa niż kwota pozostała do zapłąty. Wydarzenie zostało w pełni opłacone!!";
			}
			else{
				$willBePaid = $accountBalance;
				$echoo = "Stan Konta ucznia nie pozwolił na opłacenie całej żądanej kwoty. Kwota została opłącona częściowo!";
			}
		}
			
		if($wantPay < $leftToPay){
			if($accountBalance >= $wantPay){
				$willBePaid = $wantPay;
				$echoo = "Wydarzenie zostało opłacone żądaną kwotą!";
			}
			else{
				$willBePaid = $accountBalance;
				$echoo = "Stan Konta ucznia nie pozwolił na opłacenie całej żądanej kwoty. Kwota została opłącona częściowo!";
			}
		}
	}
	
	$connect->query(sprintf("UPDATE participation SET amount_paid =amount_paid+".$willBePaid." WHERE child_id =".$_SESSION['childToBePaid']." AND event_id=".$_SESSION['eventToBePaid']));
	$connect->query(sprintf("UPDATE account SET balance = balance - ".$willBePaid." WHERE child_id=".$_SESSION['childToBePaid']));
	header('Location: menu_treasurer.php');
	
} 

function student_class_acc_payment_details()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$sum = $connect->query(sprintf("SELECT SUM(amount) as s FROM class_account_payment WHERE child_id = " . $_POST['id']));
	$r = $sum->fetch_assoc();
	$amount_of_paid_money = $r["s"];
	$monthly_f = $connect->query(sprintf("SELECT monthly_fee as m FROM class_account WHERE class_id = (SELECT class_id FROM child WHERE id =" . $_POST['id'] . ")"));
	$x = $monthly_f->fetch_assoc();
	$monthly_fee = $x["m"];
	$output.= '
<h3> Opłacone miesiące </h3>	
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
                     <th width="50%">Miesiąc</th> 
					 <th width="50%">Wpłacona kwota</th>
                </tr>
			<thead>';
	if ($amount_of_paid_money > 0)
	{
		$months = array(
			'wrzesień',
			'październik',
			'listopad',
			'grudzień',
			'styczeń',
			'luty',
			'marzec',
			'kwiecień',
			'maj',
			'czerwiec'
		);
		$topay = 0;
		$fild_color = '#66ff66';
		$fully_paid_months = floor($amount_of_paid_money / $monthly_fee);
		for ($i = 0; $i < 10; $i++)
		{
			if ($i < $fully_paid_months)
			{
				$topay = $monthly_fee;
			}
			else
			{
				if ($i == $fully_paid_months)
				{
					$topay = - (($i) * $monthly_fee) + $amount_of_paid_money;
					$fild_color = '#FF5050';
				}
				else
				{
					$topay = 0;
				}
			}

			$output.= ' <tbody> 
						<tr>  
							<td bgcolor=' . $fild_color . ' >' . $months[$i] . '</td> 
							<td bgcolor=' . $fild_color . '  >' . $topay . '</td> 
						</tr>  
				  <tbody> ';
		}
	}
	else
	{
		$output.= '<tr>  
								  <td colspan="4">Nie znaleziono wpłat</td>  
							 </tr>';
	}

	$surcharge = $amount_of_paid_money - 10* $monthly_fee;
	if($surcharge<0){
		$surcharge=0;
	}
	$output.= '</table>  
			  </div>
			  <h3> Informacje o koncie</h3>
			  Na koncie klasowym dziecka po opłaceniu składek miesięcznych pozostało: '.$surcharge;
			  
	
	
	echo $output;
}





function students_balances_list()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = $connect->query(sprintf("SELECT * FROM child WHERE class_id=(SELECT id FROM class WHERE parent_id='" . $_SESSION['userID'] . "') order by surname"));
	$output.= '  
		<div class="table-responsive ">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark">
                <tr>  
                     <th scope="col">Imię i  Nazwisko</th> 
					 <th scope="col">Konto klasowe</th>
					 <th scope="col">Konto dziecka</th>
					<!-- <th scope="col">Szczegóły</th> -->
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$class_account_balanceTMP = $connect->query(sprintf("SELECT IFNULL(SUM(amount),0) AS x FROM class_account_payment WHERE child_id = ".$row["id"] ));
			$class_account_balance = mysqli_fetch_array($class_account_balanceTMP);
			$account_balanceTMP = $connect->query(sprintf("SELECT balance FROM account WHERE child_id = ".$row["id"] ));
			$account_balance = mysqli_fetch_array($account_balanceTMP);
			//TODO
			$month_count = $connect->query(sprintf("SELECT TIMESTAMPDIFF(MONTH,concat(year(curdate()),'-09-01'),CURDATE()) as date FROM DUAL"));
			$months=mysqli_fetch_array($month_count);
			$monthly_fee = $connect->query(sprintf("SELECT monthly_fee AS fee FROM class_account WHERE class_id=(SELECT id FROM class WHERE parent_id='" . $_SESSION['userID'] . "') " ));
			$fee=mysqli_fetch_array($monthly_fee);
			$output.= '  
			<tbody>		
                <tr>  
                     <td>' . $row["name"] . ' ' . $row["surname"] . '</td>  ';
			$child_class_account = '';
			$expected_value = intval($months["date"]) * intval($fee["fee"]); 
			$child_class_account = intval($class_account_balance["x"]) - $expected_value;
			$output.='		 <td>'. $child_class_account .' zł </td>
					 <td>' . $account_balance["balance"] . ' zł</td>
					<!-- <td><button type="button" data-toggle="modal" data-target="#classAccBalanceDetails"  data-id3="' . $row["id"] . '" class="btn_detailsClassAccBalance  btn btn-default">Szczegóły</button></td>-->
				</tr> 
			<tbody>						
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="3">Nie dodano jeszcze uczniów do tej klasy</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}

function makePayment2()
{
	session_start();
	if (empty($_POST['amountOfMoney']) || $_POST['amountOfMoney'] == '0')
	{
		header('Location: treasuer_menu/settings.php');
		exit();
	}

	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
		$amountOfMoney = $_POST['amountOfMoney'];
		$child = $_SESSION['childWhoMakePayment'];
		if ($_POST['typeOfAccount'] == "normal")
		{ //if treasuer make normal (cash) payment
			$curr_balance = $conn->query(sprintf("SELECT balance as b FROM account WHERE child_id =" . $_SESSION['childWhoMakePayment']));
			$res_balance = mysqli_fetch_array($curr_balance);
			$currentBalance = $res_balance["b"];
			$curr_cash = $conn->query(sprintf("SELECT cash as c FROM account WHERE child_id =" . $_SESSION['childWhoMakePayment']));
			$res_cash = mysqli_fetch_array($curr_cash);
			$currentCash = $res_cash["c"];
			$account_idTMP = $conn->query(sprintf("SELECT id FROM account WHERE child_id =" . $_SESSION['childWhoMakePayment']));
			$res = mysqli_fetch_array($account_idTMP);
			$accountID = $res["id"];
			if($_POST['paymentType'] == "gotowka")
			{
				$newBalance = $currentCash + $amountOfMoney;
				$conn->query(sprintf("INSERT INTO payment (account_id,amount,type) VALUES (" . $accountID . "," . $amountOfMoney . ",'" . $_POST['paymentType'] . "')"));
				$conn->query(sprintf("UPDATE account SET cash=" . $newBalance . " WHERE child_id =" . $_SESSION['childWhoMakePayment']));
				echo "Record updated successfully";
			}
			else
			{
				$newBalance = $currentBalance + $amountOfMoney;
				$conn->query(sprintf("INSERT INTO payment (account_id,amount,type) VALUES (" . $accountID . "," . $amountOfMoney . ",'" . $_POST['paymentType'] . "')"));
				$conn->query(sprintf("UPDATE account SET balance=" . $newBalance . " WHERE child_id =" . $_SESSION['childWhoMakePayment']));
				echo "Record updated successfully";
			}
			
			
			//if ($result = $conn->query(sprintf("UPDATE account SET balance='%s' WHERE child_id = '%s'", mysqli_real_escape_string($conn, $newBalance) , mysqli_real_escape_string($conn, $child))))
			//{
			
			
			//}
		}
		else
		{ //if treasuer want to transfer money to child's class account

			// fetch class account id

			$class_acc_id = $conn->query(sprintf("SELECT id FROM class_account WHERE class_id = (SELECT class_id FROM child WHERE id =" . $_SESSION['childWhoMakePayment'] . ")"));
			$ress = $class_acc_id->fetch_assoc();
			$class_account_id = $ress["id"];

			// inserting payment to class account
			$conn->query(sprintf("INSERT INTO class_account_payment (amount,class_account_id, child_id,type) VALUES (" . $amountOfMoney . "," . $class_account_id . "," . $_SESSION['childWhoMakePayment'] . ",'" . $_POST['paymentType'] . "')"));
			
			$curr_balance = $conn->query(sprintf("SELECT balance as b,cash as c FROM class_account WHERE id =". $class_account_id));
			$res_balance = mysqli_fetch_array($curr_balance);
			$currentBalance = $res_balance["b"];
			$currentCash = $res_balance["c"];
			
			if($_POST['paymentType'] == "gotowka")
			{
				$newBalance = $currentCash + $amountOfMoney;
				$conn->query(sprintf("INSERT INTO payment (account_id,amount,type) VALUES (" . $accountID . "," . $amountOfMoney . ",'" . $_POST['paymentType'] . "')"));
				$conn->query(sprintf("UPDATE class_account SET cash=" . $newBalance . " WHERE id =".$class_account_id));
				echo "Record updated successfully";
			}
			else
			{
				$newBalance = $currentBalance + $amountOfMoney;
				$conn->query(sprintf("INSERT INTO payment (account_id,amount,type) VALUES (" . $accountID . "," . $amountOfMoney . ",'" . $_POST['paymentType'] . "')"));
				$conn->query(sprintf("UPDATE class_account SET balance=" . $newBalance . " WHERE id =".$class_account_id));
				echo "Record updated successfully";
			}
		}
	}

	$conn->close();
	header('Location: treasuer_menu/settings.php');
}


function makePayment()
{
	session_start();
	$_SESSION['childWhoMakePayment'] = $_POST["id"];	
}

// button "Dodaj wydatek" in expenses.php
function addExpense()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$class_account_idx = $connect->query(sprintf("SELECT * FROM class_account WHERE class_id = (SELECT id FROM class WHERE parent_id= " . $_SESSION['userID'] . ")"));
	$clid = mysqli_fetch_array($class_account_idx);
	$class_account_id = $clid["id"];
	$excepted_budget = $clid["expected_budget"];
	$curr_exp = $connect->query(sprintf("SELECT SUM(price) FROM expense WHERE class_account_id = " . $class_account_id));
	$x = mysqli_fetch_array($class_account_idx);
	$currentExpenses = $x["s"];
	if ($_POST["expensePrice"] + $currentExpenses <= $excepted_budget)
	{
		$connect->query(sprintf("INSERT INTO expense (name,price, class_account_id) VALUES ('" . $_POST["expenseName"] . "'," . $_POST["expensePrice"] . ", " . $class_account_id . ")"));

		// KOMUNIKAT ZE DODANO POMYSLNIE

		echo "<script>
alert('Dodano pomyślnie!');
	window.location.href='treasuer_menu/expenses.php';
	</script>";
	}
	else
	{

		// KOMUNIKAT ZE BUDZET JEST PRZEKROCZONY I NIE MOZNA DODAC

		echo "<script>
	alert('Przekroczono budżet -> nie można już dodać wydatków z klasowych!');
	window.location.href='treasuer_menu/expenses.php';
	</script>";
	}
}

function fetch_expenses_list()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$tmpID = $connect->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$id = mysqli_fetch_array($tmpID);
	$_SESSION['userID'] = $id["id"]; //userID = treasuerID
	$result = $connect->query(sprintf("SELECT * from expense WHERE class_account_id = (SELECT id FROM class_account WHERE class_id = (SELECT id FROM class WHERE parent_id= " . $_SESSION['userID'] . "))"));
	$output.= '<div class="container">
		<button type="button" data-toggle="modal" data-target="#addExpense" class="btn_deleteEvent btn btn-default">Dodaj wydatek</button> 
		</div>
		<div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark">
                <tr>  
                     <th  scope="col">Nazwa</th>  
                     <th  scope="col">Cena</th> 
					 <th  scope="col">Data</th>
                </tr>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$output.= '  
			<tbody>	
                <tr>  
                    <!-- <td>' . $row["id"] . '</td>-->  
                     <td >' . $row["name"] . '</td>  
					 <td >' . $row["price"] . ' zł</td>
					 <td >' . $row["date"] . '</td>
				</tr> 
			<tbody>				
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="4">Nie dodano jeszcze wydatków w tej klasie</td>  
                     </tr>';
	}


	$output.= '</table>


		  </div>';
	echo $output;
}

function fetch_accounts_amount()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$tmpID = $connect->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$id = mysqli_fetch_array($tmpID);
	$_SESSION['userID'] = $id["id"]; //userID = treasuerID

	$tmpbalance = $connect->query(sprintf("SELECT id, balance, expected_budget FROM class_account WHERE class_id = (SELECT id FROM class WHERE parent_id = " . $_SESSION['userID'] . " )"));
	$bal = mysqli_fetch_array($tmpbalance);
	$balance = $bal["balance"];
	$exceptedBalance = $bal["expected_budget"];
	$class_account_id = $bal["id"];
	$curr_exp = $connect->query(sprintf("SELECT SUM(price) as s FROM expense WHERE class_account_id = " . $class_account_id));
	$x = mysqli_fetch_array($curr_exp);
	$currentExpenses = $x["s"];
	$availableMoney = $exceptedBalance - $currentExpenses;
	$in_cash = $connect->query(sprintf("SELECT SUM(amount) as cash FROM class_account_payment WHERE class_account_id = " . $class_account_id . " and type='gotowka' "));
	$cash = mysqli_fetch_array($in_cash);
	$in_bank = $connect->query(sprintf("SELECT SUM(amount) as cash FROM class_account_payment WHERE class_account_id = " . $class_account_id . " and type='konto' "));
	$bank = mysqli_fetch_array($in_bank);
	$kids_account_balance = $connect->query(sprintf("SELECT SUM(balance) as cash FROM account join child on (account.child_id = child.id) where child.class_id = (SELECT id FROM class WHERE parent_id = " . $_SESSION['userID'] . " )"));
	$kids_account_balance_all = mysqli_fetch_array($kids_account_balance);
	$output.= '
	 <p> Ilość pieniędzy zebranych na koncie klasowym całkowita: ' . $balance . ' zł.</p>
	 <p> W tym: </p>
	 <p> - ilość pieniędzy klasowych w gotówce: ' . $cash["cash"] . ' zł</p>
	 <p> - ilość pieniędzy klasowych na koncie: ' . $bank["cash"] . ' zł</p> 
	 <p> Ilość pieniędzy na kontach dzieci: ' . $kids_account_balance_all["cash"] . ' zł</p> 
	 <p> W tym: </p>
	 <p> - ilość pieniędzy z kont dzieci w gotówce:  zł</p>
	 <p> - ilość pieniędzy z kont dzieci na koncie: zł</p>  ';
	echo $output;
}
// helping function to save which event is edited. Used in handling button confirm

function saveEditEventID()
{
	session_start();
	$_SESSION['changeEventID'] = $_POST["id"];
}

function editEvent()
{
	session_start();
	if (empty($_POST['newEventName']) && empty($_POST['newEventPrice']) && empty($_POST['newEventDate']))
	{
		header('Location: menu_treasurer.php');
		exit();
	}

	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	$currrentDate = date('Y-m-d');
	$res = ($conn->query(sprintf("select * FROM event WHERE id = '" . $_SESSION['changeEventID'] . "'")))->fetch_assoc();
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
		if ($res["date"] >= $currrentDate && $_POST['newEventDate'] >= $currrentDate)
		{
			if (!empty($_POST['newEventName']))
			{
				$newEventName = $_POST['newEventName'];
				$newEventName = htmlentities($newEventName, ENT_QUOTES, "UTF-8");
				$result = $conn->query(sprintf("update event set name='%s' where id='" . $_SESSION['changeEventID'] . "'", mysqli_real_escape_string($conn, $newEventName)));
			}

			if (!empty($_POST['newEventPrice']))
			{
				$newEventPrice = $_POST['newEventPrice'];
				$newEventPrice = htmlentities($newEventPrice, ENT_QUOTES, "UTF-8");
				$result = $conn->query(sprintf("update event set price='%s' where id='" . $_SESSION['changeEventID'] . "'", mysqli_real_escape_string($conn, $newEventPrice)));
			}

			if (!empty($_POST['newEventDate']))
			{
				$newEventDate = $_POST['newEventDate'];
				$newEventDate = htmlentities($newEventDate, ENT_QUOTES, "UTF-8");
				$result = $conn->query(sprintf("update event set date='%s' where id='" . $_SESSION['changeEventID'] . "'", mysqli_real_escape_string($conn, $newEventDate)));
			}

			echo "<script>
	alert('Edycja pomyślna!');
	window.location.href='menu_treasurer.php';
	</script>";
		}
		else
		{
			echo "<script>
	alert('Nie możesz edytować zbiórki, która się odbyło!');
	window.location.href='menu_treasurer.php';
	</script>";
		}
	}

	$conn->close();
}

function deleteEvent()
{
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$currrentDate = date('Y-m-d');
	$res = ($connect->query(sprintf("select * FROM event WHERE id = '" . $_POST["id"] . "'")))->fetch_assoc();

	// checkoig if i can delete event (cannot delete event witch previous date)

	if ($res["date"] > $currrentDate)
	{
		if ($res = $connect->query(sprintf("DELETE FROM event WHERE id = '" . $_POST["id"] . "'")))
		{
			echo 'Pomyslnie usunięto zbiórkę.';
		}
	}
	else
	{
		echo 'Nie możesz usunąć zbiórki, która już się zakończyła!';
	}
}

function fetch_event_details()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = ($connect->query(sprintf("select count(*) as total from participation where event_id ='" . $_POST["id"] . "' ")))->fetch_assoc();
	$output.= "Liczba uczestników zbiórki: " . $result["total"] . "";
	$resultAmount = ($connect->query(sprintf("select price from event where id ='" . $_POST["id"] . "' ")))->fetch_assoc();
	$totalAmount = $resultAmount["price"] * $result["total"];
	$resultAmountPaid = ($connect->query(sprintf("select sum(amount_paid) as totalPaid from participation where event_id='" . $_POST["id"] . "' ")))->fetch_assoc();
	$totalAmountPaid = $resultAmountPaid["totalPaid"];
	$output.= "<br /> Całkowity koszt zbiórki: " . $totalAmount . "<br /> Suma wpłat uczestników: " . $totalAmountPaid . "";
	$output.= "<br /><br />";
	$result = $connect->query(sprintf("select ch.id as childID, ch.name as name , ch.surname as surname, p.amount_paid as amount_paid , (p.amount_paid+'" . $resultAmount["price"] . "') as idx from child ch, participation p where ch.id = p.child_id and p.event_id='" . $_POST["id"] . "' order by idx asc"));
	$output.= ' 
      <div class="table-responsive">
		<table class="table table-striped table-bordered">
		    <thead class="thead-dark"> 
                <tr>  
					<th scope="col">Id</th>
                    <th scope="col">Imie</th>  
                    <th scope="col">Nazwisko</th> 
					<th scope="col">Kwota wpłacona</th>
					<th scope="col">Koszt</th>
					<th scope="col">Opłać</th>
					 
                </tr>
			<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{

			// green color for fully paid events
			if ($row["amount_paid"] == $resultAmount["price"])
			{
				$color = ' bgcolor = #66ff66 ';
			}
			else
			{
				$color = '';
			}

			$output.= '  
			<tbody>
                <tr>  
					<td ' . $color . '>' . $row["childID"] . '</td>
                     <td ' . $color . '>' . $row["name"] . '</td>  
					 <td ' . $color . '>' . $row["surname"] . '</td>
					 <td ' . $color . '>' . $row["amount_paid"] . '</td>
					 <td ' . $color . '>' . $resultAmount["price"] . '</td>
					 <td><button type="button" data-toggle="modal" data-target="#payForEventModal" data-id3="' . $row["childID"] . '" data-id4="' . $_POST["id"] . '" class="btn_payForEvent btn btn-default">Oplac</button></td>

				</tr>  
			<tbody>
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="4">Nie dodano jeszcze zbiórek do tej klasy</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}

function changeParentMail()
{
	session_start();
	echo "<script>console.log( 'Zmieniasz maila rodzicowi o id dziecka:  " . $_SESSION['changeEmailChildID'] . "' );</script>";
	if (empty($_POST['newParentMail']) || $_POST['newParentMail'] == '0')
	{
		header('Location: treasuer_menu/settings.php');
		exit();
	}

	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
		$newParentEmail = $_POST['newParentMail'];
		$newParentEmail = htmlentities($newParentEmail, ENT_QUOTES, "UTF-8");
		$result = $conn->query(sprintf("select * from parent where id=(select parent_id from child where id='" . $_SESSION['changeEmailChildID'] . "')"));
		$details = $result->fetch_assoc();
		$oldParentEmail = $details['email'];
		$conn->query(sprintf("UPDATE parent SET email='%s' where id=(select parent_id from child where id='" . $_SESSION['changeEmailChildID'] . "')", mysqli_real_escape_string($conn, $newParentEmail)));
		$conn->query(sprintf("UPDATE username set login='$newParentEmail' where login='$oldParentEmail'"));
	}

	$conn->close();
	header('Location: treasuer_menu/settings.php');
}

function btn_pMailChange()
{
	session_start();
	$_SESSION['changeEmailChildID'] = $_POST["id"];
	echo "<script>console.log( 'Id:  " . $_SESSION['changeEmailChildID'] . "' );</script>";
}

function deleteStudent()
{
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);

	// checking if student have all previous months paid

	$result = $connect->query(sprintf("SELECT SUM(amount) AS s FROM class_account_payment WHERE child_id = " . $_POST["id"]));
	$res = mysqli_fetch_array($result);
	$paidAmount = $res["s"];
	$currentMonth = date("m");
	$fee = $connect->query(sprintf("SELECT monthly_fee FROM class_account WHERE id = (SELECT distinct class_account_id FROM class_account_payment WHERE child_id = " . $_POST["id"] . ")"));
	$resss = mysqli_fetch_array($fee);
	$monthlyFee = $resss["monthly_fee"];
	if ($currentMonth >= 1 and $currentMonth <= 6)
	{
		$currentMonth = $currentMonth + 12;
	}

	$differenceInMonths = $currentMonth - 9 + 1;
	$charge = $differenceInMonths * $monthlyFee;
	$x = "start";
	if ($charge >= $paidAmount)
	{
		$x = 'Nie można usunąć dziecka bo nie opłaciło wszystkich opłat';
	}
	else
	{

		// deleting student

		if ($res = $connect->query(sprintf("DELETE FROM child WHERE id = '" . $_POST["id"] . "'")))
		{
			$x = 'Pomyslnie usunięto ucznia';
		}
	}

	echo $x;
}

function fetch_class_name()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$result = $connect->query(sprintf("SELECT name FROM class WHERE parent_id = (SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "')"));
	$res = mysqli_fetch_array($result);
	$output = "<h3>Konto klasy " . $res['name'] ."</h3>";
	echo $output;
}

function fetch_event_list()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = $connect->query(sprintf("select * from event where class_id=(select id from class where parent_id='" . $_SESSION['userID'] . "')"));
	$output.= '  
		<div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
                   <!--  <th scope="col">Id</th>-->  
                     <th scope="col">Nazwa</th> 
					 <th scope="col">Cena</th>
					 <th scope="col">Data</th>
					 <th scope="col">Szczegóły</th>
					 <th scope="col">Edycja</th>
					 <th scope="col">Usuwanie</th> 
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$output.= ' 
			<tbody>				
                <tr>  
                    <!-- <td>' . $row["id"] . '</td>  -->
                     <td>' . $row["name"] . '</td>  
					 <td>' . $row["price"] . ' zł</td>
					 <td>' . $row["date"] . '</td>
					 <td><button type="button" data-toggle="modal" data-target="#eventDetailsModal"  data-id4="' . $row["id"] . '" class="btn_detailsEvent btn btn-default">Szczegóły</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#eventEditModal"  data-id4="' . $row["id"] . '" class="btn_editEvent btn btn-default">Edytuj</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#eventDeleteModal" data-id4="' . $row["id"] . '" class="btn_deleteEvent btn btn-default">Usuń zbiórkę</button></td>
				</tr>
			<tbody>				
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="4">Nie dodano jeszcze zbiórek do tej klasy</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}

function fetch_students_list()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$tmpID = $connect->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$id = mysqli_fetch_array($tmpID);
	$_SESSION['userID'] = $id["id"];
	$result = $connect->query(sprintf("SELECT * from child WHERE class_id = (SELECT id FROM class WHERE parent_id = " . $_SESSION['userID'] . ")"));
	$output.= '  
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
                  <!--   <th scope="col">Id</th>  -->
                     <th scope="col">Imię</th> 
					 <th scope="col">Nazwisko</th>
					 <th scope="col">Data urodzenia</th>
					 <th scope="col">Imię rodzica</th>
					 <th scope="col">Nazwisko rodzica</th>
					 <th scope="col">Mail rodzica</th>
					 <th scope="col">Stan konta dziecka</th>
					 <th scope="col">Zmień maila rodzica</th>
					 <th scope="col">Wpłać</th>
					 <th scope="col">Usuń ucznia</th>
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$parentTMP = $connect->query(sprintf("SELECT * FROM parent WHERE id = (SELECT parent_id FROM child WHERE id = " . $row["id"] . ")"));
			$parent = mysqli_fetch_array($parentTMP);
			
			$accountBalanceTmp = $connect->query(sprintf("SELECT * FROM account WHERE child_id = " . $row["id"]));
			$accountBalance = mysqli_fetch_array($accountBalanceTmp);
			$countBalance=doubleval($accountBalance["balance"])+doubleval($accountBalance["cash"]);
			$output.= '  
			<tbody>	
                <tr>  
                  <!--   <td>' . $row["id"] . '</td>  -->
                     <td>' . $row["name"] . '</td>  
					 <td>' . $row["surname"] . '</td>
					 <td>' . $row["date_of_birth"] . '</td>
					 <td>' . $parent["name"] . '</td>
					 <td>' . $parent["surname"] . '</td>
					 <td>' . $parent["email"] . '</td>
					 <td>' . $countBalance . '</td>
					 <td><button type="button" data-toggle="modal" data-target="#changeParMailModal" data-id3="' . $row["id"] . '" class="btn_pMailChange btn btn-default">Zmień maila</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#makePaymentModal" data-id3="' . $row["id"] . '" class="btn_makePayment btn btn-default">Wpłać</button></td>
					 <td><button type="button" data-id3="' . $row["id"] . '" class="btn_deleteStudent btn btn-default">Usuń ucznia</button></td>
				</tr>  
			<tbody>
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="4">Nie dodano jeszcze uczniów do tej klasy</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}

// showing in settings

function fetch_treasurer_data()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = $connect->query(sprintf("SELECT * FROM parent WHERE id =" . $_SESSION['userID']));
	$res = mysqli_fetch_array($result);
	$output.= '
	 <div class="table-responsive">
		<table class="table table-bordered">
		<tbody>
		<tr><td>Imię: </td><td>' . $res["name"] . '</td></tr> 
		<tr><td>Nazwisko: </td><td>' . $res["surname"] . '</td></tr> 
		<tr><td>Email: </td><td>' . $res["email"] . '</td></tr> 
		<tbody>
	<table>
	</div>
		   ';
	echo $output;
}

function changeMonthlyFee()
{
	session_start();
	if (empty($_POST['newMonthlyFee']) || $_POST['newMonthlyFee'] == '0')
	{
		header('Location: treasuer_menu/settings.php');
		exit();
	}

	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
		$newMonthlyFee = $_POST['newMonthlyFee'];
		$newMonthlyFee = htmlentities($newMonthlyFee, ENT_QUOTES, "UTF-8");
		$login = $_SESSION['user'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$result = $conn->query(sprintf("UPDATE class_account SET monthly_fee='%s' WHERE class_id=(SELECT id from class WHERE parent_id = (SELECT id FROM parent WHERE email = '%s'))", mysqli_real_escape_string($conn, $newMonthlyFee) , mysqli_real_escape_string($conn, $login)));
	}

	$conn->close();
	header('Location: treasuer_menu/settings.php');
}

function changePassword()
{
	session_start();
	if (empty($_POST['newPassword']) || $_POST['newPassword'] == '0')
	{
		header('Location: treasuer_menu/settings.php');
		exit();
	}

	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
		$newPassword = $_POST['newPassword'];
		$newPassword = htmlentities($newPassword, ENT_QUOTES, "UTF-8");
		$login = $_SESSION['user'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$result = $conn->query(sprintf("UPDATE username SET password='%s',first_login=FALSE WHERE login='%s'", mysqli_real_escape_string($conn, $newPassword) , mysqli_real_escape_string($conn, $login)));
	}

	$conn->close();
	header('Location: logout.php');
}

function addEvent()
{
	session_start();
	if (empty($_POST['eventName']) || $_POST['eventName'] == '0' || empty($_POST['eventPrice']) || $_POST['eventPrice'] == '0' || empty($_POST['eventDate']) || $_POST['eventDate'] == '0')
	{
		header('Location: treasuer_menu/addOnceEvent.php');
		exit();
	}

	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
		$eventName = $_POST['eventName'];
		$eventName = htmlentities($eventName, ENT_QUOTES, "UTF-8");
		$eventPrice = $_POST['eventPrice'];
		$eventPrice = htmlentities($eventPrice, ENT_QUOTES, "UTF-8");
		$eventDate = $_POST['eventDate'];
		$eventDate = htmlentities($eventDate, ENT_QUOTES, "UTF-8");
		$resultclassID = ($conn->query(sprintf("select * from class where parent_id='" . $_SESSION['userID'] . "'")))->fetch_assoc();
		$classID = $resultclassID['id'];
		$result = $conn->query(sprintf("insert into event (name,price,date,class_ID) values ('%s' , '%s' ,'%s',$classID)", mysqli_real_escape_string($conn, $eventName) , mysqli_real_escape_string($conn, $eventPrice) , mysqli_real_escape_string($conn, $eventDate)));
		$resulteventID = ($conn->query(sprintf("select * from event where name='%s' and date='%s'", mysqli_real_escape_string($conn, $eventName) , mysqli_real_escape_string($conn, $eventDate))))->fetch_assoc();
		$eventID = $resulteventID['id'];
		$result = $conn->query(sprintf("select * from child where class_id='" . $classID . "'"));
		if (mysqli_num_rows($result) > 0)
		{
			while ($row = mysqli_fetch_array($result))
			{
				$eventPrice = $_POST['eventPrice'];

				$conn->query(sprintf("insert into participation (event_id,child_id,amount_paid) values ('%s','%s', 0)", mysqli_real_escape_string($conn, $eventID) , mysqli_real_escape_string($conn, $row["id"]) ));
				$parent = ($conn->query(sprintf("select * from parent where id=(select parent_id from child where id='" . $row["id"] . "')")))->fetch_assoc();
				mail($parent["email"], "Dodano nową zbiórkę: $eventName", "Dzień dobry, chcielibyśmy poinformować, że w systemie SkrabnikKlasowy pojawiła się nowa zbiórka o nazwie $eventName i cenie $eventPrice. Odbędzie się ono $eventDate. SystemSKARBNIKklasowy");
			}
		}
		else
		{
			echo "Nie udalo sie dodac dziecka";
		}
	}

	$conn->close();
	header('Location: treasuer_menu/addOnceEvent.php');
}

function randomPassword()
{
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++)
	{
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}

	return implode($pass); //turn the array into a string
}

function addChildParent()
{
	session_start();
	if (empty($_POST['childName']) || $_POST['childName'] == '0' || empty($_POST['childSurname']) || $_POST['childSurname'] == '0' || empty($_POST['childBirthdate']) || $_POST['childBirthdate'] == '0' || empty($_POST['parentName']) || $_POST['parentName'] == '0' || empty($_POST['parentSurname']) || $_POST['parentSurname'] == '0')
	{
		header('Location: treasuer_menu/addStudent.php');
		exit();
	}

	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
		$childName = $_POST['childName'];
		$childName = htmlentities($childName, ENT_QUOTES, "UTF-8");
		$childSurname = $_POST['childSurname'];
		$childSurname = htmlentities($childSurname, ENT_QUOTES, "UTF-8");
		$childBirthdate = $_POST['childBirthdate'];
		$childBirthdate = htmlentities($childBirthdate, ENT_QUOTES, "UTF-8");
		$parentName = $_POST['parentName'];
		$parentName = htmlentities($parentName, ENT_QUOTES, "UTF-8");
		$parentSurname = $_POST['parentSurname'];
		$parentSurname = htmlentities($parentSurname, ENT_QUOTES, "UTF-8");
		$passwd = randomPassword();
		if (empty($_POST['parentEmail']) || $_POST['parentEmail'] == '0')
		{
			$parentEmail = $parentName . $parentSurname;
			$parentEmail = htmlentities($parentEmail, ENT_QUOTES, "UTF-8");

			// utworzenie uchwytu do pliku
			// tryb a umożliwia zapis na końcu pliku

			$plik = fopen('ParentsWithoutEmail.txt', 'a');

			// przypisanie zawartości do zmiennej

			$zawartosc = "Login : " . $parentEmail . " hasło : " . $passwd . "\r\n";
			fwrite($plik, $zawartosc);
		}
		else
		{
			$parentEmail = $_POST['parentEmail'];
			$parentEmail = htmlentities($parentEmail, ENT_QUOTES, "UTF-8");
		}

		// id klasy zalgodowanego skarbnika

		$classID1 = $conn->query(sprintf("SELECT id FROM class where parent_id=(SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "' )"));
		$classID = mysqli_fetch_array($classID1) ["id"];
		if ($result = @$conn->query(sprintf("SELECT * FROM parent WHERE email='%s'", mysqli_real_escape_string($conn, $parentEmail))))
		{
			$isUser = $result->num_rows;
			if ($isUser <= 0)
			{ //RODZICA NIE MA W SYSTEMIE
				$result = $conn->query(sprintf("insert into parent (name,surname,email,type) values ('%s' , '%s' ,'%s','p')", mysqli_real_escape_string($conn, $parentName) , mysqli_real_escape_string($conn, $parentSurname) , mysqli_real_escape_string($conn, $parentEmail)));
				mail($parentEmail, "Haslo pierwszego logowania rodzica", "Twoje hasło pierwszego logowanie to: $passwd");

				// szukamy id nowego rodzica

				if ($result = @$conn->query(sprintf("SELECT * FROM parent WHERE email='%s'", mysqli_real_escape_string($conn, $parentEmail))))
				{
					$details = $result->fetch_assoc();
					$parentIDdb = $details['id'];

					// dodanie do username

					$conn->query(sprintf("insert into username (login,password,type,first_login,parent_id) values ('%s' , '$passwd' ,'p',TRUE,'$parentIDdb')", mysqli_real_escape_string($conn, $parentEmail)));
					$result = $conn->query(sprintf("insert into child (name,surname,date_of_birth,parent_id,class_id) values ('%s' , '%s' ,'%s','$parentIDdb','$classID')", mysqli_real_escape_string($conn, $childName) , mysqli_real_escape_string($conn, $childSurname) , mysqli_real_escape_string($conn, $childBirthdate)));
				}
			}
			else
			{

				// RODZIC JEST JUZ W SYSTEMIE WIEC DODAJE SAMO DZIECKO

				$details = $result->fetch_assoc();
				$parentIDdb = $details['id'];
				$result = $conn->query(sprintf("insert into child (name,surname,date_of_birth,parent_id,class_id) values ('%s' , '%s' ,'%s','$parentIDdb','$classID')", mysqli_real_escape_string($conn, $childName) , mysqli_real_escape_string($conn, $childSurname) , mysqli_real_escape_string($conn, $childBirthdate)));
			}
		}

		$conn->close();
		header('Location: treasuer_menu/addStudent.php');
	}
}

?>