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
		echo "<p>Witaj zalogowales sie poprawnie jako : ".$_SESSION['user'].'! [ <a href="logout.php">Wyloguj się!</a> ]</p>';
		?>

</body>
</html>