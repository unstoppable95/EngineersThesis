<?php

if ((isset($_POST['changePassword'])))
	{
	changePassword();
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
	

	
	if ((isset($_POST['function2call'])))
	{
		$function2call = $_POST['function2call'];	
		switch($function2call) {
        case 'students_list' : fetch_students_list();break;
        case 'treasuer_data' : fetch_treasurer_data(); break;
		case 'fetch_event_list': fetch_event_list(); break;
		case 'fetch_class_name': fetch_class_name(); break;
		case 'deleteStudent': deleteStudent(); break;
		case 'btn_pMailChange': btn_pMailChange(); break;
		case 'fetch_event_details': fetch_event_details(); break;
		case 'deleteEvent': deleteEvent(); break;
		case 'saveEditEvent': saveEditEventID(); break;
		case 'fetch_expenses_list': fetch_expenses_list(); break;
		
	}

	}
	

function addExpense(){
	session_start();
	require_once "connection.php";
	$connect = new mysqli($servername, $username, $password, $dbName);
	
	$class_account_idx =  $connect->query(sprintf("SELECT * FROM class_account WHERE class_id = (SELECT id FROM class WHERE parent_id= ".$_SESSION['userID'].")"));
	$clid = mysqli_fetch_array($class_account_idx);
	$class_account_id= $clid["id"];
	$excepted_budget = $clid["expected_budget"];
	
	$curr_exp =  $connect->query(sprintf("SELECT SUM(price) FROM expense WHERE class_account_id = ".$class_account_id));
	$x = mysqli_fetch_array($class_account_idx);
	$currentExpenses= $x["s"];
	
	
	if($_POST["expensePrice"] + $currentExpenses <= $excepted_budget){
		$connect->query(sprintf("INSERT INTO expense (name,price, class_account_id) VALUES ('".$_POST["expenseName"]."',".$_POST["expensePrice"].", ".$class_account_id.")"));
		
		//KOMUNIKAT ZE DODANO POMYSLNIE
		
		echo "<script>
	alert('Dodano pomyślnie!');
	window.location.href='treasuer_menu/expenses.php';
	</script>";
	}
	else{
		//KOMUNIKAT ZE BUDZET JEST PRZEKROCZONY I NIE MOZNA DODAC
		echo "<script>
	alert('Przekroczono budżet -> nie można już dodać wydatków z klasowych!');
	window.location.href='treasuer_menu/expenses.php';
	</script>";
	}
	//header('Location: treasuer_menu/expenses.php');	
	
}
	
	
function fetch_expenses_list(){
		session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';  
		
		$tmpID = $connect->query(sprintf("SELECT id FROM parent WHERE email = '".$_SESSION['user']."'"));
		$id = mysqli_fetch_array($tmpID);
		$_SESSION['userID'] = $id["id"]; //userID = treasuerID
		$result=$connect->query(sprintf("SELECT * from expense WHERE class_account_id = (SELECT id FROM class_account WHERE class_id = (SELECT id FROM class WHERE parent_id= ".$_SESSION['userID']."))"));
		
		
 $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="33%">Nazwa</th>  
                     <th width="33%">Cena</th> 
					 <th width="34%">Data</th>
                </tr>'; 
				
				
 if(mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                     <td>'.$row["id"].'</td>  
                     <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["name"].'</td>  
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["price"].'</td>
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["date"].'</td>
					</tr>  
           ';  
      }  
 
 }  
 else  
 {  
      $output .= '<tr>  
                          <td colspan="4">Nie dodano jeszcze wydatków w tej klasie</td>  
                     </tr>';  
 }  
 
	 
	
	$tmpbalance = $connect->query(sprintf("SELECT id, balance, expected_budget FROM class_account WHERE class_id = (SELECT id FROM class WHERE parent_id = ".$_SESSION['userID']." )"));
		$bal = mysqli_fetch_array($tmpbalance);
		$balance = $bal["balance"];
		$exceptedBalance = $bal["expected_budget"];
		$class_account_id= $bal["id"];
	
	
		$curr_exp =  $connect->query(sprintf("SELECT SUM(price) as s FROM expense WHERE class_account_id = ".$class_account_id));
		$x = mysqli_fetch_array($curr_exp);
		$currentExpenses= $x["s"];
	
	$availableMoney = $exceptedBalance - $currentExpenses;

	 $output .= '</table>  
	 <h2> Stan konta klasowego </h2>

	 Ilość pieniędzy zebranyc na koncie klasowym: '.$balance.'
	 <br> Przewidywana cała kwota budżetu: '.$exceptedBalance.'
	 <br> Ilość pozostałego budżetu: '.$availableMoney.'
		  </div>';  
	 echo $output; 
 
	
}
	
	
function saveEditEventID(){
		session_start();
		$_SESSION['changeEventID']=$_POST["id"];
		
	}
	
