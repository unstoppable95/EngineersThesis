<?php

if ((isset($_POST['changePassword'])))
{
	//in settings
	changeOldPassword();
}

if ((isset($_POST['changeMonthlyFee'])))
{
	changeMonthlyFee();	
}

if ((isset($_POST['changeBankAccount'])))
{
	changeBankAccount();
}

if ((isset($_POST['add_transfer'])))
{
	add_transfer();
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
	//after first login
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
if ((isset($_POST['deleteStudentEvent'])))
{
	deleteStudent();
}
if ((isset($_POST['endEvent'])))
{
	endEvent();
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
if ((isset($_POST['deleteEvent'])))
{
	deleteEvent();
}
if ((isset($_POST['school_year_id'])))
{
	generate_school_year_raport();
}


if ((isset($_POST['function2call'])))
{
	$function2call = $_POST['function2call'];
	switch ($function2call)
	{
	case 'fetch_school_year_id_options':
		fetch_school_year_id_options();
		break;
	case 'set_selected_rowID':
		set_selected_rowID();
		break;
		
	case 'students_list':
		fetch_students_list();
		break;

	case 'treasuer_data':
		fetch_treasurer_data();
		break;

	case 'monthly_fee':
		fetch_monthly_fee();
		break;

	case 'bank_account_number':
		fetch_bank_account_number();
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
		
	case 'fetch_transfer_list':
		fetch_transfer_list();
		break;
	case 'makePayment':
		makePayment();
		break;
	
	case 'students_balances_list':
		students_balances_list();
		break;
	
	/*case 'student_class_acc_payment_details':
		student_class_acc_payment_details();
		break;
		*/
	case 'payForEventTmp':
		payForEventTmp();
		break;
		
	case 'fetch_class_account_information':
		fetch_class_account_information();
		break;
		
	case 'fetch_accounts_amount':
		fetch_accounts_amount();
		break;
		
	case 'fetch_children_account_information':
		fetch_children_account_information();
		break;
		
	case 'fetch_students_list_payments':
		fetch_students_list_payments();
		break;
	case 'saveStudentID':
		saveStudentID();
		break;
	}
}

function generate_school_year_raport(){
	//spojrz w todo nizej i niech sie zapisuje jakos na serwer w sensie ze jak raz byl generewonay to pobiera ten stary 
	//aktualnie nie wywoluje tego, problem z przekazaniem tego parametru dalej
}

function fetch_school_year_id_options(){
	session_start();
	require_once "connection.php";
	$conn = new MyDB();
	//TODO jakos trzeba sprawdzic czy ta klasa była juz w tym roku szkolnym bo class ma school_year_id ale jak zmieni sie rok to id clasy tez bedzie nowa klasa
	//mozde clasa wskaznik na poprzednia klase albo cos w tym stylu taki lancuszek sie zrobi 
	$school_year = $conn->query(sprintf("SELECT * FROM school_year"));
	$output ='<div class="col-md-6 offset-sm-3 form-group row"><label for="school_year_id" class="text-center col-form-label">Wybierz rok szkolny:</label><select name="school_year_id" class="form-control">';
	if (mysqli_num_rows($school_year) > 0)
	{
		while ($row = mysqli_fetch_array($school_year))
		{
			$output.="<option value=".$row["id"].">".$row["start_year"]. "/" . $row["end_year"]."</option>";
		}
	}
	echo $output."</select></div>";
}

function add_transfer(){
	session_start();
	require_once "connection.php";
	$conn = new MyDB();
	
	$class_idx = $conn->query(sprintf("SELECT id FROM class WHERE parent_id = (SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "')"));
	$class_idt = mysqli_fetch_array($class_idx);
	$class_id = $class_idt["id"];
	$type = $_POST["type"]; //1 - konto ->gotówka  //-1 gotówka -> konto
	$amount = doubleval($_POST["amount"]); //wysokość trzeba sprawdzić czy tyle kasy wgl jest 
	$account_type = $_POST["account_type"]; // 1 - klasowe //0 - konta dzieci
	
	if($account_type=="1"){//klasowe
		if($type=="1"){//konto->gotówka
			$balancex = $conn->query(sprintf("SELECT balance FROM class_account WHERE class_id = ".$class_id.";"));
			$balancet = mysqli_fetch_array($balancex);
			$balance=$balancet["balance"];
			if($balance>=$amount) //czy ma tyle kasy
			{
				$conn->query(sprintf("insert into transfer (cash,class_account,class_id) values (".$amount.",1,".$class_id.");"));
				$conn->query(sprintf("update class_account set balance=balance-".$amount." , cash=cash+".$amount." where class_id=".$class_id.";"));
			}
			else
			{
				$_SESSION["error_transfer"]='Nie masz tyle pieniądzy na koncie.';
			}
		}else{ //gotowka->konto
			$cashx = $conn->query(sprintf("SELECT cash FROM class_account WHERE class_id = ".$class_id.";"));
			$casht = mysqli_fetch_array($cashx);
			$cash=$casht["cash"];
			if($cash>=$amount) //czy ma tyle kasy
			{
				$amount=(-1)*$amount;
				$exit=$conn->query(sprintf("insert into transfer (cash,class_account,class_id) values (".$amount.",1,".$class_id.");")); 
				$conn->query(sprintf("update class_account set balance=balance-".$amount." , cash=cash+".$amount." where class_id=".$class_id.";"));
			}
			else
			{
				$_SESSION["error_transfer"]='Nie masz tyle pieniądzy w gotówce.';
			}
		}
	
	}else{//dzieci
		if($type=="1"){//konto->gotówka
			$kids_balancex =  $conn->query(sprintf("select sum(balance) as sum_balance from account join child on account.child_id=child.id where child.class_id = ".$class_id.";"));
			$kids_balancet = mysqli_fetch_array($kids_balancex);
			$kids_balance = $kids_balancet["sum_balance"];

			if($kids_balance>=$amount) //czy maja tyle kasy
			{
				$conn->query(sprintf("insert into transfer (cash,class_account,class_id) values (".$amount.",0,".$class_id.");"));
				
				$kids_with_balancex = $conn->query(sprintf("select account.id as id ,account.balance as balance from account join child on account.child_id=child.id where child.class_id = ".$class_id." and balance>0;"));
				if (mysqli_num_rows($kids_with_balancex) > 0)
				{
					while ($row = mysqli_fetch_array($kids_with_balancex) and $amount>0)
					{
						$min_amount = min($amount,$row["balance"]);
						$amount -= $min_amount;
						$conn->query(sprintf("update account set balance=balance-".$min_amount." , cash=cash+".$min_amount." where id=".$row["id"].";"));
					}
				}
			}
			else
			{
				$_SESSION["error_transfer"]='Nie ma tyle pieniądzy na kontach dzieci.';
			}
		}else{ //gotowka->konto
			$kids_cashx =  $conn->query(sprintf("select sum(cash) as sum_cash from account join child on account.child_id=child.id where child.class_id = ".$class_id.";"));
			$kids_casht = mysqli_fetch_array($kids_cashx);
			$kids_cash = $kids_casht["sum_cash"];
			if($kids_cash>=$amount) //czy ma tyle kasy
			{
				$temp_amount=(-1)*$amount;
				$conn->query(sprintf("insert into transfer (cash,class_account,class_id) values (".$temp_amount.",0,".$class_id.");"));
				
				$kids_with_cashx = $conn->query(sprintf("select account.id as id ,account.cash as cash from account join child on account.child_id=child.id where child.class_id = ".$class_id." and cash>0;"));
				if (mysqli_num_rows($kids_with_cashx) > 0)
				{
					while ($row = mysqli_fetch_array($kids_with_cashx) and $amount>0)
					{
						$min_amount = min($amount,$row["cash"]);
						$amount -= $min_amount;
						$conn->query(sprintf("update account set balance=balance+".$min_amount." , cash=cash-".$min_amount." where id=".$row["id"].";"));
					}
				}
			}
			else
			{
				$_SESSION["error_transfer"]='Dzieci nie mają tyle pieniądzy w gotówce.';
			}
		}
	}

	//echo $type .' '. $amount .' '. $account_type;
	header('Location: treasuer_menu/transfer.php');
}

function fetch_transfer_list(){
	session_start();
	require_once "connection.php";
	$conn = new MyDB();
	$class="Klasowy";
	$kids="Konta dzieci";
	$output = '';
	$result = $conn->query(sprintf("SELECT * FROM transfer WHERE class_id=(SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id='" . $_SESSION['userID'] . "') order by date desc"));
	$output.= '  
		<div class="table-responsive p-3">
           <table class="table table-striped table-bordered table-center">
		     <thead class="thead-dark">
                <tr>  
                     <th scope="col">Data</th> 
					 <th scope="col">Kwota</th>
					 <th scope="col">Rodzaj przelewu</th>
					 <th scope="col">Rachunek</th>
                </tr>
				<thead>';
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
			$output.= '  
			<tbody>		
                <tr>  
					<td>' . $row["date"] . '</td>		 
					<td>' .number_format($cash, 2, ".", "") .' zł </td>
					<td>' .$type. ' </td>
					<td>' .$account. ' </td>
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
function payForEventTmp()
{
	session_start();
	$_SESSION['eventToBePaid'] = $_POST["eventID"];
	$_SESSION['childToBePaid'] = $_POST["childID"];
	require_once "connection.php";
	$conn = new MyDB();
	$childx= $conn->query(sprintf("SELECT name,surname FROM child WHERE id =".$_POST['childID'] ));
	$child = mysqli_fetch_array($childx);
	$kid_accountx=$conn->query(sprintf("SELECT balance,cash FROM account WHERE child_id =".$_POST['childID'] ));
	$kid_account = mysqli_fetch_array($kid_accountx);
	
	 $output ='
	 <div class="table-responsive">
           <table class="table table-bordered">
		     <thead> 
                <tr>  
					<th colspan="2">Stan konta '.$child["name"].' '.$child["surname"].'</th>	
                </tr>
			<thead>
			<tbody> 
					<tr>  
						<th scope="row">Gotówka</th> 
						<td>' .$kid_account["cash"].'</td> 
					</tr>
					<tr>
						<th scope="row">Na koncie</th>
						<td>' .$kid_account["balance"].'</td> 
					</tr>  
				  <tbody> </table><div>';
	
	
	echo $output;
}
 
function set_selected_rowID()
{
	session_start();
	$_SESSION['selectedID'] = $_POST["id"];
}

function saveStudentID()
{
	session_start();
	$_SESSION['studentToDelete'] = $_POST["id"];
}
function payForEvent()
{
	session_start();
	require_once "connection.php";
	$conn = new MyDB();
	
	$kidx = $conn->query(sprintf("SELECT name,surname FROM child WHERE id =".$_SESSION['childToBePaid'] ));
	$kid = mysqli_fetch_array($kidx);
	
	
	$pricex = $conn->query(sprintf("SELECT price FROM event WHERE id = ".$_SESSION['eventToBePaid'] ));
	$x = mysqli_fetch_array($pricex);
	$price = $x["price"];

	$alreadyPaidx = $conn->query(sprintf("SELECT amount_paid FROM participation WHERE event_id = ".$_SESSION['eventToBePaid']." AND child_id =".$_SESSION['childToBePaid'] ));
	$y = mysqli_fetch_array($alreadyPaidx);
	$alreadyPaid = $y["amount_paid"];	
	
	$wantPay = $_POST["amount"];
	$leftToPay = $price - $alreadyPaid;
	if($wantPay>$leftToPay){
		$wantPay = $leftToPay;
	}
	$payAll=0;
	if(isset($_POST['payAll'])){
		$payAll = 1;
	}
	else{
		$payAll = 0;
	}
	$willBePaidBalance=0;
	$willBePaidCash=0;
	$accountBalancex = $conn->query(sprintf("SELECT balance,cash FROM account WHERE child_id = ".$_SESSION['childToBePaid']));
	$z = mysqli_fetch_array($accountBalancex);
	$accountBalance = $z["balance"];	
	$accountCash = $z["cash"];
	$willBePaid =0;
	$sumKidMoney = doubleval($accountBalance) + doubleval($accountCash);
	
	if($payAll == 1){ //chce opłacić wszystko 
		if($leftToPay>$sumKidMoney){ 
			$_SESSION["error_pay_event"]=$kid["name"].' '.$kid["surname"].' nie ma wystarczającej ilości pieniędzy';
		}	
		else{
		 // cała gotówka reszta z konta
		 if($accountCash>$leftToPay){
			$willBePaidCash = $leftToPay;
			$willBePaidBalance=0;
		 }else{
			$willBePaidCash = $accountCash;
			$leftToPay = doubleval($leftToPay) - doubleval($accountCash);
			$willBePaidBalance = $leftToPay;
		 }
		}
				
	}
	else{ //jeżeli wpisałam kwoty
		if($wantPay>$sumKidMoney){ 		
			$_SESSION["error_pay_event"]=$kid["name"].' '.$kid["surname"].' nie ma wystarczającej ilości pieniędzy';
		}
		else{
		 // cała gotówka reszta z konta
			if($wantPay >= $accountCash){
				$willBePaidCash = $accountCash;
				$leftToPay = doubleval($wantPay) - doubleval($accountCash);
				$willBePaidBalance = $leftToPay;
			}
			else{
				$willBePaidCash = $wantPay;
				$willBePaidBalance=0;
			}
		}
	}
	
	if($willBePaidBalance>0){
		$conn->query(sprintf("UPDATE account SET balance = balance - ".$willBePaidBalance." WHERE child_id=".$_SESSION['childToBePaid']));
		$conn->query(sprintf("UPDATE participation SET balance =balance+".$willBePaidBalance." WHERE child_id =".$_SESSION['childToBePaid']." AND event_id=".$_SESSION['eventToBePaid']));
	}
	if($willBePaidCash>0){
		$conn->query(sprintf("UPDATE account SET  cash = cash - ".$willBePaidCash." WHERE child_id=".$_SESSION['childToBePaid']));
		$conn->query(sprintf("UPDATE participation SET cash =cash+".$willBePaidCash." WHERE child_id =".$_SESSION['childToBePaid']." AND event_id=".$_SESSION['eventToBePaid']));
	}
	header('Location: treasuer_menu/eventDetails.php');
	
} 
/*
function student_class_acc_payment_details()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$sum = $conn->query(sprintf("SELECT SUM(amount) as s FROM class_account_payment WHERE child_id = " . $_POST['id']));
	$r = $sum->fetch_assoc();
	$amount_of_paid_money = $r["s"];
	$monthly_f = $conn->query(sprintf("SELECT monthly_fee as m FROM class_account WHERE class_id = (SELECT class_id FROM child WHERE id =" . $_POST['id'] . ")"));
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
*/




function students_balances_list()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$result = $conn->query(sprintf("SELECT * FROM child WHERE class_id=(SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"] ." and parent_id='" . $_SESSION['userID'] . "') order by surname, name"));
	$output.= '  
		<div class="table-responsive p-3">
           <table class="table table-striped table-bordered table-center">
		     <thead class="thead-dark">
                <tr>  
                     <th scope="col">Imię i Nazwisko</th> 
					 <th scope="col">Konto klasowe</th>
					 <th scope="col">Konto dziecka</th>
					<!-- <th scope="col">Szczegóły</th> -->
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$class_account_balanceTMP = $conn->query(sprintf("SELECT IFNULL(SUM(amount),0) AS x FROM class_account_payment WHERE school_year_id=".$_SESSION["school_year_id"] ." and child_id = ".$row["id"] ));
			$class_account_balance = mysqli_fetch_array($class_account_balanceTMP);
			$account_balanceTMP = $conn->query(sprintf("SELECT cash,balance  FROM account WHERE child_id = ".$row["id"] ));
			$account_balance = mysqli_fetch_array($account_balanceTMP);
			//TODO
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
			$monthly_fee = $conn->query(sprintf("SELECT monthly_fee AS fee FROM class_account WHERE class_id=(SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id='" . $_SESSION['userID'] . "') " ));
			$fee = mysqli_fetch_array($monthly_fee);
			$kid_cash_whole = doubleval($account_balance["cash"]) + doubleval($account_balance["balance"]);
			$output.= '  
			<tbody>		
                <tr>  
                     <td>' . $row["name"] . ' ' . $row["surname"] . '</td>  ';
			$child_class_account = '';
			$expected_value = intval($months["date"]) * doubleval($fee["fee"]); 
			$child_class_account = doubleval($class_account_balance["x"]) - $expected_value;
			$output.='		 <td>' .number_format($child_class_account, 2, ".", "") .' zł </td>
					 <td>' .number_format($kid_cash_whole, 2, ".", "") . ' zł</td>
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
	if ((empty($_POST['classBalance']) || $_POST['classBalance'] == '0' )&& 
	(empty($_POST['childBalance']) || $_POST['childBalance'] == '0'))
	{
		header('Location: treasuer_menu/payments.php');
		exit();
	}

	require_once "connection.php";

	$conn = new MyDB();
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
		if(isset($_POST['payment_type'])){
			$type = $_POST["payment_type"];
		}else{
			$type="cash"; //TODO domysle ustawienia w skarbniku
		}
		$child = $_SESSION['childWhoMakePayment'];

		$curr_balance = $conn->query(sprintf("SELECT balance as b ,id  FROM account WHERE child_id =" . $_SESSION['childWhoMakePayment']));
		$res_balance = mysqli_fetch_array($curr_balance);
		$currentBalance = $res_balance["b"];
		$accountID = $res_balance["id"];
		$curr_cash = $conn->query(sprintf("SELECT cash as c FROM account WHERE child_id =" . $_SESSION['childWhoMakePayment']));
		$res_cash = mysqli_fetch_array($curr_cash);
		$currentCash = $res_cash["c"];
	
		if($type=="cash" && $_POST['childBalance'] > 0)
		//if($_POST['childCash'] > 0)
		{
			$newBalance = $currentCash + $_POST['childBalance'];
			$conn->query(sprintf("INSERT INTO payment (account_id,amount,type) VALUES (" . $accountID . "," . $_POST['childBalance'] . ",'gotowka')"));
			$conn->query(sprintf("UPDATE account SET cash=" . $newBalance . " WHERE child_id =" . $_SESSION['childWhoMakePayment']));
			echo "Record updated successfully";
		}
		else if($type=="bank" && $_POST['childBalance'] > 0)
		{
			$newBalance = $currentBalance + $_POST['childBalance'];
			$conn->query(sprintf("INSERT INTO payment (account_id,amount,type) VALUES (" . $accountID . "," . $_POST['childBalance'] . ",'konto')"));
			$conn->query(sprintf("UPDATE account SET balance=" . $newBalance . " WHERE child_id =" . $_SESSION['childWhoMakePayment']));
			echo "Record updated successfully";
		}

		$class_acc_id = $conn->query(sprintf("SELECT id FROM class_account WHERE class_id = (SELECT class_id FROM child WHERE id =" . $_SESSION['childWhoMakePayment'] . ")"));
		$ress = $class_acc_id->fetch_assoc();
		$class_account_id = $ress["id"];

			// inserting payment to class account
		$conn->query(sprintf("INSERT INTO class_account_payment (amount,class_account_id, child_id,type) VALUES (" . $amountOfMoney . "," . $class_account_id . "," . $_SESSION['childWhoMakePayment'] . ",'" . $_POST['paymentType'] . "')"));
			
		$curr_balance = $conn->query(sprintf("SELECT balance as b,cash as c FROM class_account WHERE id =". $class_account_id));
		$res_balance = mysqli_fetch_array($curr_balance);
		$currentBalance = $res_balance["b"];
		$currentCash = $res_balance["c"];
			
		if($type=="cash" && $_POST['classBalance'] >0)
		{
			$newBalance = $currentCash + $_POST['classBalance'];
			$conn->query(sprintf("INSERT INTO class_account_payment (amount,type,class_account_id,child_id) VALUES (" . $_POST['classBalance'] . ",'gotowka'," . $class_account_id . ", " .$_SESSION['childWhoMakePayment'] .")"));
			$conn->query(sprintf("UPDATE class_account SET cash=" . $newBalance . " WHERE id =".$class_account_id));
			echo "Record updated successfully";
		}
		else if($type="bank" && $_POST['classBalance'] >0)
		{
			$newBalance = $currentBalance + $_POST['classBalance'];
			$conn->query(sprintf("INSERT INTO class_account_payment (amount,type,class_account_id,child_id) VALUES (" . $_POST['classBalance'] . ",'konto'," . $class_account_id . ", " .$_SESSION['childWhoMakePayment'] .")"));
			$conn->query(sprintf("UPDATE class_account SET balance=" . $newBalance . " WHERE id =".$class_account_id));
			echo "Record updated successfully";
		}
	}
	
	$conn->close();
	echo  '<script> location.replace("treasuer_menu/payments.php"); </script>';
	//header('Location: treasuer_menu/payments.php');
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

	$conn = new MyDB();
	$class_account_idx = $conn->query(sprintf("SELECT * FROM class_account WHERE class_id = (SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id= " . $_SESSION['userID'] . ")"));
	$clid = mysqli_fetch_array($class_account_idx);
	$class_account_id = $clid["id"];
	$cash = doubleval($clid["cash"]);
	$balance = doubleval($clid["balance"]);
	$all_money = doubleval($cash) + doubleval($balance);
	$price = doubleval($_POST["eventPriceCash"]);
	if(isset($_POST['payment_type'])){
		$type = $_POST["payment_type"];
	}else{
		$type="cash"; //TODO domysle ustawienia w skarbniku
	}
	if(($type == "cash") && ($cash> $price)){
		$conn->query(sprintf("INSERT INTO expense (name,price, class_account_id,type) VALUES ('" . $_POST["expenseName"] . "'," . $price . ", " . $class_account_id . ", 'gotowka')"));
		$cash = $cash - $price;
		$conn->query(sprintf("UPDATE class_account SET cash=" . $cash . " , balance = " . $balance . " WHERE id= " . $class_account_id ));
	}
	else if(($type=="bank") && ($balance>= $price)){
//bank
		$conn->query(sprintf("INSERT INTO expense (name,price, class_account_id,type) VALUES ('" . $_POST["expenseName"] . "'," . $price . ", " . $class_account_id . ", 'konto')"));
		$balance = $balance - $price; 
		$conn->query(sprintf("UPDATE class_account SET cash=" . $cash . " , balance = " . $balance . " WHERE id= " . $class_account_id ));
	}
		// KOMUNIKAT ZE DODANO POMYSLNIE
	
	else
	{
		$_SESSION['error_new_expense'] = "Nie masz wystarczającej ilości pieniędzy."; 
		header('Location: treasuer_menu/new_expenses.php');
		
	}
	$conn->close();
	header('Location: treasuer_menu/expenses.php');
	
}

function fetch_expenses_list()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$id = mysqli_fetch_array($tmpID);
	$_SESSION['userID'] = $id["id"]; //userID = treasuerID
	$result = $conn->query(sprintf("SELECT date, name, SUM(price) as price from expense WHERE class_account_id = (SELECT id FROM class_account WHERE class_id = (SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id= " . $_SESSION['userID'] . "))  group by name,date order by date desc"));
	$output.= '
	<div class="col-md-2  float-md-right p-3">
		<button type="button" onclick="window.open(\'new_expenses.php\',\'_blank\')" class="btn btn-default btn-block">Dodaj wydatek</button> 
		</div>
		<div class="table-responsive">
           <table class="table table-striped table-bordered table-center">
		     <thead class="thead-dark">
                <tr>  
					<th  scope="col">Data</th>
                    <th  scope="col">Nazwa</th>  
                    <th  scope="col">Cena</th> 
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$output.= '  
			<tbody>	
                <tr>  
					 <td >' . $row["date"] . '</td>
                     <td >' . $row["name"] . '</td>  
					 <td >' . $row["price"] . ' zł</td>
				</tr> 
			<tbody>				
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="3">Nie dodano jeszcze wydatków w tej klasie</td>  
                     </tr>';
	}


	$output.= '</table></div>
		 ';
	echo $output;
}
function fetch_class_account_information()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$id = mysqli_fetch_array($tmpID);
	$_SESSION['userID'] = $id["id"]; //userID = treasuerID

	$tmpbalance = $conn->query(sprintf("SELECT id, balance,cash,monthly_fee FROM class_account WHERE class_id = (SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id = " . $_SESSION['userID'] . " )"));
	$bal = mysqli_fetch_array($tmpbalance);
	$balance = $bal["balance"]; //ilość pieniędzy klasowych na koncie
	$cash = $bal["cash"]; //ilość pieniędzy klasowych w gotówce
	$monthly_fee = $bal["monthly_fee"];
	$class_account_id = $bal["id"];
	$class_money =  doubleval($balance) + doubleval($cash);

	$kids_account_balance = $conn->query(sprintf("SELECT SUM(balance) as balance , SUM(cash) as cash FROM account join child on (account.child_id = child.id) where child.class_id = (SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id = " . $_SESSION['userID'] . " )"));
	$kids_account_balance_all = mysqli_fetch_array($kids_account_balance);
	$class_kids_money = doubleval($kids_account_balance_all["balance"]) + doubleval($kids_account_balance_all["cash"]);
	$output.= '
	<div class="table-responsive p-3">
				<table class="table table-bordered">
				<tbody>
					<tr><td>Ilość pieniędzy zebranych na koncie klasowym</td><td class="col-right">'  . number_format($class_money, 2, ".", "") . ' zł</td></tr> 
					<tr><td>    w gotówce </td><td class="col-right">' . $cash . ' zł</td></tr> 
					<tr><td>    na koncie </td><td class="col-right">'  . $balance . ' zł</td></tr> 
				
					<tr><td>Wartość miesięcznej składki</td><td class="col-right">' .$monthly_fee. ' zł</td></tr> 
				<tbody>
				<table>
			</div>';
	echo $output;
}

