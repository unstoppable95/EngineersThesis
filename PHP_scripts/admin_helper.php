<?php

if ((isset($_POST['changePassword'])))
{
	changePassword();
}

if ((isset($_POST['addClassTreasurer'])))
{
	addClassTreasurer();
}

if ((isset($_POST['showClasses'])))
{
	displayClass();
}

if ((isset($_POST['changeTreasuer2'])))
{
	changeTreasuer2();
}

if ((isset($_POST['changeNewTreasurer'])))
{
	changeEmailTreasuer();
}

if ((isset($_POST['sendPassword'])))
{
	sendPassword();
}

if ((isset($_POST['addStudent'])))
{
	addStudent();
}

if ((isset($_POST['addStudentsFile'])))
{
	addStudentsFile();
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

	case 'details':
		showDetails();
		break;

	case 'changeTreasuer':
		changeTreasurer();
		break;

	case 'addStudent2':
		addStudent2();
		break;

	case 'saveClassID':
		saveClassID();
		break;
	}
}

function saveClassID()
{
	session_start();
	$_SESSION['classIDCSV'] = $_POST["id"];
}

function addStudentsFile()
{

	// UPLOAD PLIKU na SERWER

	session_start();
	$target_dir = "\uploadsCSV\\";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
	$uploadOk = 1;

	// Check if file already exists

	if (file_exists($target_file))
	{
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}

	// Check file size

	if ($_FILES["fileToUpload"]["size"] > 500000)
	{
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}

	// Allow certain file formats

	if ($imageFileType != "csv")
	{
		echo "Sorry, only csv files are allowed.";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error

	if ($uploadOk == 0)
	{
		echo "Sorry, your file was not uploaded.";

		// if everything is ok, try to upload file

	}
	else
	{
		define('SITE_ROOT', realpath(dirname(__FILE__)));
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], SITE_ROOT . $target_file))
		{
			echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
		}
		else
		{
			echo "Sorry, there was an error uploading your file.";
		}
	}

	// odczytanie i insert do bazy danych  -> nazwa pliku

	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	$filename = "." . $target_file;
	if ($_FILES["fileToUpload"]["size"] > 0)
	{
		$file = fopen($filename, "r");
		while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
		{

			// pobieranie danych z pliku csv

			$childName = $getData[3];
			$childName = htmlentities($childName, ENT_QUOTES, "UTF-8");
			$childSurname = $getData[4];
			$childSurname = htmlentities($childSurname, ENT_QUOTES, "UTF-8");
			$childBirthdate = $getData[5];
			$childBirthdate = htmlentities($childBirthdate, ENT_QUOTES, "UTF-8");
			$parentName = $getData[0];
			$parentName = htmlentities($parentName, ENT_QUOTES, "UTF-8");
			$parentSurname = $getData[1];
			$parentSurname = htmlentities($parentSurname, ENT_QUOTES, "UTF-8");
			$parentEmail = $getData[2];
			$parentEmail = htmlentities($parentEmail, ENT_QUOTES, "UTF-8");
			$passwd = randomPassword();
			$classID = $_SESSION['classIDCSV'];
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
					else
					{

						// RODZIC JEST JUZ W SYSTEMIE WIEC DODAJE SAMO DZIECKO

						$details = $result->fetch_assoc();
						$parentIDdb = $details['id'];
						$result = $conn->query(sprintf("insert into child (name,surname,date_of_birth,parent_id,class_id) values ('%s' , '%s' ,'%s','$parentIDdb','$classID')", mysqli_real_escape_string($conn, $childName) , mysqli_real_escape_string($conn, $childSurname) , mysqli_real_escape_string($conn, $childBirthdate)));
					}
				}
			}
		}

		fclose($file);
	}

	$conn->close();

	// usuniecie pliku z serwera

	unlink($filename);
	header('Location: menu_admin.php');
}

function addStudent()
{
	session_start();
	if (empty($_POST['childName']) || $_POST['childName'] == '0' || empty($_POST['childSurname']) || $_POST['childSurname'] == '0' || empty($_POST['childBirthdate']) || $_POST['childBirthdate'] == '0' || empty($_POST['parentName']) || $_POST['parentName'] == '0' || empty($_POST['parentSurname']) || $_POST['parentSurname'] == '0')
	{
		header('Location: menu_admin.php');
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

		$classID = $_SESSION['classToAdd'];
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
		header('Location: menu_admin.php');
	}
}

function addStudent2()
{
	session_start();
	$_SESSION['classToAdd'] = $_POST["id"];
	echo "<script>console.log( 'Id:  " . $_SESSION['classToAdd'] . "' );</script>";
}