function editEvent(){
	session_start();	
    if (empty($_POST['newEventName']) && empty($_POST['newEventPrice']) && empty($_POST['newEventDate'])) {
	   header('Location: menu_treasurer.php');
		exit();
    }
    require_once "connection.php";
    $conn = new mysqli($servername, $username, $password, $dbName);
    $currrentDate=date('Y-m-d');
	$res=($conn->query(sprintf("select * FROM event WHERE id = '".$_SESSION['changeEventID']."'")))->fetch_assoc();
	
	
    if ($conn->connect_errno != 0) {
        echo "Blad: " . $conn->connect_errno; 
    } else {
		
		if ($res["date"]>=$currrentDate && $_POST['newEventDate']>=$currrentDate){
			
		 if (!empty($_POST['newEventName'])){
			$newEventName = $_POST['newEventName'];
			$newEventName  = htmlentities($newEventName , ENT_QUOTES, "UTF-8");
			$result=$conn->query(sprintf("update event set name='%s' where id='".$_SESSION['changeEventID']."'", mysqli_real_escape_string($conn, $newEventName )));
		 }
		 
		  if (!empty($_POST['newEventPrice'])){
			$newEventPrice = $_POST['newEventPrice'];
			$newEventPrice  = htmlentities($newEventPrice , ENT_QUOTES, "UTF-8");
			$result=$conn->query(sprintf("update event set price='%s' where id='".$_SESSION['changeEventID']."'", mysqli_real_escape_string($conn, $newEventPrice )));
		 }
		 
		
		  if ( !empty($_POST['newEventDate'])){
			$newEventDate = $_POST['newEventDate'];
			$newEventDate  = htmlentities($newEventDate , ENT_QUOTES, "UTF-8");
			$result=$conn->query(sprintf("update event set date='%s' where id='".$_SESSION['changeEventID']."'", mysqli_real_escape_string($conn, $newEventDate )));
		 }	
			//echo 'Edycja pomyślna!';  
		
		
		
		echo "<script>
	alert('Edycja pomyślna!');
	window.location.href='menu_treasurer.php';
	</script>";

		
		}
		
		else {
				echo "<script>
	alert('Nie możesz edytować wydarzenia, które się odbyło!');
	window.location.href='menu_treasurer.php';
	</script>";

			
			//echo 'Nie możesz edytować eventu, który już się odbył!';  
		}

			
	}
	$conn->close();
	//header('Location: menu_treasurer.php');			
	}
	
	
	
function deleteEvent(){
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$currrentDate=date('Y-m-d');
		$res=($connect->query(sprintf("select * FROM event WHERE id = '".$_POST["id"]."'")))->fetch_assoc();
		
	
		if ($res["date"]>$currrentDate){
			if($res=$connect->query(sprintf("DELETE FROM event WHERE id = '".$_POST["id"]."'"))){
			 echo 'Pomyslnie usunięto event';  
			} 
		}
		else {
			echo 'Nie możesz usunąć eventu, który już się odbył!';  
		}
			
	}
	
