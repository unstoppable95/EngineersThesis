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
		echo "Blad: " . $conn->connect_errno; // " Opis bledu: ".$conn->connect_error;
		}
	  else
		{ //polaczenie spoko :)
		$newPassword = $_POST['newPassword'];
		$newPassword = htmlentities($newPassword, ENT_QUOTES, "UTF-8");
		$login = $_SESSION['user'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		if ($result = $conn->query(sprintf("UPDATE username SET password='%s' WHERE login='%s'", mysqli_real_escape_string($conn, $newPassword) , mysqli_real_escape_string($conn, $login))))
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
		echo "Blad: " . $conn->connect_errno; // " Opis bledu: ".$conn->connect_error;
		}
	  else
		{ //polaczenie spoko :)
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

function addChildParent()
	{
	session_start();
	if (empty($_POST['childName']) || $_POST['childName'] == '0' || empty($_POST['childSurname']) || $_POST['childSurname'] == '0' || empty($_POST['childBirthdate']) || $_POST['childBirthdate'] == '0' || empty($_POST['parentName']) || $_POST['parentName'] == '0' || empty($_POST['parentSurname']) || $_POST['parentSurname'] == '0' || empty($_POST['parentEmail']) || $_POST['parentEmail'] == '0')
		{
		header('Location: treasuer_menu/addStudent.php');
		exit();
		}

	require_once "connection.php";

	$conn = new mysqli($servername, $username, $password, $dbName);
	if ($conn->connect_errno != 0)
		{
		echo "Blad: " . $conn->connect_errno; // " Opis bledu: ".$conn->connect_error;
		}
	  else
		{ //polaczenie spoko :)
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
		$parentEmail = $_POST['parentEmail'];
		$parentEmail = htmlentities($parentEmail, ENT_QUOTES, "UTF-8");
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
				mail($parentEmail, "Haslo pierwszego logowania rodzica" , "Twoje hasło pierwszego logowanie to: 12345");
				// szukamy id nowego rodzica

				if ($result = @$conn->query(sprintf("SELECT * FROM parent WHERE email='%s'", mysqli_real_escape_string($conn, $parentEmail))))
					{
					$details = $result->fetch_assoc();
					$parentIDdb = $details['id'];
					if ($result = $conn->query(sprintf("insert into child (name,surname,date_of_birth,parent_id) values ('%s' , '%s' ,'%s','$parentIDdb')", mysqli_real_escape_string($conn, $childName) , mysqli_real_escape_string($conn, $childSurname) , mysqli_real_escape_string($conn, $childBirthdate))))
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
				if ($result = $conn->query(sprintf("insert into child (name,surname,date_of_birth,parent_id) values ('%s' , '%s' ,'%s','$parentIDdb')", mysqli_real_escape_string($conn, $childName) , mysqli_real_escape_string($conn, $childSurname) , mysqli_real_escape_string($conn, $childBirthdate))))
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