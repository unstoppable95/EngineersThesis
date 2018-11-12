

<?php

if ((isset($_POST['changePassword'])))
{
	changePassword();
}

if ((isset($_POST['function2call'])))
{
	$function2call = $_POST['function2call'];
	switch ($function2call)
	{
	case 'fetch':
		fetch();
		break;

	case 'delete':
		deleteFromDB();
		break;

	case 'fetch_child_list':
		fetch_child_list();
		break;

	case 'choose':
		choose();
		break;

	case 'parent_data':
		fetch_parent_data();
		break;

	case 'fetch_balance':
		fetch_balance();
		break;

	case 'fetch_payment_history':
		fetch_payment_history();
		break;

	case 'fetch_child_name':
		fetch_child_name();
		break;

	case 'fetch_class_expenses_list':
		fetch_class_expenses_list();
		break;

	case 'fetch_class_account_data':
		fetch_class_account_data();
		break;

	case 'fetch_class_account_payment_history':
		fetch_class_account_payment_history();
		break;

	case 'fetch_paid_months':
		fetch_paid_months();
		break;
	}
}

if ((isset($_POST['RequiredNewPasswordAccept'])))
{
	changePassword();
}


function fetch_paid_months()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$sum = $connect->query(sprintf("SELECT SUM(amount) as s FROM class_account_payment WHERE child_id = " . $_SESSION['choosenChild']));
	$r = $sum->fetch_assoc();
	$amount_of_paid_money = $r["s"];
	$monthly_f = $connect->query(sprintf("SELECT monthly_fee as m FROM class_account WHERE class_id = (SELECT class_id FROM child WHERE id =" . $_SESSION['choosenChild'] . ")"));
	$x = $monthly_f->fetch_assoc();
	$monthly_fee = $x["m"];
	$output.= '  
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
                     <th scope="col"">Miesiąc</th> 
					 <th scope="col">Wpłacona kwota</th>
                </tr>';
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

			$output.= '  
			<tbody>
						<tr>  
							<td bgcolor=' . $fild_color . ' >' . $months[$i] . '</td> 
							<td bgcolor=' . $fild_color . '  >' . $topay . ' zł</td> 
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

	$output.= '</table>  
			  </div>';
	echo $output;
}

function fetch_class_account_payment_history()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = $connect->query(sprintf("SELECT * FROM class_account_payment WHERE child_id = " . $_SESSION['choosenChild'] . " ORDER BY date"));
	$output.= '  
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
                     <th scope="col">Kwota</th> 
					 <th scope="col">Data</th>
                     <th scope="col">Sposób płatności</th>
                </tr>
			<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$output.= ' 
			<tbody>			
                <tr>  
                    <td>' . $row["amount"] . ' zł</td> 
					<td>' . $row["date"] . '</td>  					 
                    <td>' . $row["type"] . '</td>
                </tr>  
			<tbody>	
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="4">Nie znaleziono wpłat</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}

function fetch_class_expenses_list()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = $connect->query(sprintf("SELECT * FROM expense WHERE class_account_id =(SELECT id FROM class_account WHERE class_id = (SELECT class_id FROM child WHERE id =" . $_SESSION['choosenChild'] . ")) ORDER BY date"));
	$output.= '  
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark">  
                <tr>  
                     <th scope="col">Nazwa</th> 
					 <th scope="col">Cena</th>
                     <th scope="col">Data</th>
                </tr>
			<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$output.= '  
				<tbody>	
						<tr>  
							 <td>' . $row["name"] . '</td> 
							<td>' . $row["price"] . ' zł</td>  					 
							 <td>' . $row["date"] . '</td>
						</tr>  
				<tbody>	
				   ';
		}
	}
	else
	{
		$output.= '<tr>  
								  <td colspan="4">Nie znaleziono wydatków</td>  
							 </tr>';
	}

	$output.= '</table>  
			  </div>';
	echo $output;
}

