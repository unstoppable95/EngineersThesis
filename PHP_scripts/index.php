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
</head>
<body>

	<form action="login.php" method="post">
	
		Login: <br /> <input type="text" name="login" /> <br />
		Hasło: <br /> <input type="password" name="password" /> <br /><br />
		<input type="submit" value="Zaloguj się" />
	
	</form>

	<?php
	if(isset($_SESSION['error'])) 
	{
		echo $_SESSION['error'];
	}
	?>
</body>
</html>