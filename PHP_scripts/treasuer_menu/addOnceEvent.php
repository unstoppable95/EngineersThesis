

<html> 
<head> 
	<title>add once event </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
</head>
<?php
session_start();

if (!isset($_SESSION['loggedIn']))
	{
		header('Location: ../index.php');
		exit();
	}
	require_once "../treasurer_helper.php";

?>
<body>

<div class="menu">
	<a href="../menu_treasurer.php" >Strona główna</a>
  <a href="addStudent.php" >Dodaj ucznia do klasy</a>
  <a href="addCyclicEvent.php">Dodaj event cykliczny</a>
  <a href="addOnceEvent.php" class="active">Dodaj event jednorazowy</a>
  <a href="settings.php" >Ustawienia</a>
  <a href="../logout.php"> Wyloguj się</a>
</div> 

<div class="lewa_strona">
	<h1> Dodaj pojednycze wydarzenie </h1>
	<form action="../treasurer_helper.php" method="post">
	<table>
		<tr><td>Nazwa: </td><td><input type="text" name="eventName"/></td></tr> 
		<tr><td>Cena: </td><td><input type="text" name="eventPrice" /></td></tr> 
		<tr><td>Data: </td><td><input type="date" placeholder="YYYY-MM-DD" name="eventDate" /> </td></tr> 
		<tr><td colspan="2"><input type="submit" name="addEvent" class="btn_add" value="Zatwierdz"  onclick="return validate(this.form);"/></td></tr>
	<table>
	</form>


</div>








</body>
</html>