<html> 
<head> 
	<title>settings </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="p_style.css" title="Arkusz stylów CSS">
</head>

<body>

<div class="menu">
	<a href="../p_index.php" >Strona główna</a>
  <a href="p_choiceChild.php">Wybór dziecka</a>
  <a href="p_history.php">Historia wpłat</a>
  <a href="p_classAccount.php">Konto klasowe</a>
  <a href="p_settings.php" class="active">Ustawienia</a>
  <a href="#">Wyloguj </a> <!-- Przekierowanie do funkcji wyloguj -->
</div>

<div class="lewa_strona">
	<h1> Ustawienia </h1>
	<h2> Informacja o dzieciach </h2>
	<h4> Lista dzieci </h4>
	... <br>
	Lista dzieci<br>
	...<br>
	...<br>
	<h4> Dane rodzica </h4>
	
	<form name="parent_data">
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