function fetch_class_account_data()
{
	session_start();
	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	$sum = $conn->query(sprintf("SELECT SUM(amount) as s FROM class_account_payment WHERE child_id = " . $_SESSION['choosenChild']));
	$r = $sum->fetch_assoc();
	$amount_of_paid_money = $r["s"];
	$class_acc_id = $conn->query(sprintf("SELECT id FROM class_account WHERE class_id = (SELECT class_id FROM child WHERE id =" . $_SESSION['choosenChild'] . ")"));
	$ress = $class_acc_id->fetch_assoc();
	$class_account_id = $ress["id"];
	$curr_class_balance = $conn->query(sprintf("SELECT balance FROM class_account WHERE id =" . $class_account_id));
	$res = mysqli_fetch_array($curr_class_balance);
	$current_class_balance = $res["balance"];
	$output = '<p  class="text-center">Ilość pieniędzy wpłaconych na konto klasowe dziecka: ' . $amount_of_paid_money . ' zł</p>
			<p  class="text-center">Suma pieniędzy na koncie klasowym całej klasy: ' . $current_class_balance . ' zł</p>';
	echo $output;
}

function fetch_child_name()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	if (isset($_SESSION["choosenChild"]))
	{
		$result = $connect->query(sprintf("SELECT * FROM child WHERE id=" . $_SESSION['choosenChild']));
		if (mysqli_num_rows($result) > 0)
		{
			$row = mysqli_fetch_array($result);
			$class = $connect->query(sprintf("SELECT * FROM class WHERE id=" . $row["class_id"]));
			$classrow = mysqli_fetch_array($class);
			$output = '<h5> Bieżące płatności dziecka: ' . $row["name"] . ' ' . $row["surname"] . ', klasa: ' . $classrow["name"] . ' </h5>';
		}
	}
	else
	{
		$output = "Wystapil blad w poborze dzieci";
	}

	echo $output;
}

function fetch_payment_history()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = $connect->query(sprintf("SELECT * FROM payment WHERE account_id =(SELECT id FROM account WHERE child_id = " . $_SESSION['choosenChild'] . ") ORDER BY date"));
	$output.= '  
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
                     <th scope="col">Kwota</th> 
					 <th scope="col">Data</th>
                     <th scope="col">Sposób płatności</th>
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$output.= '  
			<tbody>
                <tr>  
                     <td>' . $row["amount"] . ' zł</td> 
					<td>' . $row["date"] . '</td>  					 
                     <td>' . $row["type"] . '</td>
                </tr>  
			<tbody>
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="4">Nie znaleziono wpłat</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}


function changePassword()
{
	session_start();
	if (empty($_POST['newPassword']) || $_POST['newPassword'] == '0')
	{
		header('Location: parent_menu/p_settings.php');
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

function fetch()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	if (isset($_SESSION["choosenChild"]))
	{
		$result = $connect->query(sprintf("SELECT e.id,e.name,e.price,e.date, (e.price-p.amount_paid) as sortx from event e join participation p on e.id = p.event_id and p.child_id=" . $_SESSION['choosenChild'] . " WHERE e.id IN (SELECT event_id FROM participation WHERE child_id =" . $_SESSION['choosenChild'] . ") order by sortx desc"));
		$output.= '  
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark"> 
                <tr>  
                  <!--   <th width="5%">Id</th>  -->
                     <th scope="col">Nazwa</th> 
					 <th scope="col">Wpłacono</th>
                     <th scope="col">Cena</th>  
                     <th scope="col">Data</th>  
					 <th scope="col">Wypisz dziecko</th>
                </tr>
			<thead>';
		if (mysqli_num_rows($result) > 0)
		{
			while ($row = mysqli_fetch_array($result))
			{
				if ($row["sortx"] == 0)
				{
					$color = ' bgcolor = #66ff66 ';
				}
				else
				{
					$color = '';
				}

				$payedTmp = $connect->query(sprintf("SELECT * FROM participation WHERE child_id =" . $_SESSION['choosenChild'] . " AND event_id=" . $row["id"]));
				$res = mysqli_fetch_array($payedTmp);
				$paid = $res["amount_paid"];
				$output.= ' 
			<tbody>					
                <tr>  
                <!--    <td' . $color . '>' . $row["id"] . '</td>  -->
                    <td ' . $color . '>' . $row["name"] . '</td> 
					<td ' . $color . '>' . $paid . '</td>  					 
                    <td ' . $color . '>' . $row["price"] . ' zł</td>  
                    <td ' . $color . '>' . $row["date"] . '</td>
					<td ' . $color . '><button type="button"data-id3="' . $row["id"] . '" class="btn_delete btn btn-default">Wypisz</button></td>
                </tr>  
			<tbody>
           ';
			}
		}
		else
		{
			$output.= '<tr>  
                          <td colspan="4">Nie znaleziono wydarzeń</td>  
                     </tr>';
		}

		$output.= '</table>  
      </div>';
	}
	else
	{
		$output = "Wystapil blad w wyswietlaniu";
	}

	echo $output;
}

function deleteFromDB()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);

	// adding paid money to account

	$amount_paid_tmp = $connect->query(sprintf("SELECT amount_paid FROM participation where event_id='" . $_POST["id"] . "' AND child_id = " . $_SESSION['choosenChild']));
	$x = mysqli_fetch_array($amount_paid_tmp);
	$amount_paid = $x["amount_paid"];
	$curr = $connect->query(sprintf("SELECT balance as b FROM account WHERE child_id =" . $_SESSION['choosenChild']));
	$res = mysqli_fetch_array($curr);
	$currentBalance = $res["b"];
	$newBalance = $currentBalance + $amount_paid;
	$connect->query(sprintf("UPDATE account SET balance='%s' WHERE child_id = '%s'", mysqli_real_escape_string($connect, $newBalance) , mysqli_real_escape_string($connect, $_SESSION['choosenChild'])));

	if ($res = $connect->query(sprintf("DELETE FROM participation where event_id='" . $_POST["id"] . "' AND child_id = " . $_SESSION['choosenChild'])))
	{
		echo 'Pomyslnie wypisano dziecko';
	}
	
}

