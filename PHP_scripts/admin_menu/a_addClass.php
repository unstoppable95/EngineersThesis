<html>
<head>
	<title>ADMIN-dodaj klase</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="a_style.css" title="Arkusz stylów CSS">
</head>

<?php
	session_start();
	
	if (!isset($_SESSION['loggedIn']))
	{
		header('Location: index.php');
		exit();
	}
	require_once "../admin_helper.php";
			
	if (isset($_SESSION['funAddClass']))
	{
		echo '<script language="javascript">';
		echo 'alert("Dodano klasę i skarbnika! ")';
		echo '</script>';
		$_SESSION['funAddClass']=null;	
		
	}
?>
	
<body>

	<div class="menu">
		<a href="../menu_admin.php">Strona główna</a>
		<a href="a_addClass.php" class="active" >Dodaj klasę</a>
		<a href="a_settings.php">Ustawienia</a>
		<a href="../logout.php">Wyloguj się</a>
	</div>	
		
	
<div class="lewa_strona">

	<h1>Dodaj klasę i skarbnika</h1>
	<form action="../admin_helper.php" method="post">
		<table>
			<tr><td><h4>Klasa: </h4></td><td></td></tr>
			<tr><td>Nazwa klasy: </td><td> <input  name="className" /></td></tr>
			<br>
			<tr><td><h4>Skarbnik: </h4></td><td></td></tr>
			<tr><td>Imię:  </td><td><input  name="name" /> </td></tr>
			<tr><td>Nazwisko: </td><td><input  name="surname" /></td></tr>
			<tr><td>Email: </td><td><input  name="email" /></td></tr>
		</table>
		<input type="submit" name="addClassTreasurer" value=" Zatwierdz"/>
	</form>
</div>
	
</body>
</html>