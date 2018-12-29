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
if ((isset($_POST['deleteEvent'])))
{
	deleteFromDB();
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
		
	case 'saveIDToDelete':
		saveIDToDelete();
		break;
	}
}

function saveClassID()
{
	session_start();
	$_SESSION['classIDCSV'] = $_POST["id"];
	//debug_to_console("Ustawiono id csv");
}
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}
function saveIDToDelete()
{
	session_start();
	$_SESSION['classToDelete'] = $_POST["id"];
	//debug_to_console("Ustawiono". $_SESSION['classToDelete']. "dupa");
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

	$conn = new MyDB();
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

	$conn = new MyDB();
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

	$conn = new MyDB();
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

	$conn = new MyDB();
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

	$conn = new MyDB();
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

	$conn = new MyDB();
	if ($conn->connect_errno != 0)
	{
		echo "Blad: " . $conn->connect_errno;
	}
	else
	{
			if ($result = @$conn->query(sprintf("SELECT * FROM username WHERE login='%s' AND password='%s'", mysqli_real_escape_string($conn, $_SESSION['user']) , mysqli_real_escape_string($conn, $_POST['oldPassword'])))){
			$userCount = $result->num_rows;
			if ($userCount > 0)
			{
				$newPassword = $_POST['newPassword'];
				$newPassword = htmlentities($newPassword, ENT_QUOTES, "UTF-8");
				$reNewPassword = $_POST['reNewPassword'];
				$reNewPassword = htmlentities($reNewPassword, ENT_QUOTES, "UTF-8");
				if($newPassword==$reNewPassword){
					$login = $_SESSION['user'];
					$login = htmlentities($login, ENT_QUOTES, "UTF-8");
					$result = $conn->query(sprintf("UPDATE username SET password='%s',first_login=FALSE WHERE login='%s'", mysqli_real_escape_string($conn, $newPassword) , mysqli_real_escape_string($conn, $login)));
					$_SESSION['errorChangePassword'] ='';
				}
				else{
					$_SESSION['errorChangePassword'] = 'Nowe hasło i powtórzone nowe hasło muszą być takie same!';
					header('Location: treasuer_menu/settings.php');
					echo "Nowe hasło i powtórzone nowe hasło muszą być takie same"; 
				//nowe i powtorzone musza byc takie same
				}
			}
		else{
			$_SESSION['errorChangePassword'] = 'Stare hasło jest błędne';
			header('Location: treasuer_menu/settings.php');
			echo "Stare hasło jest błędne"; 
			//złe stare hasło
			}
	}
	}

	$conn->close();
	header('Location: admin_menu/a_settings.php');
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

	$conn = new MyDB();
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
	session_start();
	require_once "connection.php";

	$conn = new MyDB();
	
	$output = '';
		
	$result = $conn->query(sprintf("SELECT parent.id as treasuer_id,class.id as id,class.name as class_name, parent.name as parent_name , parent.surname as surname FROM class left join parent on class.parent_id=parent.id where class.school_year_id=".$_SESSION["school_year_id"]." order by class.name"));
	$output.= '  
      <div class="table-responsive">
           <table class="table table-striped table-bordered">
		     <thead class="thead-dark">
                <tr>
					<!--<th scope="col">Id</th>-->
                    <th scope="col">Nazwa</th>
					<th scope="col">Lista uczniów</th>
					
					<th scope="col" colspan="2">Skarbnik</th>
					<th scope="col" colspan="2">Dodaj</th>
					
					<!--<th scope="col">Zmień email</th>
					<th scope="col">Zmień skarbnika</th>
					
					<th scope="col">Dodaj ucznia do klasy</th>
					<th scope="col">Dodaj uczniów z pliku</th>-->
					
					<th scope="col">Usuń klasę</th>

                </tr>
				<thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$email = $conn->query(sprintf("select email from parent where id=". $row["treasuer_id"].";"));
			$treasuer_email = mysqli_fetch_array($email);
			$output.= '
			<tbody>			
                <tr>  
                  <!--   <th scope="row">' . $row["id"] . '</th>  -->
                     <td>' . $row["class_name"] . '</td>  
					<td><button type="button" data-toggle="modal" data-target="#userModal" data-id3="' . $row["id"] . '" class="btn_details btn btn-default">Wyświetl</button></td>
					<td>' . $row["parent_name"] .' '. $row["surname"].'<p><a href="mailto:'.$treasuer_email["email"].'">'.$treasuer_email["email"].'</a></p></td>
					<td><button type="button" data-toggle="modal" data-target="#changeTrEmail" data-id3="' . $row["id"] . '" class="btn_trChange btn btn-default">Zmień email</button>
					<button type="button" data-toggle="modal" data-target="#changeTrModal" data-id3="' . $row["id"] . '" class="btn_trChange btn btn-default">Zmień skarbnika</button></td>
					<td><button type="button" onclick="window.open(\'./admin_menu/addStudent.php\',\'_self\')" data-id3="' . $row["id"] . '" class="btn_addStudent btn btn-default">Ucznia</button></td>
					<td><button type="button" data-toggle="modal" data-target="#addStudentCSVModal" data-id3="' . $row["id"] . '" class="btn_addStudentsCSV btn btn-default">Z pliku</button></td>
					<td><button type="button" data-toggle="modal" data-target="#eventDeleteModal" data-id3="' . $row["id"] . '" class="btn_delete_class btn btn-default">Usuń</button></td> 
				</tr>  
			<tbody>
           ';
		}
	}
	else
	{
		$output.= '<tr>  
                          <td colspan="7">Nie dodano jeszcze żadnych klas</td>  
                     </tr>';
	}

	$output.= '</table>  
      </div>';
	echo $output;
}