function sendPassword()
{
	session_start();
	if (empty($_POST['myMail']) || $_POST['myMail'] == '0')
	{
		header('Location: index.php');
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
		$myEmail = $_POST['myMail'];
		$myEmail = htmlentities($myEmail, ENT_QUOTES, "UTF-8");
		$isLogin = ($conn->query(sprintf("SELECT * FROM username WHERE login = '%s'", mysqli_real_escape_string($conn, $myEmail))))->num_rows;
		if ($isLogin > 0)
		{
			$psswd = ($conn->query(sprintf("SELECT password FROM username WHERE login = '%s'", mysqli_real_escape_string($conn, $myEmail))))->fetch_assoc();
			mail($myEmail, "Odzyskiwanie hasła", "Twoje nowe hasło w systemie skarbnik klasowy to: " . $psswd["password"]);
			echo "<script>
			alert('Twoje hasło zostało wysłane na podany adres email!');
			window.location.href='index.php';
			</script>";
		}
		else
		{
			echo "<script>
			alert('Nie istnieje taki login w systemie!');
			window.location.href='index.php';
			</script>";
		}
	}

	$conn->close();
}

function changeEmailTreasuer()
{
	session_start();
	if (empty($_POST['trNewMail']) || $_POST['trNewMail'] == '0')
	{
		header('Location: menu_admin.php');
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
		$newTreasurerEmail = $_POST['trNewMail'];
		$newTreasurerEmail = htmlentities($newTreasurerEmail, ENT_QUOTES, "UTF-8");
		$conn->query(sprintf("UPDATE parent SET email='%s' where id=(select parent_id from class where id='" . $_SESSION['changeID'] . "')", mysqli_real_escape_string($conn, $newTreasurerEmail)));
		mail($newTreasurerEmail, "Zmiana adresu email", "Mail w systemie skarbnik klasowy został zmieniony pomyślnie. Pozdrawiamy System Skarbnik Klasowy");
	}

	$conn->close();
	header('Location: menu_admin.php');
}

function changeTreasuer2()
{
	echo "<script>console.log( 'Debug Objectss: " . $_SESSION['changeID'] . "' );</script>";
	if (empty($_POST['trMail']) || $_POST['trMail'] == '0')
	{
		header('Location: menu_admin.php');
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
		$newParentEmail = $_POST['trMail'];
		$newParentEmail = htmlentities($newParentEmail, ENT_QUOTES, "UTF-8");
		$conn->query(sprintf("UPDATE parent SET type='p' where id=(select parent_id from class where id ='" . $_SESSION['changeID'] . "')"));
		$conn->query(sprintf("UPDATE class SET parent_id=(select id from parent where email='%s') where id='" . $_SESSION['changeID'] . "'", mysqli_real_escape_string($conn, $newParentEmail)));
		$conn->query(sprintf("UPDATE parent SET type='t' where email='%s'", mysqli_real_escape_string($conn, $newParentEmail)));
	}

	$conn->close();
	header('Location: menu_admin.php');
}

function changeTreasurer()
{
	session_start();
	$_SESSION['changeID'] = $_POST["id"];
	echo "Zmieniane id: " . $_SESSION['changeID'];
}

function changePassword()
{
	session_start();
	if (empty($_POST['newPassword']) || $_POST['newPassword'] == '0')
	{
		header('Location: menu_admin.php');
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
		if ($result = @$conn->query(sprintf("UPDATE username SET password='%s' WHERE login='%s'", mysqli_real_escape_string($conn, $newPassword) , mysqli_real_escape_string($conn, $login))))
		{
			$_SESSION['funChange'] = true;
		}
	}

	$conn->close();
	header('Location: logout.php');
}

function randomPassword()
{
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++)
	{
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}

	return implode($pass); //turn the array into a string
}

function addClassTreasurer()
{
	session_start();
	if (empty($_POST['className']) || $_POST['className'] == '0' || empty($_POST['name']) || $_POST['name'] == '0' || empty($_POST['surname']) || $_POST['surname'] == '0' || empty($_POST['email']) || $_POST['email'] == '0')
	{
		header('Location: menu_admin.php');
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
		$className = $_POST['className'];
		$className = htmlentities($className, ENT_QUOTES, "UTF-8");
		$name = $_POST['name'];
		$name = htmlentities($name, ENT_QUOTES, "UTF-8");
		$surname = $_POST['surname'];
		$surname = htmlentities($surname, ENT_QUOTES, "UTF-8");
		$email = $_POST['email'];
		$email = htmlentities($email, ENT_QUOTES, "UTF-8");
		if ($result = @$conn->query(sprintf("SELECT * FROM parent WHERE email='%s'", mysqli_real_escape_string($conn, $email))))
		{
			$isUser = $result->num_rows;
			if ($isUser <= 0)
			{ //SKARBNIKA NIE MA W SYSTEMIE
				$result = $conn->query(sprintf("insert into parent (name,surname,email,type) values ('%s' , '%s' ,'%s','t')", mysqli_real_escape_string($conn, $name) , mysqli_real_escape_string($conn, $surname) , mysqli_real_escape_string($conn, $email)));
				$passwd = randomPassword();
				mail($email, "Haslo pierwszego logowania skarbnika", "Twoje hasło pierwszego logowanie to: $passwd");

				// szukamy id nowego rodzica

				if ($result = @$conn->query(sprintf("SELECT * FROM parent WHERE email='%s'", mysqli_real_escape_string($conn, $email))))
				{
					$details = $result->fetch_assoc();
					$parentIDdb = $details['id'];

					// dodanie do username

					$conn->query(sprintf("insert into username (login,password,type,first_login,parent_id) values ('%s' , '$passwd' ,'t',TRUE,'$parentIDdb')", mysqli_real_escape_string($conn, $email)));
					$result = $conn->query(sprintf("insert into class (name,parent_id) values ('%s' ,'$parentIDdb')", mysqli_real_escape_string($conn, $className)));
				}
			}
			else
			{

				// skarbnik ma juz konto w systemie

				$details = $result->fetch_assoc();
				$parentIDdb = $details['id'];
				$result = $conn->query(sprintf("insert into class (name,parent_id) values ('%s' ,'$parentIDdb')", mysqli_real_escape_string($conn, $className)));
				$result = $conn->query(sprintf("update parent set type='t' where id='$parentIDdb'"));
				$result = $conn->query(sprintf("update username set type='t' where login='$email'"));
			}
		}
	}

	$conn->close();
	header('Location: admin_menu/a_addClass.php');
}