function fetch_children_account_information()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$id = mysqli_fetch_array($tmpID);
	$_SESSION['userID'] = $id["id"]; //userID = treasuerID
	$kids_account_balance = $conn->query(sprintf("SELECT SUM(balance) as balance , SUM(cash) as cash FROM account join child on (account.child_id = child.id) where child.class_id = (SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id = " . $_SESSION['userID'] . " )"));
	$kids_account_balance_all = mysqli_fetch_array($kids_account_balance);
	$class_kids_money = doubleval($kids_account_balance_all["balance"]) + doubleval($kids_account_balance_all["cash"]);

	$output.= '
	<div class="table-responsive">
				<table class="table table-bordered">
				<tbody>
					<tr><td>Ilość pieniędzy na kontach dzieci:</td><td class="col-right">'  . 
					number_format($class_kids_money, 2, ".", "") . ' zł</td></tr> 
					<tr><td>    w gotówce </td><td class="col-right">' . $kids_account_balance_all["cash"] . ' zł</td></tr> 
					<tr><td>    na koncie </td><td class="col-right">'  . $kids_account_balance_all["balance"] . ' zł</td></tr> 
				
				<tbody>
				<table>
			</div> ';
	echo $output;
}


function fetch_accounts_amount()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$id = mysqli_fetch_array($tmpID);
	$_SESSION['userID'] = $id["id"]; //userID = treasuerID

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
	$output.= '
			<div class="table-responsive-sm table-aligin-center">
				<table class="table table-bordered">
				<tbody>
					<tr><td>Suma pieniędzy zebranych na koncie klasowym</td><td class="col-right">'  . number_format($class_money, 2, ".", "") . ' zł</td></tr>
					<tr><td>Suma pieniędzy na kontach dzieci</td><td class="col-right">'  . number_format($class_kids_money, 2, ".", "") . ' zł</td></tr> 
					<tr><td>Suma pieniądzy zebranych w gotówce</td><td class="col-right">' . number_format($whole_cash, 2, ".", ""). ' zł</td></tr> 
					<tr><td>Suma pieniądzy zebranych na koncie</td><td class="col-right">'  . number_format($whole_balance, 2, ".", "") . ' zł</td></tr> 
				
				<tbody>
				<table>
			</div> ';
	echo $output;
}
// helping function to save which event is edited. Used in handling button confirm

