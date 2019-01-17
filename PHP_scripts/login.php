<?php
session_start();

// jezeli nie ma w poscie loginu lub hasla

if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
{
	header('Location: index.php');
	exit();
}

// dodanie pliku z connectem, require lepsze od include bo zwraca errory i zatrzymuje skrypt
// once sprawdza czy juz byl dodany w pliku, jak jest to juz nie dokleja :D

require_once "connection.php";

// stworzenie polaczenia z baza danych -> @ wyciszanie bledow zeby dac swoje

$conn = new MyDB();

if ($conn->connect_errno != 0)
{
	echo "Blad: " . $conn->connect_errno; // " Opis bledu: ".$conn->connect_error;
}
else
{ //polaczenie spoko :)
	$login = $_POST['login'];
	$password = $_POST['password'];

	// ENT_QUOTES tez cudzyslowie

	$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	$password = htmlentities($password, ENT_QUOTES, "UTF-8");

	// zabezpieczenie przed sql injection

	if ($result = @$conn->query(sprintf("SELECT * FROM username WHERE login='%s'", mysqli_real_escape_string($conn, $login))))
	{
		$userCount = $result->num_rows;
		$details = $result->fetch_assoc();
		if ($userCount > 0 && password_verify($password, $details['hashedPassword']))
		{
			// ustalam ze jestem zalogowany

			$_SESSION['loggedIn'] = true;
			$_SESSION['user'] = $details['login'];

			$school_year_id = "select max(id) as id from school_year;";
			$school_year_idq = ($conn->query($school_year_id))->fetch_assoc();
			$_SESSION['school_year_id'] = $school_year_idq['id'];
			
			// usuniecie zmiennej blad z session

			unset($_SESSION['error']);
			$result->free_result();
			$typeUser = "select type from username where login='$login'";
			$typeDB = ($conn->query($typeUser))->fetch_assoc();
			$_SESSION['type'] = $typeDB['type'];

			// przekierowanie do innego pliku jak OK logowanie post -formulatrze get- w adresie byloby widac
			// header('Location: menu.php');

			$resultID = ($conn->query(sprintf("select * from parent where email='" . $_SESSION['user'] . "'")))->fetch_assoc();
			$_SESSION['userID'] = $resultID['id'];
			$_SESSION['firstDisplayParent'] = true;
			if ($typeDB['type'] == "a")
			{
				echo("<script>location.replace('menu_admin.php');</script>");
				//header('Location: menu_admin.php');
			}

			if ($typeDB['type'] == "p")
			{
				echo("<script>location.replace('menu_parent.php');</script>");
				//header('Location: menu_parent.php');
			}

			if ($typeDB['type'] == "t")
			{
				echo("<script>location.replace('menu_treasurer.php');</script>");
				//header('Location: menu_treasurer.php');
			}
		}
		else
		{
			$_SESSION['error'] = 'Nieprawidłowy login lub hasło!';
			//header('Location: index.php');
			echo("<script>location.replace('index.php');</script>");
		}
	}
	$conn->close();
}
?>