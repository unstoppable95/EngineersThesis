 <?php

if ((isset($_POST['changePassword']))) {
    changePassword();
}

if ((isset($_POST['addClassTreasurer']))) {
    addClassTreasurer();
}

if ((isset($_POST['showClasses']))) {
    displayClass();
}


function changePassword()
{
    session_start();
    if (empty($_POST['newPassword']) || $_POST['newPassword'] == '0') {
        header('Location: menu_admin.php');
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
        
        if ($result = @$conn->query(sprintf("UPDATE username SET password='%s' WHERE login='%s'", mysqli_real_escape_string($conn, $newPassword), mysqli_real_escape_string($conn, $login)))) {
            echo "Record updated successfully";
			$_SESSION['funChange']=true;
		
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    $conn->close();
	
    header('Location: admin_menu/a_settings.php');
    
}
//----------------------------------------------------------------
function addClassTreasurer()
{
    session_start();
    if (empty($_POST['className']) || $_POST['className'] == '0' || empty($_POST['name']) || $_POST['name'] == '0' || empty($_POST['surname']) || $_POST['surname'] == '0' || empty($_POST['email']) || $_POST['email'] == '0') {
        header('Location: menu_admin.php');
        exit();
    }
    require_once "connection.php";
    $conn = new mysqli($servername, $username, $password, $dbName);
    
    if ($conn->connect_errno != 0) {
        echo "Blad: " . $conn->connect_errno; // " Opis bledu: ".$conn->connect_error;
    } else { //polaczenie spoko :) 
        
        $className = $_POST['className'];
        $className = htmlentities($className, ENT_QUOTES, "UTF-8");
        $name      = $_POST['name'];
        $name      = htmlentities($name, ENT_QUOTES, "UTF-8");
        $surname   = $_POST['surname'];
        $surname   = htmlentities($surname, ENT_QUOTES, "UTF-8");
        $email     = $_POST['email'];
        $email     = htmlentities($email, ENT_QUOTES, "UTF-8");
        
        
        if ($conn->query(sprintf("INSERT INTO class (name) values ('%s')", mysqli_real_escape_string($conn, $className))) && $conn->query(sprintf("INSERT INTO parent (name,surname,email) values( '%s', '%s' , '%s')", mysqli_real_escape_string($conn, $name), mysqli_real_escape_string($conn, $surname), mysqli_real_escape_string($conn, $email)))) {
            //(adresat, temat, wiadomość[, nagłówki[, parametry]]
			mail($email, "Haslo pierwszego logowania skarbnika" , "Twoje hasło pierwszego logowanie to: 12345");
			echo "Record updated successfully";
			$_SESSION['funAddClass']=true;
			
			
        } else {
            echo "Error updating record: " . $conn->error;
        }
        
        
    }
	
    $conn->close();

    header('Location: admin_menu/a_addClass.php');
    
}
//-------------
function displayClass()
{
    session_start();
  
    require_once "connection.php";
    $conn = new mysqli($servername, $username, $password, $dbName);
    
    if ($conn->connect_errno != 0) {
        echo "Blad: " . $conn->connect_errno; // " Opis bledu: ".$conn->connect_error;
    } else { //polaczenie spoko :) 
        $result=$conn->query("select * from class");
        if ( $result){
            echo "Record updated successfully";
			$text="";
			while($event=mysqli_fetch_assoc($result)){
				
				$text= $text.$event['id']." ".$event['name']."\r\n";	
			}
			$_SESSION['funDisplay']=$text;
			$_SESSION['funDisplay_1]']=true;
	}
			
			
			
         else {
            echo "Error updating record: " . $conn->error;
        }        
    }
    $conn->close();
    
   header('Location: menu_admin.php');
    
}



?> 