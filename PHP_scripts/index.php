<html>
<head>
<title>System skarbnik klasowy-panel logowania</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="index_style.css" title="Arkusz stylów CSS">

<?php
session_start();

if ((isset($_SESSION['loggedIn'])) && ($_SESSION['loggedIn'] == true))
{
	if ($_SESSION['type'] == "a")
	{
		header('Location: menu_admin.php');
	}

	if ($_SESSION['type'] == "p" || $_SESSION['treasurerAsParent'] == true)
	{
		header('Location: menu_parent.php');
	}

	if ($_SESSION['type'] == "t" && $_SESSION['treasurerAsParent'] == false)
	{
		header('Location: menu_treasurer.php');
	}

	exit();
}

?>
</head>

<body>
	<form action="login.php" method="post">
	<div class="container">
	<div class="vertical-al">
		<h2> Zaloguj się </h2>
		<p>
		Login: <br /> <input type="text" name="login" /> <br /> 
		Hasło: <br /> <input type="password" name="password" /> <br /><br />
		<input type="submit" value="Zaloguj się" />
		</p>
		<?php

if (isset($_SESSION['error']))
{
	echo $_SESSION['error'];
}

?>
		</div>
	</div>
	</form>

</body>
</html>