function fetch_event_details(){
		session_start();	
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';  
		
		
		
		$result=($connect->query(sprintf("select count(*) as total from participation where event_id ='".$_POST["id"]."' ")))->fetch_assoc();
		$output .="Liczba uczestników eventu: ".$result["total"]."";
	
		
		$resultAmount=($connect->query(sprintf("select price from event where id ='".$_POST["id"]."' ")))->fetch_assoc();
		$totalAmount=$resultAmount["price"] * $result["total"];
		
		$resultAmountPaid=($connect->query(sprintf("select sum(amount_paid) as totalPaid from participation where event_id='".$_POST["id"]."' ")))->fetch_assoc();
		$totalAmountPaid=$resultAmountPaid["totalPaid"];
		
		$output .="<br> Całkowity koszt eventu: ".$totalAmount."<br> Suma wpłat uczestników: ".$totalAmountPaid."";
		$output .="<br><br>";
		
		
		$result=$connect->query(sprintf("select ch.name as name , ch.surname as surname, p.amount_paid as amount_paid from child ch, participation p where ch.id = p.child_id and p.event_id='".$_POST["id"]."'"));
		
		
		$output .= ' 
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="30%">Imie</th>  
                     <th width="40%">Nazwisko</th> 
				
					 <th width="15%">Kwota wpłacona</th>
					<th width="15%">Koszt</th>
					 
                </tr>'; 
				
				
 if(mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                  
                     <td class="name" data-id1="" contenteditable>'.$row["name"].'</td>  
					 <td class="name" data-id1="" contenteditable>'.$row["surname"].'</td>
					 <td class="name" data-id1="" contenteditable>'.$row["amount_paid"].'</td>
					  <td class="name" data-id1="" contenteditable>'.$resultAmount["price"].'</td>
				</tr>  
           ';  
      }  
 
 }  
 else  
 {  
      $output .= '<tr>  
                          <td colspan="4">Nie dodano jeszcze wydarzeń do tej klasy</td>  
                     </tr>';  
 }  
 
 $output .= '</table>  
      </div>';  
 echo $output;  
		
		
		
	
}	
	
function changeParentMail(){
	session_start();
	echo "<script>console.log( 'Zmieniasz maila rodzicowi o id dziecka:  " .$_SESSION['changeEmailChildID']. "' );</script>";
	
	  //session_start();
    if (empty($_POST['newParentMail']) || $_POST['newParentMail'] == '0') {
        header('Location: treasuer_menu/settings.php');
        exit();
    }
    require_once "connection.php";
    $conn = new mysqli($servername, $username, $password, $dbName);
    
    if ($conn->connect_errno != 0) {
        echo "Blad: " . $conn->connect_errno; 
    } else {
		$newParentEmail = $_POST['newParentMail'];
        $newParentEmail  = htmlentities($newParentEmail , ENT_QUOTES, "UTF-8");
		
		$result=$conn->query(sprintf("select * from parent where id=(select parent_id from child where id='".$_SESSION['changeEmailChildID']."')"));
		$details = $result->fetch_assoc();
		$oldParentEmail = $details['email'];
		$conn->query(sprintf("UPDATE parent SET email='%s' where id=(select parent_id from child where id='".$_SESSION['changeEmailChildID']."')", mysqli_real_escape_string($conn, $newParentEmail )));
		$conn->query(sprintf("UPDATE username set login='$newParentEmail' where login='$oldParentEmail'"));
		
	}
	
	  $conn->close();
	
    header('Location: treasuer_menu/settings.php');	
		

	
	
}	

function btn_pMailChange(){
	session_start();
	$_SESSION['changeEmailChildID']=$_POST["id"];
	echo  "<script>console.log( 'Id:  " .$_SESSION['changeEmailChildID']. "' );</script>";
	
}

	
	
function deleteStudent(){
		//session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		if($res=$connect->query(sprintf("DELETE FROM child WHERE id = '".$_POST["id"]."'"))){
			 echo 'Pomyslnie usunięto ucznia';  
		}

}

	

