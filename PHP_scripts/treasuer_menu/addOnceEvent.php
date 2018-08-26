<html> 
<head> 
	<title>add once event </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
</head>

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
	<form name="add_event">
	<table>
		<tr><td>Nazwa: </td><td><input type="text" name="event_name"/></td></tr> 
		<tr><td>Cena: </td><td><input type="text" name="event_pricee" /></td></tr> 
		<tr><td>Data: </td><td><input type="date" name="event_date" /> </td></tr> 
	
		<tr><td colspan="2"><input type="button" class="btn_add" value="Zatwierdz" onclick="return validate(this.form);"/></td></tr>
	<table>
	</form>


</div>








</body>
</html>