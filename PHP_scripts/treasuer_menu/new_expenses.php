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
	<title>Skarbnik-dodaj zbiórkę</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!-- Our CSS -->
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
	
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
			<a class="navbar-brand">Konto skarbnika</a>
	  	</div>
</nav>    
<div class="container">
	<div class="row text-center">
		<h5 class="col-md-12">Dodaj wydatek klasowy</h5>
	</div>
</div>
<div class="container">
	<form action="../treasurer_helper.php" method="post" class="form-vertical justify-content-center">
		<div class="form-group row">
			<div class="col-md-10 offset-sm-1">
				<label for="expenseName" class="text-center col-form-label">Nazwa:</label>
				<input type="text" name="expenseName" class="form-control" required />
			</div>
			<div class="col-md-10 offset-sm-1">
				<label for="eventPriceCash" class="text-center col-form-label">Cena:</label>
				<input type="number" step="0.01" min="0" name="eventPriceCash" class="form-control" value="0"/>
			</div>
			<div class="col-md-10 offset-sm-1">
			<div class="form-check">
				<label class="form-check-label">
				<input type="radio" class="form-check-input" name="payment_type" value="cash" checked>Płatność gotówką
				</label>
			</div>
			</div>
			<div class="col-md-10 offset-sm-1">
			<div class="form-check">
				<label class="form-check-label">
				<input type="radio" class="form-check-input" name="payment_type" value="bank">Płatność z konta
				</label>
			</div>
			</div>
			<div class="col-md-8 offset-sm-2 text-center text-danger" >
				<?php
					if (isset($_SESSION['error_new_expense']))
					{
						echo $_SESSION['error_new_expense'];
						unset($_SESSION['error_new_expense']);

					}
				?>
				</div>
		</div>
		<div class="row text-center">
			<div class="offset-sm-1 col-sm-10">
				<button type="submit" class="btn btn_addExpense"  name="addExpense">Zatwierdź</button>
			 </div>
		</div>
	</form>
</div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>
</html>