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
        echo "Blad: " . $conn->connect_errno; 
    } else { 
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
	
    header('Location: logout.php');
    
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
        echo "Blad: " . $conn->connect_errno;
    } else { 
        
        $className = $_POST['className'];
        $className = htmlentities($className, ENT_QUOTES, "UTF-8");
        $name      = $_POST['name'];
        $name      = htmlentities($name, ENT_QUOTES, "UTF-8");
        $surname   = $_POST['surname'];
        $surname   = htmlentities($surname, ENT_QUOTES, "UTF-8");
        $email     = $_POST['email'];
        $email     = htmlentities($email, ENT_QUOTES, "UTF-8");
        	
			if ($result = @$conn->query(sprintf("SELECT * FROM parent WHERE email='%s'", mysqli_real_escape_string($conn, $email))))
			{
			$isUser = $result->num_rows;
			if ($isUser <= 0)
				{ //SKARBNIKA NIE MA W SYSTEMIE
				if ($result = $conn->query(sprintf("insert into parent (name,surname,email,type) values ('%s' , '%s' ,'%s','t')", mysqli_real_escape_string($conn, $name) , mysqli_real_escape_string($conn, $surname) , mysqli_real_escape_string($conn, $email))))
					{
					echo "Record inserted successfully";
					}
				  else
					{
					echo "Error inserted record: " . $conn->error . "     " . $conn->connect_error . "     " . $conn->connect_errno;
					}
				mail($email, "Haslo pierwszego logowania skarbnika" , "Twoje hasło pierwszego logowanie to: 12345");
				// szukamy id nowego rodzica

				if ($result = @$conn->query(sprintf("SELECT * FROM parent WHERE email='%s'", mysqli_real_escape_string($conn, $email))))
					{
					$details = $result->fetch_assoc();
					$parentIDdb = $details['id'];
					if ($result = $conn->query(sprintf("insert into class (name,parent_id) values ('%s' ,'$parentIDdb')", mysqli_real_escape_string($conn, $className))))
						{
						echo "Record inserted successfully";
						}
					  else
						{
						echo "Error inserted record: " . $conn->error . "     " . $conn->connect_error . "     " . $conn->connect_errno;
						}
					}
				}
			else {
				//skarbnik ma juz konto w systemie
					$details = $result->fetch_assoc();
					$parentIDdb = $details['id'];
					if ($result = $conn->query(sprintf("insert into class (name,parent_id) values ('%s' ,'$parentIDdb')", mysqli_real_escape_string($conn, $className))) && $result = $conn->query(sprintf("update parent set type='t' where id='$parentIDdb'"))&& $result = $conn->query(sprintf("update username set type='t' where login='$email'")) )
						{
						echo "Record inserted successfully";
						}
					  else
						{
						echo "Error inserted record: " . $conn->error . "     " . $conn->connect_error . "     " . $conn->connect_errno;
						}
									
			}
								
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
        echo "Blad: " . $conn->connect_errno;
    } else { 
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