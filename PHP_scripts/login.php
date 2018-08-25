<?php
	//pozwala koszystac z sesji
	session_start();
	
	//jezeli nie ma w poscie loginu lub hasla
	if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
	{
		header('Location: index.php');
		exit();
	}
	
	//dodanie pliku z connectem, require lepsze od include bo zwraca errory i zatrzymuje skrypt
	//once sprawdza czy juz byl dodany w pliku, jak jest to juz nie dokleja :D
	require_once "connection.php";
	
	//stworzenie polaczenia z baza danych -> @ wyciszanie bledow zeby dac swoje
	$conn = @new mysqli($servername, $username, $password, $dbName);
	
	if ($conn->connect_errno!=0){
		echo "Blad: ".$conn->connect_errno;// " Opis bledu: ".$conn->connect_error;
	}
	else { //polaczenie spoko :) 
		$login = $_POST['login'];
		$password = $_POST['password'];
		//ENT_QUOTES tez cudzyslowie
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$password = htmlentities($password, ENT_QUOTES, "UTF-8");
		
		//zabezpieczenie przed sql injection
		if($result = @$conn->query(sprintf("SELECT * FROM username WHERE login='%s' AND password='%s'",
		mysqli_real_escape_string($conn,$login),
		mysqli_real_escape_string($conn,$password))))
		{
			
			
			
			$userCount = $result->num_rows;
			if($userCount>0)
			{	
				//ustalam ze jestem zalogowany
				$_SESSION['loggedIn']=true;
				$details = $result->fetch_assoc();
				$_SESSION['user']=$details['login'];
				
				//usuniecie zmiennej blad z session
				unset($_SESSION['error']);
				$result->free_result();
				
				$typeUser="select type from username where login='$login' and password='$password'";
				$typeDB = ($conn->query($typeUser))->fetch_assoc();
				
				$_SESSION['type']=$typeDB['type'];
				
				//przekierowanie do innego pliku jak OK logowanie post -formulatrze get- w adresie byloby widac
				//header('Location: menu.php');
	
				if ($typeDB['type'] =="a"){
					header('Location: menu_admin.php')
				;}
				if ($typeDB['type'] =="p"){
					header('Location: menu_parent.php');
				}
				if ($typeDB['type']=="t"){
				header('Location: menu_treasurer.php');
				}
				
				
			}
			else
			{
				$_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: index.php');
			}
			
		}
		
		echo "<br>" ."login: " . $login. "<br>" ;
		echo "<br>" ."haslo: " . $password. "<br>" ;
		
		$conn->close();
	}
	
	

	
?>