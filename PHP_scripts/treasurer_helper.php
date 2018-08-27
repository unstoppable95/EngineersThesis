 <?php

if ((isset($_POST['changePassword']))) {
    changePassword();
}

if ((isset($_POST['addEvent']))) {
    addEvent();
}


function changePassword()
{
    session_start();
    if (empty($_POST['newPassword']) || $_POST['newPassword'] == '0') {
        header('Location: treasuer_menu/settings.php');
        exit();
    }
    require_once "connection.php";
    $conn = new mysqli($servername, $username, $password, $dbName);
    
    if ($conn->connect_errno != 0) {
        echo "Blad: " . $conn->connect_errno; // " Opis bledu: ".$conn->connect_error;
    } else { //polaczenie spoko :) 
        $newPassword = $_POST['newPassword'];
        $newPassword = htmlentities($newPassword, ENT_QUOTES, "UTF-8");
        $login       = $_SESSION['user'];
        $login       = htmlentities($login, ENT_QUOTES, "UTF-8");
        
        if ($result = $conn->query(sprintf("UPDATE username SET password='%s' WHERE login='%s'", mysqli_real_escape_string($conn, $newPassword), mysqli_real_escape_string($conn, $login)))) {
            echo "Record updated successfully";
		
		
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    $conn->close();
	
    header('Location: treasuer_menu/settings.php');
    
}
//-----------------------------------
function addEvent()
{
    session_start();
    if (empty($_POST['eventName']) || $_POST['eventName'] == '0' || empty($_POST['eventPrice']) || $_POST['eventPrice'] == '0' || empty($_POST['eventDate']) || $_POST['eventDate'] == '0' ) {
        header('Location: treasuer_menu/addOnceEvent.php');
        exit();
    }
    require_once "connection.php";
    $conn = new mysqli($servername, $username, $password, $dbName);
    
    if ($conn->connect_errno != 0) {
        echo "Blad: " . $conn->connect_errno; // " Opis bledu: ".$conn->connect_error;
    } else { //polaczenie spoko :) 
			$eventName=$_POST['eventName'];
			 $eventName = htmlentities($eventName, ENT_QUOTES, "UTF-8");
			$eventPrice = $_POST['eventPrice'];
			 $eventPrice= htmlentities($eventPrice, ENT_QUOTES, "UTF-8");
			$eventDate =  $_POST['eventDate'];
			 $eventDate= htmlentities($eventDate, ENT_QUOTES, "UTF-8");
			 
        if ($result = $conn->query(sprintf("insert into event (name,price,date) values ('%s' , '%s' ,'%s')", mysqli_real_escape_string($conn, $eventName), mysqli_real_escape_string($conn, $eventPrice), mysqli_real_escape_string($conn, $eventDate)))) {
            echo "Record updated successfully";
			
		
        } else {
            echo "Error updating record: " . $conn->error."     ".$conn->connect_error."     ".$conn->connect_errno;;
			
        }
    }
    $conn->close();
	
    header('Location: treasuer_menu/addOnceEvent.php');
    
}


?> 