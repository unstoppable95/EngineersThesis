<html>
<head>
	<title>ADMIN-ustawienia</title>
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
	
	if (isset($_SESSION['funChange']))
	{
		echo '<script language="javascript">';
		echo 'alert("Hasło zostało zmienione! ")';
		echo '</script>';
		$_SESSION['funChange']=null;
	}
?>
	
<body>

	<div class="menu">
		<a href="../menu_admin.php">Strona główna</a>
		<a href="a_addClass.php" >Dodaj klasę</a>
		<a href="a_settings.php" class="active">Ustawienia</a>
		<a href="../logout.php">Wyloguj się</a>
	</div>	
		
	
<div class="lewa_strona">
			
	
	<h1>Konto administratora</h1>
	<h3>Zmiana hasła</h3>
	<form action="../admin_helper.php" method="post">
		<table>
				<tr><td>Nowe hasło dostępu:</td><td><input type="password" name="newPassword" /></td></tr>
		</table>
		<input type="submit" name="changePassword" value=" Zatwierdz"/>	
	</form>

</div>
	
</body>
</html>