function fetch()
{
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = $connect->query(sprintf("SELECT * from class"));
	$output.= '  
      <div class="table-responsive">
           <table class="table table-striped">
		     <thead class="thead-dark">
                <tr>
					<th scope="col">Id</th>
                    <th scope="col">Nazwa</th>
					<th scope="col">Usuń klasę</th>
					<th scope="col">Szczegóły</th>
					<th scope="col">Zmień email</th>
					<th scope="col">Zmień skarbnika</th>
					<th scope="col">Dodaj ucznia do klasy</th>
					<th scope="col">Dodaj uczniów z pliku</th>
                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$output.= '
			<tbody>			
                <tr>  
				
                     <th scope="row">' . $row["id"] . '</th>  
                     <td>' . $row["name"] . '</td>  
					 <td><button type="button" data-id3="' . $row["id"] . '" class="btn_delete btn btn-default">Usuń klasę</button></td>  
					<td><button type="button" data-toggle="modal" data-target="#userModal" data-id3="' . $row["id"] . '" class="btn_details btn btn-default">Szczegóły</button></td>
					<td><button type="button" data-toggle="modal" data-target="#changeTrEmail" data-id3="' . $row["id"] . '" class="btn_trChange btn btn-default">Zmień email</button></td>
					<td><button type="button" data-toggle="modal" data-target="#changeTrModal" data-id3="' . $row["id"] . '" class="btn_trChange btn btn-default">Zmień skarbnika</button></td>
					<td><button type="button" data-toggle="modal" data-target="#addStudentModal" data-id3="' . $row["id"] . '" class="btn_addStudent btn btn-default">Dodaj ucznia</button></td>
					<td><button type="button" data-toggle="modal" data-target="#addStudentCSVModal" data-id3="' . $row["id"] . '" class="btn_addStudentsCSV btn btn-default">Dodaj</button></td>
				</tr>  
				<tbody>
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="4">Nie dodano jeszcze żadnych klas</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}

function deleteFromDB()
{
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	if ($res = $connect->query(sprintf("DELETE FROM class WHERE id = '" . $_POST["id"] . "'")))
	{
		echo 'Pomyślnie usunięto klasę';
	}
}

function showDetails()
{
	require_once "connection.php";

	$connect = new mysqli($servername, $username, $password, $dbName);
	$output = '';
	$result = $connect->query(sprintf("SELECT name, surname, email FROM parent WHERE id = (SELECT parent_id FROM class WHERE id = '" . $_POST["id"] . "')"));
	$className = $connect->query(sprintf("SELECT name FROM class WHERE id = '" . $_POST["id"] . "'"));
	$studentsList = $connect->query(sprintf("SELECT * FROM child WHERE class_id = '" . $_POST["id"] . "'"));
	$res = mysqli_fetch_array($result);
	$output.= '<h2>Szczegóły klasy: ' . mysqli_fetch_array($className) ["name"] . '</h2>
		   <h3 class="text-center">Dane skarbnika</h3>
		   Imię: ' . $res["name"] . ' <br />
		   Nazwisko: ' . $res["surname"] . ' <br />
		   Email: ' . $res["email"] . ' <br />
		   <h3 class="text-center">Lista uczniów</h3>
		   ';
	$output.= '  
      <div>  
           <table>  
                <tr>  
                     <th width="10%">Id</th>  
                     <th width="40%">Imię</th> 
					 <th width="40%">Nazwisko</th>
					 <th width="10%">Data urodzenia</th>
                </tr>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($studentsList))
		{
			$output.= '  
                <tr>  
                     <td>' . $row["id"] . '</td>  
                     <td>' . $row["name"] . '</td>  
					 <td>' . $row["surname"] . '</td>
					 <td>' . $row["date_of_birth"] . '</td>
                </tr>  
           ';
		}
	}
	else
	{
		$output.= 'Nie znaleziono danych';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}

?>