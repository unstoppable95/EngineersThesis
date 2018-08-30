<html> 
<head> 
	<title>Skarbnik-wydarzenia cykliczne</title>
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
	<a href="menu_parent.php">Moje dzieci</a>
  <a href="addStudent.php" >Dodaj ucznia do klasy</a>
  <a href="addCyclicEvent.php" class="active">Dodaj event cykliczny</a>
  <a href="addOnceEvent.php" >Dodaj event jednorazowy</a>
  <a href="settings.php" >Ustawienia</a>
  <a href="../logout.php"> Wyloguj się</a>
</div> 

<div class="lewa_strona">
	<h1> Dodaj wydarzenie cykliczne </h1>


</div>








</body>
</html>