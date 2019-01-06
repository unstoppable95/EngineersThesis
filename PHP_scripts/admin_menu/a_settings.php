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
<html>
	<head>
		<title>ADMIN-ustawienia</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!-- Our CSS -->
	<link rel="stylesheet" type="text/css" href="a_style.css" title="Arkusz stylów CSS">

	<!-- Example of icons -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>



<body>

	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
		<a class="navbar-brand">Konto administratora</a>
			<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="../menu_admin.php">Strona główna</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="a_addClass.php">Dodaj klasę</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="a_settings.php">Ustawienia<span class="sr-only">(current)</span></a>
				</li>
			</ul>

			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i>Wyloguj się</a>
			</li>
			</ul>
	  </div>
	</nav>    


   
	<h3 class="text-center">Zmiana hasła</h3>

	<div class="container">
		<form action="../admin_helper.php" class="form-vertical justify-content-center" method="post">
			<div class="form-group row">
				<div class="col-md-6 offset-sm-3">
					<label for="oldPassword" class="text-center col-form-label">Stare hasło:</label>
					<input type="password" name="oldPassword" class="form-control" required />
					<label for="newPassword" class="text-center col-form-label" required>Nowe hasło:</label>
					<input type="password" name="newPassword" class="form-control"/>
					<label for="reNewPassword" class="text-center col-form-label" required>Powtórz nowe hasło:</label>
					<input type="password" name="reNewPassword" class="form-control"/>
			</div>
			<div class="col-md-8 offset-sm-2 text-center text-danger" >
				<?php
					if (isset($_SESSION['errorChangePassword']))
					{
						echo $_SESSION['errorChangePassword'];
						unset($_SESSION['errorChangePassword']);

					}
				?>
				</div>
		</div>
		<div class="row text-center">
			<div class="offset-sm-1 col-sm-10">
				<button type="submit" name="changePassword" class="btn_add btn">Zatwierdź</button>
			 </div>
		</div>
    </form>
</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>