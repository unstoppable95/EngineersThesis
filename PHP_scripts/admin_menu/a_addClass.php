<html>
<head>
    <title>ADMIN-dodaj klase</title>
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

if (isset($_SESSION['funAddClass'])) {
    echo '<script language="javascript">';
    echo 'alert("Dodano klasę i skarbnika! ")';
    echo '</script>';
    $_SESSION['funAddClass'] = null;
    
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
		<li class="active"><a href="a_addClass.php" >Dodaj klasę</a></li>
		<li><a href="a_settings.php">Ustawienia</a></li>
	</ul>
	<ul class="nav navbar-nav navbar-right">
		<li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span>Wyloguj się</a></li>  
	</ul>
	<div>	
</nav>  
        
    
<h3>Dodaj klasę i skarbnika</h3>
<div class="container new-class-form">
<form action="../admin_helper.php" class="form-vertical" method="post">
	<div class="form-group row"><div class="col-md-7 col-centered">
		<label for="className">Nazwa klasy</label> 
		
		<input type="text" name="className" class="form-control"/>
		</div>
    </div>
	<div class="row">
	<h5>Dane skarbnika klasy</h5>
	</div>
	<div class="form-group row">
		<div class="col-md-7 col-centered">
		<label for="name">Imię</label> 
        <input type="text" name="name" class="form-control"/>
		</div><div class="col-md-7 col-centered">
		<label for="name">Nazwisko</label> 
		
        <input type="text" name="surname" class="form-control"/>
		</div><div class="col-md-7 col-centered">
		<label for="email">Email</label> 
        
		<input type="text" name="email" class="form-control"/>
     </div>
	 </div>
	 <div class="text-center row">
	 <button type="submit" name="addClassTreasurer" class="btn btn-primary">Zatwierdz</button>
	</div>
	 </form>
</div>
    
</body>
</html>