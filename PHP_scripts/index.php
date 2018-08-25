<?php

	session_start();
	if((isset($_SESSION['loggedIn'])) && ($_SESSION['loggedIn']==true))
	{
		header('Location: menu.php');
		//opuszczenie pliku
		exit();
	}
?>

<html>
<head>
<title>PANEL LOGOWANIA</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="index_style.css" title="Arkusz stylów CSS">




</head>
<body>

	<form action="login.php" method="post">
	<div class="container">
	<div class="vertical-al">
		<h3> Zaloguj się </h3>
		<p>
		Login: <br /> <input type="text" name="login" /> <br /> 
		Hasło: <br /> <input type="password" name="password" /> <br /><br />
		<input type="submit" value="Zaloguj się" />
		</p>
		</div>
	</div>
	</form>

	<?php
	if(isset($_SESSION['error'])) 
	{
		echo $_SESSION['error'];
	}
	?>
</body>
</html>