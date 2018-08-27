<html>
<head>
<title>MENU </title>
<?php
	session_start();
	

	if (!isset($_SESSION['loggedIn']))
	{
		header('Location: index.php');
		exit();
	}
	require_once "admin_helper.php";
	
	if (isset($_SESSION['funChange']))
	{
		echo '<script language="javascript">';
		echo 'alert("Hasło zostało zmienione! ")';
		echo '</script>';
		$_SESSION['funChange']=null;
	}
	
			
	if (isset($_SESSION['funAddClass']))
	{
		echo '<script language="javascript">';
		echo 'alert("Klasa i skarbnik dodani pomyślnie !")';
		echo '</script>';
		$_SESSION['funAddClass']=null;
	}
?>
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
	
	
	
	<section>
	<h1><u>Klasy </u></h1>
	<form action="admin_helper.php" method="post">
	INFO :
	<textarea cols="50" rows="3" wrap="on"><?php 
	if (isset($_SESSION['funDisplay_1]']))
	{
		echo $_SESSION['funDisplay'];
	}
	?></textarea>
	<input type="submit" name="showClasses" value=" Pokaz"/>
	</p>
	</form>
	</section>
	
</body>
</html>