function fetch_class_name(){
		session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		
		$result=$connect->query(sprintf("SELECT name FROM class WHERE parent_id = (SELECT id FROM parent WHERE email = '".$_SESSION['user']."')"));
		$res=mysqli_fetch_array($result);

		$output = "<h1> Konto klasy ".$res['name']."</h1>";
		echo $output;
}

function fetch_event_list(){
	session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';  
		
		
		
		$result=$connect->query(sprintf("select * from event where class_id=(select id from class where parent_id='".$_SESSION['userID']."')"));
	
		
 $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="5%">Id</th>  
                     <th width="20%">Nazwa</th> 
					 <th width="15%">Cena</th>
					 <th width="15%">Data</th>
					 <th width="15%">Szczegóły</th>
					  <th width="15%">Edycja</th>
					 <th width="15%">Usuwanie</th>
					 
                </tr>'; 
				
				
 if(mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                     <td>'.$row["id"].'</td>  
                     <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["name"].'</td>  
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["price"].'</td>
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["date"].'</td>
					 <td><button type="button" data-toggle="modal" data-target="#eventDetailsModal"  data-id4="'.$row["id"].'" class="btn_detailsEvent">Szczegóły</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#eventEditModal"  data-id4="'.$row["id"].'" class="btn_editEvent">Edytuj</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#eventDeleteModal" data-id4="'.$row["id"].'" class="btn_deleteEvent">Usuń event</button></td>
				</tr>  
           ';  
      }  
 
 }  
 else  
 {  
      $output .= '<tr>  
                          <td colspan="4">Nie dodano jeszcze wydarzeń do tej klasy</td>  
                     </tr>';  
 }  
 
 $output .= '</table>  
      </div>';  
 echo $output;  
 
}
	
function fetch_students_list(){
		session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';  
		
		$tmpID = $connect->query(sprintf("SELECT id FROM parent WHERE email = '".$_SESSION['user']."'"));
		$id = mysqli_fetch_array($tmpID);
		$_SESSION['userID'] = $id["id"];
		$result=$connect->query(sprintf("SELECT * from child WHERE class_id = (SELECT id FROM class WHERE parent_id = ".$_SESSION['userID'].")"));
		
		
 $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="5%">Id</th>  
                     <th width="10%">Imię</th> 
					 <th width="10%">Nazwisko</th>
					 <th width="10%">Data urodzenia</th>
					 <th width="10%">Imię rodzica</th>
					 <th width="10%">Nazwisko rodzica</th>
					 <th width="10%">Mail rodzica</th>
					 <th width="10%">Usuń ucznia</th>
					 <th width="10%">Zmień maila rodzica</th>
                </tr>'; 
				
				
 if(mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  
			$parentTMP = $connect->query(sprintf("SELECT * FROM parent WHERE id = (SELECT parent_id FROM child WHERE id = ".$row["id"].")")); 
			$parent = mysqli_fetch_array($parentTMP);
           $output .= '  
                <tr>  
                     <td>'.$row["id"].'</td>  
                     <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["name"].'</td>  
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["surname"].'</td>
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["date_of_birth"].'</td>
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$parent["name"].'</td>
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$parent["surname"].'</td>
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$parent["email"].'</td>
					 <td><button type="button" name="delete_btn" data-id3="'.$row["id"].'" class="btn_deleteStudent">Usuń ucznia</button></td>
					 <td><button type="button" data-toggle="modal" data-target="#changeParMailModal" id="pMailChange_btn" name="pMailChange_btn" data-id3="'.$row["id"].'" class="btn_pMailChange">Zmień maila</button></td>
					 </tr>  
           ';  
      }  
 
 }  
 else  
 {  
      $output .= '<tr>  
                          <td colspan="4">Nie dodano jeszcze uczniów do tej klasy</td>  
                     </tr>';  
 }  
 
 $output .= '</table>  
      </div>';  
 echo $output;  
 
}	


