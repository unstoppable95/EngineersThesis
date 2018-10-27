<html>
<head>
    <title>ADMIN-ustawienia</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
	<script src="../js/jquery-2.2.4.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 	<link rel="stylesheet" type="text/css" href="a_style.css" title="Arkusz stylów CSS">
</head>
<?php
session_start();

if (!isset($_SESSION['loggedIn'])) {
    header('Location: index.php');
    exit();
}
require_once "../admin_helper.php";

if (isset($_SESSION['funChange'])) {
    echo '<script language="javascript">';
    echo 'alert("Hasło zostało zmienione! ")';
    echo '</script>';
    $_SESSION['funChange'] = null;
}
?>

<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Konto administratora</a>
    </div>

	<ul class="nav navbar-nav">
	<li><a href="../menu_admin.php">Strona główna</a></li>
			  <li><a href="a_addClass.php" >Dodaj klasę</a></li>
		   <li class="active"><a href="a_settings.php">Ustawienia</a></li>
	</ul>
	<ul class="nav navbar-nav navbar-right">
		<li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span>Wyloguj się</a></li>  
	</ul>
	<div>	
</nav>
	    

   
<h3>Zmiana hasła</h3>
<div class="container new-class-form">

<form action="../admin_helper.php" class="form-vertical" method="post">
	<div class="form-group row">
<div class="col-md-4 col-centered">
        <label for="className">Nowe hasło dostępu</label>  
		<input type="password" name="newPassword" class="form-control" />
	</div>
	</div>
	 <div class="text-center row">
		<button type="submit" name="changePassword" class="btn btn-primary">Zatwierdz</button>
</div>
		</form>
</div>

</body>
</html>