function fetch_child_list()
{
	session_start();
	$_SESSION['firstDisplayParent'] = false;
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$x = $connect->query(sprintf("SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "'"));
	$res = mysqli_fetch_array($x);
	$_SESSION['userID'] = $res["id"];
	$result = $connect->query(sprintf("SELECT * from child WHERE parent_id = " . $_SESSION['userID']));
	$output.= '  
      <div class="table-responsive">
		<table class="table table-striped table-bordered">
		    <thead class="thead-dark"> 
                <tr>  
                   <!--  <th width="5%">Id</th>  -->
                     <th scope="col">Imię</th> 
					 <th scope="col">Nazwisko</th>
					 <th scope="col">Data urodzenia</th>
					 <th scope="col">Wybierz</th>
                </tr>
			<thead>';
	if ($result && mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$output.= '  
			<tbody>
                <tr>  
                    <!-- <td>' . $row["id"] . '</td>  -->
                     <td>' . $row["name"] . '</td>  
					 <td>' . $row["surname"] . '</td>
					 <td>' . $row["date_of_birth"] . '</td>
					 <td><button type="button" data-id3="' . $row["id"] . '" class="btn_choose btn btn-default">Wybierz</button></td>  
				</tr>
			<tbody>
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="4">Nie posiadasz w szkole żadnych dzieci</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}

function fetch_parent_data()
{
	session_start();
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = $connect->query(sprintf("SELECT * FROM parent WHERE id =" . $_SESSION['userID']));
	$res = mysqli_fetch_array($result);
	$output.= '	 <div class="table-responsive">
		<table class="table table-bordered">
		<tbody>
		<tr><td>Imię: </td><td>' . $res["name"] . '</td></tr> 
		<tr><td>Nazwisko: </td><td>' . $res["surname"] . '</td></tr> 
		<tr><td>Email: </td><td>' . $res["email"] . '</td></tr> 
	<tbody>
	<table>
	</div>
	<table>
		   ';
	echo $output;
}

function choose()
{
	session_start();
	$_SESSION['choosenChild'] = $_POST["id"];
	$ale = "Wybrales dziecko o id: " . $_SESSION['choosenChild'];
	echo $ale;
}

function fetch_balance()
{
	session_start();
	if (isset($_SESSION["choosenChild"]))
	{
		require_once "connection.php";

		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';
		$result = $connect->query(sprintf("SELECT balance FROM account WHERE child_id =" . $_SESSION['choosenChild']));
		$res = mysqli_fetch_array($result);
		$output.='<p class="text-center">Stan konta dziecka: '. $res["balance"] .'</p>';
	}
	else
	{
		$output = "Wystapil błąd w poborze danych";
	}

	echo $output;
}

?>

