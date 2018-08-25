<?php
	session_start();
	
	
	if (!isset($_SESSION['loggedIn']))
	{
		header('Location: index.php');
		exit();
	}
	require_once "admin_helper.php";

?>



<html>
<head>
<title>MENU </title>

</head>
<body>

		<?php
	echo "<p>Witaj zalogowales sie poprawnie jako : ".$_SESSION['user'] //.'! [ <a href="logout.php">Wyloguj się!</a> ]</p>';
		?>
		<br><br>
	<a href="logout.php"><button> Wyloguj się</button></a>
	
	
	<section>
	<h1><u>Zmiana hasła</u></h1>
	<form action="admin_helper.php" method="post">
	<p>Nowe hasło dostępu : <input type="password" name="newPassword" />
	<input type="submit" name="changePassword" value=" Zatwierdz"/>
		</p>
	</form>
	</section>
	
	<section>
	<h1><u>Dodaj klasę i skarbnika</u></h1>
	<form action="admin_helper.php" method="post">
	<p>Nazwa klasy : <input  name="className" /></br>
	Skarbnik</br>
	Imię :  <input  name="name" />     </br>
	Nazwisko :  <input  name="surname" />     </br>
	Email :  <input  name="email" />     </br>
	<input type="submit" name="addClassTreasurer" value=" Zatwierdz"/>
	</p>
	</form>
	</section>
	
	
</body
</html>