function saveEditEventID()
{
	session_start();
	$_SESSION['changeEventID'] = $_POST["id"];
}

function endEvent()
{
	session_start();

	require_once "connection.php";

	$conn = new MyDB();
	$currrentDate = date('Y-m-d');
	//$res = ($conn->query(sprintf("select * FROM event WHERE id = '" . $_SESSION['changeEventID'] . "'")))->fetch_assoc();
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
		
	}
	else
	{
		$result = $conn->query(sprintf("update event set completed='1' where id='" . $_SESSION['changeEventID'] . "'")); 
		$_SESSION['errorEndEvent'] = "Zbiórka została zamknięta"; 
	}
	header('Location: treasuer_menu/class_event_list.php');
	$conn->close();
}


function editEvent()
{
	session_start();
	//if (empty($_POST['newEventName']) && empty($_POST['newEventPrice']) && empty($_POST['newEventDate']))
	//{
		//header('Location: treasuer_menu/class_event_list.php');
		//exit();
	//}

	require_once "connection.php";

	$conn = new MyDB();
	$currrentDate = date('Y-m-d');
	$res = ($conn->query(sprintf("select * FROM event WHERE id = '" . $_SESSION['changeEventID'] . "'")))->fetch_assoc();
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
		
	}
	else
	{
		if ($res["date"] >= $currrentDate)
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
				if($newEventDate > $currrentDate){
					$newEventDate = htmlentities($newEventDate, ENT_QUOTES, "UTF-8");
					$result = $conn->query(sprintf("update event set date='%s' where id='" . $_SESSION['changeEventID'] . "'", mysqli_real_escape_string($conn, $newEventDate)));
				}else{
					$_SESSION['errorEditEvent'] = "Data musi być przyszła"; 
					//header('Location: treasuer_menu/class_event_list.php');
					echo  '<script> location.replace("treasuer_menu/class_event_list.php"); </script>';
				}
			}

		}
		else
		{
				$_SESSION['errorEditEvent'] = "Nie można edytować zakończonej zbiórki"; 
				echo  '<script> location.replace("treasuer_menu/class_event_list.php"); </script>';
				//header('Location: treasuer_menu/class_event_list.php');
		}
	}
	//header('Location: treasuer_menu/class_event_list.php');
	echo  '<script> location.replace("treasuer_menu/class_event_list.php"); </script>';
	$conn->close();
}

