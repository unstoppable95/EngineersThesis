<html> 
<head> 
	<title>Skarbnik-dodawania ucznia i rodzica</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<script src="check.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
</head>
<?php
session_start();

if (!isset($_SESSION['loggedIn']))
	{
		header('Location: ../index.php');
		exit();
	}

?>
<body>

<div class="menu">
	<a href="../menu_treasurer.php" >Strona główna</a>
	<a href="../menu_parent.php">Moje dzieci</a>
	<a href="expenses.php">Wydatki klasowe</a>
  <a href="addStudent.php" class="active">Dodaj ucznia do klasy</a>
  <a href="addOnceEvent.php">Dodaj wydarzenie</a>
  <a href="settings.php" >Ustawienia</a>
  <a href="../logout.php"> Wyloguj się</a>
</div> 

<div class="lewa_strona">
	<h1> Dodaj ucznia do klasy</h1> 
	<h3> Dane ucznia </h3>
	<form action="../treasurer_helper.php" method="post">
	<table>
		<tr><td>Imię: </td><td><input type="text" name="childName"/></td></tr> 
		<tr><td>Nazwisko: </td><td><input type="text" name="childSurname" /></td></tr> 
		<tr><td>Data urodzenia: </td><td><input type="date" name="childBirthdate" /> </td></tr> 
		<tr><td> </td><td> </td></tr> 
		<tr><td><h3> Dane rodzica </h3></td></tr> 
		<tr><td>Imię: </td><td><input type="text" name="parentName" /></td></tr>  
		<tr><td>Nazwisko: </td><td><input type="text" name="parentSurname" /></td></tr> 
		<tr><td>Mail: </td><td><input type="text" name="parentEmail" /></td></tr> 
	
		<tr><td colspan="2"><input type="submit" name="addChildParent" class="btn_add" value="Zatwierdz" onclick="return validate(this.form);"/></td></tr>
	<table>
	</form>


</div>








</body>
</html>