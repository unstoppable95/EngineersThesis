<?php

if ((isset($_POST['changePassword'])))
	{
	changePassword();
	}
	
	
		if ((isset($_POST['function2call'])))
	{
		$function2call = $_POST['function2call'];	
		switch($function2call) {
			case 'fetch' : fetch();break;
			case 'delete' : deleteFromDB(); break;
			case 'fetch_child_list': fetch_child_list(); break;
			case 'choose' : choose(); break;
			case 'parent_data' : fetch_parent_data(); break;
			case 'fetch_balance' : fetch_balance(); break;
			case 'fetch_payment_history' : fetch_payment_history(); break;
			case 'fetch_child_name' : fetch_child_name(); break;
			case 'fetch_class_expenses_list' : fetch_class_expenses_list(); break;
			case 'fetch_class_account_data' : fetch_class_account_data(); break;
		
	}

	}
	
	
	
if ((isset($_POST['RequiredNewPasswordAccept'])))
	{
	changePassword();
	}
	
	if ((isset($_POST['MakePayment'])))
	{
	makePayment();
	}


	
function fetch_class_expenses_list(){
		session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';  
		$result=$connect->query(sprintf("SELECT * FROM expense WHERE class_account_id =(SELECT id FROM class_acount WHERE class_id = (SELECT class_id FROM child WHERE id =".$_SESSION['choosenChild'].")) ORDER BY date"));
		
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
							 <td class="name" data-id1="'.$row["id"].'" >'.$row["name"].'</td> 
							<td class="name" data-id1="'.$row["id"].'" >'.$row["price"].'</td>  					 
							 <td class="price" data-id2="'.$row["id"].'" >'.$row["date"].'</td>
						</tr>  
				   ';  
			  }  
		 
		 }  
		 else  
		 {  
			  $output .= '<tr>  
								  <td colspan="4">Nie znaleziono wydatków</td>  
							 </tr>';  
		 }  
		 
		 $output .= '</table>  
			  </div>';  
		 echo $output; 
}


function fetch_class_account_data(){
	session_start();
	require_once "connection.php";
	$conn = new mysqli($servername, $username, $password, $dbName); 
	
	$sum=$conn->query(sprintf("SELECT SUM(amount) as s FROM class_account_payment WHERE child_id = ".$_SESSION['choosenChild']));
	$r=$sum->fetch_assoc();
	$amount_of_paid_money = $r["s"];
	
	
	
	$class_acc_id=$conn->query(sprintf("SELECT id FROM class_acount WHERE class_id = (SELECT class_id FROM child WHERE id =".$_SESSION['choosenChild'].")"));
	$ress=$class_acc_id->fetch_assoc();
	$class_account_id = $ress["id"];
 
	$curr_class_balance = $conn->query(sprintf("SELECT balance FROM class_acount WHERE id =".$class_account_id));
	$res=mysqli_fetch_array($curr_class_balance);
	$current_class_balance = $res["balance"];
		 
	$output = '<form>
		<table>
			<tr><td>Ilość pieniędzy wpłaconych na konto klasowe dziecka:  </td><td>'.$amount_of_paid_money.'</td></tr> 
			<tr><td>Suma pieniędzy na koncie klasowym całej klasy:  </td><td>'.$current_class_balance.'</td></tr> 
		</table>
	</form>';  
  

 echo $output; 
			
}
	
function fetch_child_name(){
			session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName); 
		$result=$connect->query(sprintf("SELECT * FROM child WHERE id=".$_SESSION['choosenChild']));
				
				
	 if(mysqli_num_rows($result) > 0)  
	 {  
		 $row = mysqli_fetch_array($result);
		 $class=$connect->query(sprintf("SELECT * FROM class WHERE id=".$row["class_id"]));	
		 $classrow = mysqli_fetch_array($class);
		 
		$output = '<h3> Bierzące płatności dziecka: '.$row["name"].' '.$row["surname"].', klasa: '.$classrow["name"].' </h3>';  
	 }  

 echo $output; 
}	


