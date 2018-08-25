<?php
	//TODO zabezpieczenie przy wykonywaniu sql
	require_once "menu_admin.php";

	if ((isset($_POST['changePassword'])) )
	{
		changePassword();
	}
	
	if ((isset($_POST['addClassTreasurer'])) )
	{
		addClassTreasurer();
	}
	
	function changePassword(){
	
	if (empty($_POST['newPassword'])||$_POST['newPassword']=='0')
	{
		header('Location: menu_admin.php');
		exit();
	}
	require_once "connection.php";
	$conn = new mysqli( $servername,  $username,  $password, $dbName);
	
	if ($conn->connect_errno!=0){
		echo "Blad: ".$conn->connect_errno;// " Opis bledu: ".$conn->connect_error;
	}
	else { //polaczenie spoko :) 
			$newPassword=$_POST['newPassword'];
			$newPassword= htmlentities($newPassword, ENT_QUOTES, "UTF-8");
			$login =$_SESSION['user'];
			$login = htmlentities($login, ENT_QUOTES, "UTF-8");

			$sql="UPDATE username SET password='$newPassword' WHERE login='$login'";
		
			if ($conn->query($sql) === TRUE) {
			echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
		}
			$conn->close();
	}
		function addClassTreasurer(){
	//session_start();
	if ( empty($_POST['className'])||$_POST['className']=='0' || empty($_POST['name'])||$_POST['name']=='0' || empty($_POST['surname'])||$_POST['surname']=='0' || empty($_POST['email'])||$_POST['email']=='0' )
	{
		header('Location: menu_admin.php');
		exit();
	}
		require_once "connection.php";
		$conn = new mysqli( $servername,  $username,  $password, $dbName);
	
	if ($conn->connect_errno!=0){
		echo "Blad: ".$conn->connect_errno;// " Opis bledu: ".$conn->connect_error;
	}
	else { //polaczenie spoko :) 
			
			$className=$_POST['className'];
			$className= htmlentities($className, ENT_QUOTES, "UTF-8");
			$name=$_POST['name'];
			$name= htmlentities($name, ENT_QUOTES, "UTF-8");
			$surname=$_POST['surname'];
			$surname= htmlentities($surname, ENT_QUOTES, "UTF-8");
			$email=$_POST['email'];
			$email= htmlentities($email, ENT_QUOTES, "UTF-8");
			
				$sql="INSERT INTO class (name) values ('$className')";
				$sql2 =  "INSERT INTO parent (name,surname,email) values( '$name', '$surname' , '$email')";
			if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
			echo "Record created successfully";
		} else {
		echo "Error updating record: " . $conn->error;
		}
		}
			$conn->close();
	}
	
?>