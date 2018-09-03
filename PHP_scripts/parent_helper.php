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
	}

	}
	
if ((isset($_POST['RequiredNewPasswordAccept'])))
	{
	changePassword();
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
		$result=$connect->query(sprintf("SELECT * from event"));
		
		
 $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">  
                <tr>  
                     <th width="5%">Id</th>  
                     <th width="40%">Nazwa</th>  
                     <th width="15%">Cena</th>  
                     <th width="20%">Data</th>  
					 <th width="10%">Wypisz dziecko</th>
					 <th width="10%">Szczegóły</th>
                </tr>'; 
				
				
 if(mysqli_num_rows($result) > 0)  
 {  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                     <td>'.$row["id"].'</td>  
                     <td class="name" data-id1="'.$row["id"].'" contenteditable>'.$row["name"].'</td>  
                     <td class="price" data-id2="'.$row["id"].'" contenteditable>'.$row["price"].'</td>  
                     <td class="date" data-id2="'.$row["id"].'" contenteditable>'.$row["date"].'</td>
					 <td><button type="button" name="delete_btn" data-id3="'.$row["id"].'" class="btn_delete">Wypisz</button></td>  
					  <td><button type="button" name="details_btn" data-id3="'.$row["id"].'" class="btn_details">Szczegóły</button></td>
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
		if($res=$connect->query(sprintf("DELETE FROM event WHERE id = '".$_POST["id"]."'"))){
			 echo 'Pomyslnie wypisano dziecko';  
		}

}



?>