function deleteEvent()
{
	session_start();
	require_once "connection.php";
	$conn = new MyDB();
	$currrentDate = date('Y-m-d');
	$res = ($conn->query(sprintf("select * FROM event WHERE id = '" . $_SESSION["changeEventID"] . "'")))->fetch_assoc();

	// checkoig if i can delete event (cannot delete event witch previous date)

	if ($res["date"] > $currrentDate)
	{
		if ($res = $conn->query(sprintf("DELETE FROM event WHERE id = '" . $_SESSION["changeEventID"] . "'")))
		{
			$_SESSION['errorDeleteEvent'] = "Usunieto zbiorke"; 
			//	header('Location: treasuer_menu/class_event_list.php');
			//echo 'Pomyslnie usunięto zbiórkę.';
		}else{
			$_SESSION['errorDeleteEvent'] = "Nie usunieto zbiorke tez ". $_SESSION["changeEventID"] .$res; 
		}
	}else{
			$_SESSION['errorDeleteEvent'] = "Nie usunieto zbiorke"; 
	}
	echo  '<script> location.replace("treasuer_menu/class_event_list.php"); </script>';
}
function fetch_event_details()
{
	session_start();
	require_once "connection.php";
	$conn = new MyDB();
	$output = '';
	$result = ($conn->query(sprintf("select count(*) as total from participation where event_id ='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
	
	$resultAmount = ($conn->query(sprintf("select price, completed, name  from event where id ='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
	$totalAmount = $resultAmount["price"] * $result["total"];
	$name = $resultAmount["name"];
	$output.= "<h1>" . $name . "</h1>";
	$output.= "Liczba uczestników zbiórki: " . $result["total"];
	$resultAmountPaid = ($conn->query(sprintf("select sum(amount_paid) as totalPaid from participation where event_id='" . $_SESSION['selectedID'] . "' ")))->fetch_assoc();
	$totalAmountPaid = $resultAmountPaid["totalPaid"];
	$output.= "<br /> Całkowity koszt zbiórki: " . number_format($totalAmount, 2, ".", "") . " zł<br /> Suma wpłat uczestników: " . $totalAmountPaid . " zł";
	$output.= "<br /><br />";
	$result = $conn->query(sprintf("select ch.id as childID, ch.name as name , ch.surname as surname, p.amount_paid as amount_paid , (p.amount_paid+'" . $resultAmount["price"] . "') as idx from child ch, participation p where ch.id = p.child_id and p.event_id='" . $_SESSION['selectedID']. "' order by surname,idx asc"));
	$output.= ' 
      <div class="table-responsive">
		<table class="table table-striped table-bordered">
		    <thead class="thead-dark"> 
                <tr>  
					<!--<th scope="col">Id</th>-->
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
				$disabled = 'disabled';
			}else{
				$color = '';
				$disabled='';
			}
			if($resultAmount["completed"] == 0) {
			$output.= '  
			<tbody>
                <tr>  
                     <td ' . $color . '>' . $row["name"] . '</td>  
					 <td ' . $color . '>' . $row["surname"] . '</td>
					 <td ' . $color . '>' . $row["amount_paid"] . ' zł</td>
					 <td ' . $color . '>' . $resultAmount["price"] . ' zł</td>
					 <td ' . $color . '><button type="button" data-toggle="modal" data-target="#payForEventModal" data-id3="' . $row["childID"] . '" data-id4="' . $_SESSION['selectedID'] . '" class="btn_payForEvent btn btn-default" '.$disabled.'>Opłać</button></td>

				</tr>  
			<tbody>
			';
			}
			else
			{
			$output.= '  
			<tbody>
                <tr>  
                     <td ' . $color . '>' . $row["name"] . '</td>  
					 <td ' . $color . '>' . $row["surname"] . '</td>
					 <td ' . $color . '>' . $row["amount_paid"] . '</td>
					 <td ' . $color . '>' . $resultAmount["price"] . '</td>
					 <td ' . $color . '><button type="button" data-toggle="modal" data-target="#payForEventModal" data-id3="' . $row["childID"] . '" data-id4="' . $_SESSION['selectedID'] . '" class="btn_payForEvent btn btn-default " disabled>Opłać</button></td>

				</tr>  
			<tbody>';
			}
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="7">Nie dodano jeszcze zbiórek do tej klasy</td>  
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

	$conn = new MyDB();
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
	session_start();
	require_once "connection.php";

	$conn = new MyDB();

	// checking if student have all previous months paid

	$result = $conn->query(sprintf("SELECT IFNULL(SUM(amount),0) AS x FROM class_account_payment WHERE school_year_id=".$_SESSION["school_year_id"]." and child_id = " .$_SESSION["studentToDelete"])); //suma zapłacona przez studenta
	$res = mysqli_fetch_array($result);
	
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
	
	$fee_tmp = $conn->query(sprintf("SELECT monthly_fee AS fee FROM class_account WHERE class_id=(SELECT id FROM class WHERE parent_id='" . $_SESSION['userID'] . "') " ));
	$fee = mysqli_fetch_array($fee_tmp);
	$monthly_fee = $fee["monthly_fee"];
	$expected_value = intval($months["date"]) * doubleval($fee["fee"]); 
	$child_class_account = doubleval($res["x"]) - $expected_value;
	$x = "start";
	if ($child_class_account < 0)
	{
		$x = 'Nie można usunąć dziecka bo nie opłaciło wszystkich opłat ';
	}
	else
	{

		// deleting student

		if ($res = $conn->query(sprintf("DELETE FROM child WHERE id = '" . $_SESSION["studentToDelete"] . "'")))
		{
			$x = 'Pomyslnie usunięto ucznia';
			
		}
	}
	$_SESSION["info_delete_student"] =$x;
	header('Location: ./treasuer_menu/students.php');
}

function fetch_class_name()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$result = $conn->query(sprintf("SELECT name FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and  parent_id = (SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "')"));
	$res = mysqli_fetch_array($result);
	$output = "<h3>Konto klasy " . $res['name'] ."</h3>";
	echo $output;
}

function fetch_event_list()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$result = $conn->query(sprintf("select * from event where class_id=(select id from class where school_year_id=".$_SESSION["school_year_id"]." and parent_id='" . $_SESSION['userID'] . "') order by date desc"));
	$output.= '  
		<div class="col-md-2  float-md-right p-3" >
		<button type="button" onclick="window.open(\'addOnceEvent.php\',\'_self\')" class="btn btn-default btn-block">Dodaj zbiórke</button> 
		</div>
		<div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
					<th scope="col">Data</th>
                     <th scope="col">Nazwa</th> 
					 <th scope="col">Cena</th>
					 <th scope="col">Szczegóły</th>
					 <th scope="col">Edycja</th>
					 <th scope="col">Zakończ</th>
					 <th scope="col">Usuwanie</th> 
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			if($row["completed"]=='0'){
			
			$output.= ' 
			<tbody>				
                <tr>  
					<td >' . $row["date"] . '</td>
                     <td>' . $row["name"] . '</td>  
					 <td>' . $row["price"] . ' zł</td>
					 <td><button type="button"  data-id4="' . $row["id"] . '" class="btn_detailsEvent btn btn-default">Szczegóły</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#eventEditModal"  data-id4="' . $row["id"] . '" class="btn_editEvent btn btn-default">Edytuj</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#eventEndModal" data-id4="' . $row["id"] . '" class="btn_endEvent btn btn-default">Zakończ</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#eventDeleteModal" data-id4="' . $row["id"] . '" class="btn_deleteEvent btn btn-default">Usuń zbiórkę</button></td>
				</tr>
			<tbody>				
           ';
		}else{
			$color = 'bgcolor=\"#99e699\"';
			$output.= ' 
			<tbody>				
                <tr>  
					<td '.$color.'>' . $row["date"] . '</td>
                     <td '.$color.'>' . $row["name"] . '</td>  
					 <td '.$color.'>' . $row["price"] . ' zł</td>
					 <td '.$color.'><button type="button"  data-id4="' . $row["id"] . '" class="btn_detailsEvent btn btn-default" >Szczegóły</button></td>
					 <td '.$color.'><button type="button" data-toggle="modal" data-target="#eventEditModal"  data-id4="' . $row["id"] . '" class="btn_editEvent btn btn-default" disabled>Edytuj</button></td>
					 <td '.$color.'><button type="button" data-toggle="modal" data-target="#eventEndModal" data-id4="' . $row["id"] . '" class="btn_endEvent btn btn-default"  disabled>Zakończ</button></td>
					 <td '.$color.'><button type="button" data-toggle="modal" data-target="#eventDeleteModal" data-id4="' . $row["id"] . '" class="btn_deleteEvent btn btn-default" disabled>Usuń zbiórkę</button></td>
				</tr>
			<tbody>				
           ';
		}
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="7">Nie dodano jeszcze zbiórek do tej klasy</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}



function fetch_students_list_payments()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$id = mysqli_fetch_array($tmpID);
	$_SESSION['userID'] = $id["id"];
	$result = $conn->query(sprintf("SELECT id,name,surname from child WHERE class_id = (SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and  parent_id = " . $_SESSION['userID'] . ") order by surname, name"));
	$output.= '  
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
                     <th scope="col">Imię i Nazwisko</th> 
					 <th scope="col">Wpłać</th>
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{

			$output.= '  
			<tbody>	
                <tr>  
                     <td>' . $row["name"] . ' '. $row["surname"] .'</td>  
					 <td><button type="button" data-toggle="modal" data-target="#makePaymentModal" data-id3="' . $row["id"] . '" class="btn_makePayment btn btn-default">Wpłać</button></td>
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


function fetch_students_list()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$tmpID = $conn->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$id = mysqli_fetch_array($tmpID);
	$_SESSION['userID'] = $id["id"];
	$result = $conn->query(sprintf("SELECT * from child WHERE class_id = (SELECT id FROM class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id = " . $_SESSION['userID'] . ")order by surname, name"));
	$output.= '  
		<div class="col-md-2  float-md-right p-3" >
		<button type="button" onclick="window.open(\'addStudent.php\',\'_self\')" class="btn btn-default btn-block">Dodaj ucznia do klasy</button> 
		</div>
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
                     <th scope="col">Imię i nazwisko dziecka</th>
					 <th scope="col">Data urodzenia</th>
					 <th scope="col">Imię i nazwisko rodzica</th>
					 <th scope="col">Mail rodzica</th>
					 <th scope="col">Zmień maila rodzica</th>
					 <th scope="col">Usuń ucznia</th>
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$parentTMP = $conn->query(sprintf("SELECT * FROM parent WHERE id = (SELECT parent_id FROM child WHERE id = " . $row["id"] . ")"));
			$parent = mysqli_fetch_array($parentTMP);
			$output.= '  
			<tbody>	
                <tr>  
                     <td>' . $row["name"] . ' ' . $row["surname"] . '</td>
					 <td>' . $row["date_of_birth"] . '</td>
					 <td>' . $parent["name"] . ' ' . $parent["surname"] . '</td>
					 <td><a href="mailto:' . $parent["email"] . '">' . $parent["email"] . '</a>
					 </td>
					 <td><button type="button" data-toggle="modal" data-target="#changeParMailModal" data-id3="' . $row["id"] . '" class="btn_pMailChange btn btn-default">Zmień maila</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#eventDeleteModal"  data-id3="' . $row["id"] . '" class="btn_deleteStudent btn btn-default">Usuń</button></td>
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

	$conn = new MyDB();
	$output = '';
	$result = $conn->query(sprintf("SELECT * FROM parent WHERE id =" . $_SESSION['userID']));
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

function fetch_monthly_fee()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$login = $_SESSION['user'];
	$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	$result = $conn->query(sprintf("SELECT * FROM class_account WHERE class_id=(SELECT id from class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id = (SELECT id FROM parent WHERE email = '%s'))", mysqli_real_escape_string($conn, $login)));
	$res = mysqli_fetch_array($result);
	echo '<p>Aktualna miesięczna składka: ' . $res["monthly_fee"] . '</p>';
}

function fetch_bank_account_number()
{
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	$login = $_SESSION['user'];
	$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	$result = $conn->query(sprintf("SELECT bank_account_number FROM class WHERE parent_id= (SELECT id FROM parent WHERE email = '%s')", mysqli_real_escape_string($conn, $login)));
	$res = mysqli_fetch_array($result);
	//format for account number
	$parts = array(0 => 2, 2 => 4, 6 => 4, 10 => 4, 14 => 4, 18 => 4, 22 => 4);
	$newNumber = '';
	foreach ($parts as $key => $val)
	{
	  $newNumber .= substr($res["bank_account_number"], $key, $val) . ' ';
	}
	echo '<p>Aktualne numery konta bankowego: ' . trim($newNumber) . '</p>';
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

	$conn = new MyDB();
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
		$result = $conn->query(sprintf("UPDATE class_account SET monthly_fee='%s' WHERE class_id=(SELECT id from class WHERE school_year_id=".$_SESSION["school_year_id"]." and parent_id = (SELECT id FROM parent WHERE email = '%s'))", mysqli_real_escape_string($conn, $newMonthlyFee), mysqli_real_escape_string($conn, $login)));
	}

	$conn->close();
	header('Location: treasuer_menu/settings.php');
}

function changeBankAccount()
{
	session_start();
	if (empty($_POST['newAccountNumber']) || $_POST['newAccountNumber'] == '0')
	{
		header('Location: treasuer_menu/settings.php');
		exit();
	}

	require_once "connection.php";

	$conn = new MyDB();
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
		$newAccountNumber = $_POST['newAccountNumber'];
		$newAccountNumber = htmlentities($newAccountNumber, ENT_QUOTES, "UTF-8");
		$newAccountNumber = preg_replace('/\s+/', '', $newAccountNumber);
		$login = $_SESSION['user'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$result = $conn->query(sprintf("UPDATE class SET bank_account_number='%s' WHERE parent_id=(SELECT id FROM parent WHERE email = '%s')", mysqli_real_escape_string($conn, $newAccountNumber), mysqli_real_escape_string($conn, $login)));
	}
	echo $newAccountNumber;
	echo $login;
	$conn->close();
	header('Location: treasuer_menu/settings.php');
}

function valid_pass($candidate) {
	$r1='/[A-Z]/';  //Uppercase
	$r2='/[a-z]/';  //lowercase
	$r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  //special chars
	$r4='/[0-9]/';  //numbers

	if(preg_match_all($r1,$candidate, $o)<1) return FALSE;

	if(preg_match_all($r2,$candidate, $o)<1) return FALSE;

	if(preg_match_all($r3,$candidate, $o)<1) return FALSE;

	if(preg_match_all($r4,$candidate, $o)<1) return FALSE;

	if(strlen($candidate)<8) return FALSE;

	return TRUE;
 }

function changeOldPassword()
{
	session_start();
	if (empty($_POST['newPassword']) || $_POST['newPassword'] == '0' || empty($_POST['oldPassword']) || $_POST['oldPassword'] == '0' || empty($_POST['reNewPassword']) || $_POST['reNewPassword'] == '0')
	{
		header('Location: treasuer_menu/settings.php');
		exit();
	}

	require_once "connection.php";

	$conn = new MyDB();
	if ($conn->connect_errno != 0)
	{
		echo "Bląd: " . $conn->connect_errno;
	}
	else
	{
		$result = @$conn->query(sprintf("SELECT * FROM username WHERE login='%s'", mysqli_real_escape_string($conn, $_SESSION['user'])));
		$res = $result->fetch_assoc();
		if (password_verify($_POST['oldPassword'], $res['hashedPassword']))
		{
			$userCount = $result->num_rows;
			if ($userCount > 0)
			{
				$newPassword = $_POST['newPassword'];
				$newPassword = htmlentities($newPassword, ENT_QUOTES, "UTF-8");
				$reNewPassword = $_POST['reNewPassword'];
				$reNewPassword = htmlentities($reNewPassword, ENT_QUOTES, "UTF-8");
				if($newPassword == $reNewPassword)
				{
					if (valid_pass($newPassword))
					{
						$login = $_SESSION['user'];
						$login = htmlentities($login, ENT_QUOTES, "UTF-8");
						$newHash = password_hash($newPassword, PASSWORD_BCRYPT);
						$result = $conn->query(sprintf("UPDATE username SET password='%s', hashedPassword='$newHash', first_login=FALSE WHERE login='%s'", mysqli_real_escape_string($conn, $newPassword) , mysqli_real_escape_string($conn, $login)));
						$_SESSION['errorChangePassword'] ='Hasło zostało zmienione.';
					}
					else
					{
						$_SESSION['errorChangePassword'] = 'Zmiana hasła nie powiodła się. Hasło powinno zawierać minimum 8 znaków, w tym co najmniej jedną małą literę, wielką literę, cyfrę i symbol';
					}
				}
				else
				{
					$_SESSION['errorChangePassword'] = 'Nowe hasło i powtórzone nowe hasło muszą być takie same!';
					header('Location: treasuer_menu/settings.php');
					echo "Nowe hasło i powtórzone nowe hasło muszą być takie same";
					//nowe i powtorzone musza byc takie same
				}
			}
		}
		else
		{
			$_SESSION['errorChangePassword'] = 'Stare hasło jest błędne';
			header('Location: treasuer_menu/settings.php');
			echo "Stare hasło jest błędne";
			//złe stare hasło
		}
	}
	$conn->close();
	header('Location: treasuer_menu/settings.php');  // nie trzeba sie przelogowac przy zmiane hasła
}

function changePassword()
{
	session_start();
	if (empty($_POST['newPassword']) || $_POST['newPassword'] == '0' )
	{
		header('Location: treasuer_menu/settings.php');
		exit();
	}

	require_once "connection.php";

	$conn = new MyDB();
	if ($conn->connect_errno != 0)
	{
		echo "Bląd: " . $conn->connect_errno;
	}
	else
	{
		$newPassword = $_POST['newPassword'];
		$newPassword = htmlentities($newPassword, ENT_QUOTES, "UTF-8");
		$reNewPassword = $_POST['reNewPassword'];
		$reNewPassword = htmlentities($reNewPassword, ENT_QUOTES, "UTF-8");
		if($newPassword == $reNewPassword)
		{
				if (valid_pass($newPassword))
				{
					$login = $_SESSION['user'];
					$login = htmlentities($login, ENT_QUOTES, "UTF-8");
					$newHash = password_hash($newPassword, PASSWORD_BCRYPT);
					$result = $conn->query(sprintf("UPDATE username SET password='%s', hashedPassword='$newHash', first_login=FALSE WHERE login='%s'", mysqli_real_escape_string($conn, $newPassword) , mysqli_real_escape_string($conn, $login)));
					$_SESSION['infoChangePasswordFirst'] = 'Dokonano prawidłowej zmiany hasła. <br> Zaloguj się ponownie na swoje konto.';
					header('Location: logout.php');
				}
				else
				{
					$_SESSION['errorChangePasswordFirst'] = 'Zmiana hasła nie powiodła się. Hasło nie spełnia wymagań.';
					header('Location: menu_treasurer.php');
				}
		}
		else
		{
			$_SESSION['errorChangePasswordFirst'] = 'Zmiana hasła nie powiodła się. Podane hasła nie są identyczne.';
			header('Location: menu_treasurer.php');
		}
	}

	$conn->close();
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

	$conn = new MyDB();
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
		$resultclassID = ($conn->query(sprintf("select * from class where school_year_id=".$_SESSION["school_year_id"]." and parent_id='" . $_SESSION['userID'] . "'")))->fetch_assoc();
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
			echo "Nie udalo sie dodac wydarzenia";
		}
	}

	$conn->close();
	header('Location: treasuer_menu/class_event_list.php');
}

function randomPassword()
{
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!?@#$%^*-_+';
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

	$conn = new MyDB();

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

		$classID1 = $conn->query(sprintf("SELECT id FROM class where school_year_id=".$_SESSION["school_year_id"]." and parent_id=(SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "' )"));
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
		header('Location: treasuer_menu/students.php');
	}
}

?>