function fetch_payment_history(){
		session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';  
		$result=$connect->query(sprintf("SELECT * FROM payment WHERE account_id =(SELECT id FROM account WHERE child_id = ".$_SESSION['choosenChild'].") ORDER BY date"));
		
		
 $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="33%">Kwota</th> 
					 <th width="33%">Data</th>
                     <th width="34%">Typ wpłaty</th>
                </tr>'; 
				
				
 if(mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  
			
           $output .= '  
                <tr>  
                     <td class="name" data-id1="'.$row["id"].'" >'.$row["amount"].'</td> 
					<td class="name" data-id1="'.$row["id"].'" >'.$row["date"].'</td>  					 
                     <td class="price" data-id2="'.$row["id"].'" >'.$row["type"].'</td>
                </tr>  
           ';  
      }  
 
 }  
 else  
 {  
      $output .= '<tr>  
                          <td colspan="4">Nie znaleziono wpłat</td>  
                     </tr>';  
 }  
 
 $output .= '</table>  
      </div>';  
 echo $output; 
	
}

//call when parent want to transfer money to child account. It increment child's account and then it automatically pay for event (chronological), as long as the account balance allows it
function makePayment(){
	session_start();
	if (empty($_POST['amountOfMoney']) || $_POST['amountOfMoney'] == '0')
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
		$amountOfMoney = $_POST['amountOfMoney'];
		$amountOfMoney = htmlentities($amountOfMoney, ENT_QUOTES, "UTF-8");
		$child = $_SESSION['choosenChild'];
		
		if($_POST['typeOfAccount']=="normal"){ //if parent make normal (cash) payment
				$curr=$conn->query(sprintf("SELECT balance as b FROM account WHERE child_id =".$_SESSION['choosenChild']));
				//$currentBalanceTmp=mysqli_fetch_array($curr);
				$res=mysqli_fetch_array($curr);
				$currentBalance = $res["b"];

				$newBalance = $currentBalance + $amountOfMoney;
				
				if ($result = $conn->query(sprintf("UPDATE account SET balance='%s' WHERE child_id = '%s'",  mysqli_real_escape_string($conn, $newBalance),  mysqli_real_escape_string($conn, $child))))
				{
					
				$account_idTMP=$conn->query(sprintf("SELECT id FROM account WHERE child_id =".$_SESSION['choosenChild']));
				$res=mysqli_fetch_array($account_idTMP);
				$accountID = $res["id"];
					
					$conn->query(sprintf("INSERT INTO payment (account_id,amount,type) VALUES (".$accountID.",".$amountOfMoney.",'".$_POST['paymentType']."')"));
					echo "Record updated successfully";
				}
				else
				{
					echo "Error updating record: " . $conn->error;
				}
		}
		else{ //if parent want to transfer money to class account
			//fetch class account id
			$class_acc_id=$conn->query(sprintf("SELECT id FROM class_acount WHERE class_id = (SELECT class_id FROM child WHERE id =".$_SESSION['choosenChild'].")"));
			$ress=$class_acc_id->fetch_assoc();
			$class_account_id = $ress["id"];
		
			//inserting payment to class account
			$conn->query(sprintf("INSERT INTO class_account_payment (amount,class_account_id, child_id) VALUES (".$amountOfMoney.",".$class_account_id.",".$_SESSION['choosenChild'].")"));
			
			//updating class account balance
			$curr_class_balance = $conn->query(sprintf("SELECT balance FROM class_acount WHERE id =".$class_account_id));
			$res=mysqli_fetch_array($curr_class_balance);
			$current_class_balance = $res["balance"];
			
			$new_account_balance = $current_class_balance + $amountOfMoney;
			
			$conn->query(sprintf("UPDATE class_acount SET balance='%s' WHERE id = '%s'",  mysqli_real_escape_string($conn, $new_account_balance),  mysqli_real_escape_string($conn, $class_account_id)));

		}
	

	}
	
	
	$conn->close();
	header('Location: menu_parent.php');
		
		
		
		
		

	
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
	
	
	
