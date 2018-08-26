<html> 
<head> 
	<title>add student </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<script src="check.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
</head>

<body>

<!-- php include('menu.php'); ?>    -->
<div class="menu">
	<a href="index.php" >Strona główna</a>
  <a href="addStudent.php" class="active">Dodaj ucznia do klasy</a>
  <a href="addCyclicEvent.php">Dodaj event cykliczny</a>
  <a href="addOnceEvent.php">Dodaj event jednorazowy</a>
  <a href="settings.php" >Ustawienia</a>
  <a href="#" onclick=javascript:logOut()>Wyloguj</a> <!-- Przekierowanie do funkcji wyloguj -->
</div> 

<div class="lewa_strona">
	<h1> Dodaj ucznia do klasy ...Ia... </h1> 
	<h3> Dane ucznia </h3>
	<form name="student_data">
	<table>
		<tr><td>Imię: </td><td><input type="text" name="student_name"/></td></tr> 
		<tr><td>Nazwisko: </td><td><input type="text" name="student_surname" /></td></tr> 
		<tr><td>Data urodzenia: </td><td><input type="date" name="student_birthdata" /> </td></tr> 
		<tr><td> </td><td> </td></tr> 
		<tr><td><h3> Dane rodzica </h3></td></tr> 
		<tr><td>Imię: </td><td><input type="text" name="parent_name" /></td></tr>  
		<tr><td>Nazwisko: </td><td><input type="text" name="parent_surname" /></td></tr> 
		<tr><td>Mail: </td><td><input type="text" name="parent_mail" /></td></tr> 
	
		<tr><td colspan="2"><input type="button" class="btn_add" value="Zatwierdz" onclick="return validate(this.form);"/></td></tr>
	<table>
	</form>


</div>








</body>
</html>