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
	}

	}
	
	
	
function changeParentMail(){
	session_start();
	echo "<script>console.log( 'Zmieniasz maila rodzicowi o id dziecka:  " .$_SESSION['changeIDmail']. "' );</script>";

}	

function btn_pMailChange(){
	session_start();
	$_SESSION['changeIDmail']=$_POST["id"];
	echo  "<script>console.log( 'Id:  " .$_SESSION['changeIDmail']. "' );</script>";
	
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
		
		//$tmpID = $connect->query(sprintf("SELECT id FROM parent WHERE email = '".$_SESSION['user']."'"));
		//$id = mysqli_fetch_array($tmpID);
		//$_SESSION['userID'] = $id["id"];
		$result=$connect->query(sprintf("SELECT * FROM event"));
		
		
 $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="5%">Id</th>  
                     <th width="25%">Nazwa</th> 
					 <th width="15%">Cena</th>
					 <th width="15%">Data</th>
					 <th width="15%">Szczegóły</th>
					 
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
					 <td> BUTTON </td>
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
		if ($result = $conn->query(sprintf("insert into event (name,price,date) values ('%s' , '%s' ,'%s')", mysqli_real_escape_string($conn, $eventName) , mysqli_real_escape_string($conn, $eventPrice) , mysqli_real_escape_string($conn, $eventDate))))
			{
			echo "Record updated successfully";
			}
		  else
			{
			echo "Error updating record: " . $conn->error . "     " . $conn->connect_error . "     " . $conn->connect_errno;;
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