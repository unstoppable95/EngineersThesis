<html>
<head>
	<title>MENU </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="admin_menu/a_style.css" title="Arkusz stylów CSS">
</head>
<?php
	session_start();
	
	if (!isset($_SESSION['loggedIn']))
	{
		header('Location: index.php');
		exit();
	}
	require_once "admin_helper.php";
	
?>
	
<body>

	<div class="menu">
		<a href="#" class="active">Strona główna</a>
		<a href="admin_menu/a_addClass.php">Dodaj klasę</a>
		<a href="admin_menu/a_settings.php">Ustawienia</a>
		<a href="logout.php">Wyloguj się</a>
	</div>	
		
	
<div class="lewa_strona">

	<h1>Konto administratora</h1>
	<h3>Lista klas w szkole</h3>
	<form action="admin_helper.php" method="post">
		<textarea cols="50" rows="3" wrap="on">
			<?php 
			if (isset($_SESSION['funDisplay_1]']))
			{
				echo $_SESSION['funDisplay'];
			}
			?></textarea>
		<input type="submit" name="showClasses" value=" Pokaz"/>
	</form>

</div>
	
</body>
</html>