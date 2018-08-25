<?php
	//pozwala koszystac z sesji
	//session_start();
	
	//jezeli nie ma w poscie loginu lub hasla
	if ((!isset($_POST['newpassword'])) )
	{
		header('Location: menu_admin.php');
		exit();
	}
	
	//dodanie pliku z connectem, require lepsze od include bo zwraca errory i zatrzymuje skrypt
	//once sprawdza czy juz byl dodany w pliku, jak jest to juz nie dokleja :D
	require_once "menu_admin.php";
		require_once "connection.php";
	//doIT();
	//stworzenie polaczenia z baza danych -> @ wyciszanie bledow zeby dac swoje
	//function doIT(){
	$conn = @new mysqli($servername, $username, $password, $dbName);
	
	if ($conn->connect_errno!=0){
		echo "Blad: ".$conn->connect_errno;// " Opis bledu: ".$conn->connect_error;
	}
	else { //polaczenie spoko :) 
			$newPassword=$_POST['newpassword'];
			$newPassword= htmlentities($newPassword, ENT_QUOTES, "UTF-8");
			
			$login =$_SESSION['user'];
			$login = htmlentities($login, ENT_QUOTES, "UTF-8");
			//zabezpieczenie przed sql injection
			//zabezpieczenie przed sql injection
			echo "<br>" ."login: " . $login. "<br>" ;
		echo "<br>" ."haslo: " . $newPassword. "<br>" ;
		//$result = $conn->query(sprintf("update username set password='%s' where login='%s'",
		//mysqli_real_escape_string($conn,$newPassword),
		//mysqli_real_escape_string($conn,$login)));
		
		$sql="UPDATE username SET password='$newPassword' WHERE login='$login'";
		if ($conn->query($sql) === TRUE) {
			echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
		
	}
		
		$conn->close();

	
?>