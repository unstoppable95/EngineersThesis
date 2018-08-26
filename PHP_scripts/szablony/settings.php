<html> 
<head> 
	<title>add student </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
</head>

<body>

<div class="menu">
	<a href="index.php" >Strona główna</a>
  <a href="addStudent.php">Dodaj ucznia do klasy</a>
  <a href="addCyclicEvent.php">Dodaj event cykliczny</a>
  <a href="addOnceEvent.php">Dodaj event jednorazowy</a>
  <a href="settings.php" class="active">Ustawienia</a>
  <a href="#" onclick=javascript:logOut()>Wyloguj</a> <!-- Przekierowanie do funkcji wyloguj -->
</div>

<div class="lewa_strona">
	<h1> Ustawienia </h1>
	<h2> Informacja o klasie </h2>
	<h4> Lista uczniów </h4>
	... <br>
	Lista uczniow<br>
	...<br>
	...<br>
	<h4> Dane skarbnika </h4>
	
	<form name="treasuet_data">
	<table>
		<tr><td>Imię: </td><td>....</td></tr> 
		<tr><td>Nazwisko: </td><td>....</td></tr> 
		<tr><td>Email: </td><td>....</td></tr> 
	<table>
	</form>
	<br>
	<h2> Zmien hasło </h2>
	
	<form name="change_password">
	<table>
		<tr><td>Stare haslo: </td><td><input type="password" name="old_passwd"/></td></tr> 
		<tr><td>Nowe hasło: </td><td><input type="password" name="new password" /></td></tr> 
		<tr><td colspan="2"><input type="button" class="btn_commit" value="Zatwierdz"/></td></tr>
	<table>
	</form>


</div>








</body>
</html>