function deleteFromDB()
{
	 if(!session_id()) {
        session_start();
    }

	require_once "connection.php";
	$conn = new MyDB();
	
	if ($res = $conn->query(sprintf("DELETE FROM class WHERE id = '" . 	$_SESSION['classToDelete'] . "'")))
	{
		//echo 'Pomyślnie usunieto klase'. $_SESSION['classToDelete'] . 'lalal';
		//header('Location: menu_admin.php');
		header('Location: menu_admin.php');
	}

	$conn->close();
}
/*
function showTreasuerData(){
	require_once "connection.php";

	$conn = new MyDB();
	$output = '';
	$result = $conn->query(sprintf("SELECT name, surname, email FROM parent WHERE id = (SELECT parent_id FROM class WHERE id = '" . $_POST["id"] . "and school_year_id=".$_SESSION["school_year_id"].";)"));
	$res = mysqli_fetch_array($result);
	$output.= '
		   Imię: ' . $res["name"] . '
		   Nazwisko: ' . $res["surname"] . ' 
		   ';
	echo $output;
}

*/
function showDetails()
{
	require_once "connection.php";
	session_start();
	$conn = new MyDB();
	$output = '';
	$result = $conn->query(sprintf("SELECT name, surname, email FROM parent WHERE id = (SELECT parent_id FROM class WHERE id = " . $_POST["id"] . " and school_year_id=".$_SESSION['school_year_id'].")"));
	$className = $conn->query(sprintf("SELECT name FROM class WHERE id = '" . $_POST["id"] . "'"));
	$studentsList = $conn->query(sprintf("SELECT * FROM child WHERE class_id = '" . $_POST["id"] . "' order by surname"));
	$res = mysqli_fetch_array($result);
	$output.= '<h5>Lista uczniów klasy: ' . mysqli_fetch_array($className) ["name"] . '</h5>

		   ';
	$output.= '  
      <div class="table-responsive">
		<table class="table table-striped table-bordered">
		    <thead class="thead-dark"> 
                <tr>  
                     <th scope="col">Imię Nazwisko</th> 
					 <th scope="col">Data urodzenia</th>
					 <th scope="col">Rodzic</th>
					 <th scope="col">Email</th>
                </tr>
			</thead>';
	if (mysqli_num_rows($result) > 0)
	{
		while ($row = mysqli_fetch_array($studentsList))
		{
			$parentTMP = $conn->query(sprintf("SELECT * FROM parent WHERE id = (SELECT parent_id FROM child WHERE id = " . $row["id"] . ")"));
			$parent = mysqli_fetch_array($parentTMP);
			$output.= '  
			<tbody>
                <tr>  
                     <td>' . $row["name"] . ' ' . $row["surname"] .'</td>  
					 <td>' . $row["date_of_birth"] . '</td>
					 <td>' . $parent["name"] . ' ' . $parent["surname"] . '</td>
					 <td><a href="mailto:' . $parent["email"] . '">' . $parent["email"] . '</a>
					 </td>				 
                </tr>  
			<tbody>
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