function fetch(){
		session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';  
		$result=$connect->query(sprintf("SELECT * from event WHERE id IN (SELECT event_id FROM participation WHERE child_id =" .$_SESSION['choosenChild'].")"));
		
		
 $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="5%">Id</th>  
                     <th width="35%">Nazwa</th> 
					 <th width="15%">Wpłacono</th>
                     <th width="15%">Cena</th>  
                     <th width="20%">Data</th>  
					 <th width="10%">Wypisz dziecko</th>
                </tr>'; 
				
				
 if(mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  
			
			$payedTmp=$connect->query(sprintf("SELECT * FROM participation WHERE child_id =".$_SESSION['choosenChild']." AND event_id=".$row["id"]));
				$res=mysqli_fetch_array($payedTmp);
				$paid = $res["amount_paid"];
			
           $output .= '  
                <tr>  
                     <td>'.$row["id"].'</td>  
                     <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["name"].'</td> 
					<td class="name" data-id1="'.$row["id"].'" contenteditable>'.$paid.'</td>  					 
                     <td class="price" data-id2="'.$row["id"].'" contenteditable>'.$row["price"].'</td>  
                     <td class="date" data-id2="'.$row["id"].'" contenteditable>'.$row["date"].'</td>
					 <td><button type="button" name="delete_btn" data-id3="'.$row["id"].'" class="btn_delete">Wypisz</button></td>
                </tr>  
           ';  
      }  
 
 }  
 else  
 {  
      $output .= '<tr>  
                          <td colspan="4">Nie znaleziono wydarzeń</td>  
                     </tr>';  
 }  
 
 $output .= '</table>  
      </div>';  
 echo $output;  
 
}

function deleteFromDB(){
		session_start();
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		
		//adding paid money to account
		
		if($res=$connect->query(sprintf("DELETE FROM participation where event_id='".$_POST["id"]."' AND child_id = " .$_SESSION['choosenChild']))){
			 echo 'Pomyslnie wypisano dziecko';  
		}

}



//---------------------------------------
//--------------------------

function fetch_child_list(){
		session_start();
		$_SESSION['firstDisplayParent']=false;
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = ''; 
		
		$x= $connect->query(sprintf("SELECT id FROM parent WHERE email = '".$_SESSION['user']."'"));
		$res=mysqli_fetch_array($x);
		$_SESSION['userID'] = $res["id"];
		$result=$connect->query(sprintf("SELECT * from child WHERE parent_id = ".$_SESSION['userID']));
		
		
 $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="5%">Id</th>  
                     <th width="20%">Imię</th> 
					 <th width="30%">Nazwisko</th>
					 <th width="30%">Data urodzenia</th>
					 <th width="15%">Wybierz</th>
                </tr>'; 
				
				
 if($result && mysqli_num_rows($result) > 0 )  
 {  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                     <td>'.$row["id"].'</td>  
                     <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["name"].'</td>  
					 <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["surname"].'</td>
					  <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["date_of_birth"].'</td>
					 <td><button type="button" name="chooose_btn" data-id3="'.$row["id"].'" class="btn_choose">Wybierz</button></td>  
				</tr>  
           ';  
      }  
	  // href="menu_parent.php"
 
 }  
 else  
 {  
      $output .= '<tr>  
                          <td colspan="4">Nie posiadasz w szkole żadnych dzieci</td>  
                     </tr>';  
 }  
 
 $output .= '</table>  
      </div>';  
 echo $output; 

 
}

function fetch_parent_data(){
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

function choose(){
	session_start();
	$_SESSION['choosenChild']=$_POST["id"];
	$ale="Wybrales dziecko o id: ".$_SESSION['choosenChild'];
	echo $ale;
	//header('Location: menu_parent.php');
	//exit();
	
}

function fetch_balance(){
	session_start();
	
		require_once "connection.php";
		$connect = new mysqli($servername, $username, $password, $dbName);
		$output = '';  
		
		$result=$connect->query(sprintf("SELECT balance FROM account WHERE child_id =".$_SESSION['choosenChild']));
		$res=mysqli_fetch_array($result);

		
        $output .= $res["balance"];
 echo $output;  
	
}



?>