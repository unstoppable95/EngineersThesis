<?php
	session_start();
	
	
	if (!isset($_SESSION['loggedIn']))
	{
		header('Location: index.php');
		exit();
	}
	

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
	<form action="admin_helper.php" method="post">
	<p>Zmien haslo dostępu : <input type="newpassword" name="newpassword" /><input type="submit" value=" Zatwierdz" /></p>
	</form>
	</section>
	
	
</body
</html>