function fetch_treasurer_data(){
		session_start();
	
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';  
		
		$result=$connect->query(sprintf("SELECT * FROM parent WHERE id =".$_SESSION['userID']));
		$res=mysqli_fetch_array($result);
		
        $output .= '<table>
		<tr><td>Imię: </td><td>'.$res["name"].'</td></tr> 
		<tr><td>Nazwisko: </td><td>'.$res["surname"].'</td></tr> 
		<tr><td>Email: </td><td>'.$res["email"].'</td></tr> 
	<table>
		   ';
 echo $output; 
	
	
}

//------------------------
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
		if ($result = $conn->query(sprintf("UPDATE username SET password='%s',first_login=FALSE WHERE login='%s'", mysqli_real_escape_string($conn, $newPassword) , mysqli_real_escape_string($conn, $login))))
			{
			echo "Record updated successfully";
			}
		  else
			{
			echo "Error updating record: " . $conn->error;
			}
		}

	$conn->close();
	header('Location: logout.php');
	}

// -----------------------------------

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
		$resultclassID=($conn->query(sprintf("select * from class where parent_id='".$_SESSION['userID']."'")))->fetch_assoc();		
		$classID = $resultclassID['id'];
		if ($result = $conn->query(sprintf("insert into event (name,price,date,class_ID) values ('%s' , '%s' ,'%s',$classID)", mysqli_real_escape_string($conn, $eventName) , mysqli_real_escape_string($conn, $eventPrice) , mysqli_real_escape_string($conn, $eventDate))))
			{
			echo "Record updated successfully";
			}
		  else
			{
			echo "Error updating record: " . $conn->error . "     " . $conn->connect_error . "     " . $conn->connect_errno;;
			}
			
		
		
			
			
			$resulteventID=($conn->query(sprintf("select * from event where name='%s' and date='%s'", mysqli_real_escape_string($conn, $eventName), mysqli_real_escape_string($conn, $eventDate))))->fetch_assoc();		
			$eventID = $resulteventID['id'];
			
			$result=$conn->query(sprintf("select * from child where class_id='".$classID."'"));
			
		 if(mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  	
  //////////
		$acc_bal=($conn->query(sprintf("SELECT balance FROM account WHERE child_id='%s'",mysqli_real_escape_string($conn, $row["id"]))))->fetch_assoc();		
		$account_balance = $acc_bal['balance']; 
		$eventPrice = $_POST['eventPrice'];
		
		if($account_balance > $eventPrice) {
			$toBePaid = $eventPrice;
			$newAccountBalance = $account_balance - $eventPrice;
		}
		
		if($account_balance <= $eventPrice) {
			$toBePaid = $account_balance;
			$newAccountBalance = 0;
		}
		
		//update balance
		$conn->query(sprintf("UPDATE account SET balance='%s' where child_id='%s'", mysqli_real_escape_string($conn, $newAccountBalance ),  mysqli_real_escape_string($conn, $row["id"] )));
		
		/////////
		$conn->query(sprintf("insert into participation (event_id,child_id,amount_paid) values ('%s','%s', '%s')", mysqli_real_escape_string($conn, $eventID), mysqli_real_escape_string($conn, $row["id"]), mysqli_real_escape_string($conn, $toBePaid)));
		
		$parent=($conn->query(sprintf("select * from parent where id=(select parent_id from child where id='".$row["id"]."')")))->fetch_assoc();
		mail($parent["email"], "Dodano nowe wydarzenie: $eventName" , "Dzień dobry, chcielibyśmy poinformować, że w systemie SkrabnikKlasowy pojawiło się nowe wydarzenie o nazwie $eventName i cenie $eventPrice. Odbędzie się ono $eventDate. SystemSKARBNIKklasowy");	
			 
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

// -------------------------------------------
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
//-----------------------------
function addChildParent()
	{
	session_start();
	if (empty($_POST['childName']) || $_POST['childName'] == '0' || empty($_POST['childSurname']) || $_POST['childSurname'] == '0' || empty($_POST['childBirthdate']) || $_POST['childBirthdate'] == '0' || empty($_POST['parentName']) || $_POST['parentName'] == '0' || empty($_POST['parentSurname']) || $_POST['parentSurname'] == '0' )
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
		$passwd=randomPassword();
		if (empty($_POST['parentEmail']) || $_POST['parentEmail'] == '0'  )
		{
		$parentEmail = $parentName.$parentSurname;
		$parentEmail = htmlentities($parentEmail, ENT_QUOTES, "UTF-8");
		
		
		// utworzenie uchwytu do pliku
		// tryb a umożliwia zapis na końcu pliku
		$plik = fopen('ParentsWithoutEmail.txt','a');

		// przypisanie zawartości do zmiennej
		$zawartosc = "Login : ".$parentEmail." hasło : ".$passwd."\r\n";

		fwrite($plik, $zawartosc);
		}
		else{
		$parentEmail = $_POST['parentEmail'];
		$parentEmail = htmlentities($parentEmail, ENT_QUOTES, "UTF-8");	
			
		}
		//id klasy zalgodowanego skarbnika
		$classID1=$conn->query(sprintf("SELECT id FROM class where parent_id=(SELECT id FROM parent WHERE email = '".$_SESSION['user']."' )"));
		$classID=mysqli_fetch_array($classID1)["id"];
		
		
		if ($result = @$conn->query(sprintf("SELECT * FROM parent WHERE email='%s'", mysqli_real_escape_string($conn, $parentEmail))))
			{
			$isUser = $result->num_rows;
			if ($isUser <= 0)
				{ //RODZICA NIE MA W SYSTEMIE
				if ($result = $conn->query(sprintf("insert into parent (name,surname,email,type) values ('%s' , '%s' ,'%s','p')", mysqli_real_escape_string($conn, $parentName) , mysqli_real_escape_string($conn, $parentSurname) , mysqli_real_escape_string($conn, $parentEmail))))
					{
					echo "Record inserted successfully";
					}
				  else
					{
					echo "Error inserted record: " . $conn->error . "     " . $conn->connect_error . "     " . $conn->connect_errno;
					}
					
				
				mail($parentEmail, "Haslo pierwszego logowania rodzica" , "Twoje hasło pierwszego logowanie to: $passwd");				
				//dodanie do username
				$conn->query(sprintf("insert into username (login,password,type,first_login) values ('%s' , '$passwd' ,'p',TRUE)", mysqli_real_escape_string($conn, $parentEmail)));
				
				// szukamy id nowego rodzica
				
				if ($result = @$conn->query(sprintf("SELECT * FROM parent WHERE email='%s'", mysqli_real_escape_string($conn, $parentEmail))))
					{
					$details = $result->fetch_assoc();
					$parentIDdb = $details['id'];
					if ($result = $conn->query(sprintf("insert into child (name,surname,date_of_birth,parent_id,class_id) values ('%s' , '%s' ,'%s','$parentIDdb','$classID')", mysqli_real_escape_string($conn, $childName) , mysqli_real_escape_string($conn, $childSurname) , mysqli_real_escape_string($conn, $childBirthdate))))
						{
						echo "Record inserted successfully";
						}
					  else
						{
						echo "Error inserted record: " . $conn->error . "     " . $conn->connect_error . "     " . $conn->connect_errno;
						}
					}
				}
			  else
				{

				// RODZIC JEST JUZ W SYSTEMIE WIEC DODAJE SAMO DZIECKO

				$details = $result->fetch_assoc();
				$parentIDdb = $details['id'];
				if ($result = $conn->query(sprintf("insert into child (name,surname,date_of_birth,parent_id,class_id) values ('%s' , '%s' ,'%s','$parentIDdb','$classID')", mysqli_real_escape_string($conn, $childName) , mysqli_real_escape_string($conn, $childSurname) , mysqli_real_escape_string($conn, $childBirthdate))))
					{
					echo "Record inserted successfully";
					}
				  else
					{
					echo "Error inserted record: " . $conn->error . "     " . $conn->connect_error . "     " . $conn->connect_errno;
					}
				}
			}

		$conn->close();
		header('Location: treasuer_menu/addStudent.php');
		}
	}

?>