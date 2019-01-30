<?php
session_start();

if (!isset($_SESSION['loggedIn']))
{
	header('Location: ../index.php');
	exit();
}

?>
<html> 
<head> 
	<title>Skarbnik-dodawania ucznia i rodzica</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<script src="check.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!-- Our CSS -->
	<link rel="stylesheet" type="text/css" href="../treasuer_menu/style.css" title="Arkusz stylów CSS">
	
	<!-- Example of icons -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<div class="container">
	<div class="row text-center">
		<h5 class="col-md-12" >Dane nowego ucznia:</h5>
	</div>
</div>


<div class="container">
	<form action="../admin_helper.php" method="post" class="form-vertical justify-content-center">
		<div class="form-group row">
			<div class="col-md-10 offset-sm-1">
				<label for="childName" class="text-center col-form-label">Imię:</label>
				<input type="text" name="childName" class="form-control" required/>
			</div>
			<div class="col-md-10 offset-sm-1">
				<label for="childSurname" class="text-center col-form-label">Nazwisko:</label>
				<input type="text" name="childSurname" class="form-control" required/>
			</div>
			<div class="col-md-10 offset-sm-1">
				<label for="childBirthdate" class="text-center col-form-label">Data urodzenia: </label>
				<input type="date" name="childBirthdate" class="form-control"/>
			</div>
		</div>
		<div class="row text-center">
			<h5 class="col-sm-10 offset-sm-1">Dane rodzica:</h5>
		</div>
		
		<div class="form-group row">
			<div class="col-md-10 offset-sm-1">
				<label for="parentName" class="text-center col-form-label">Imię:</label>
				<input type="text" name="parentName" class="form-control"/>
			</div>
			<div class="col-md-10 offset-sm-1">
				<label for="parentSurname" class="text-center col-form-label">Nazwisko:</label>
				<input type="text" name="parentSurname" class="form-control"/>
			</div>
			<div class="col-md-10 offset-sm-1">
				<label for="parentEmail" class="text-center col-form-label">Email:</label>
				<input type="text" name="parentEmail" class="form-control" required/>
			</div>
		</div>
		<div class="row text-center">
			<div class="offset-sm-1 col-sm-10">
				<button type="submit" name="addStudent" class="btn_add btn" onclick="return validate(this.form);">Zatwierdź</button>
			 </div>
		</div>

	</form>
</div>



	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>