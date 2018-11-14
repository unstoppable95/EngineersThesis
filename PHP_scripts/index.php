<html>
<head>
	<title>System skarbnik klasowy-panel logowania</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!-- Our CSS-->
	<link rel="stylesheet" type="text/css" href="index_style.css" title="Arkusz stylów CSS"> 

	<!-- Example of icons -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

	<meta name="viewport" content="width=device-width, initial-scale=1">

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
	<div class="container">
	<form action="login.php" method="post" class="form-signin">
	
		<div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
				<h2>Zaloguj się</h2>
                 <hr>
            </div>
		</div>
		
		<div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
				<div class="form-group">
					<label for="login">Login</label>
					<input type="text" name="login" class="form-control" id="login" placeholder="Email"/>
				</div>
			</div>
		</div>
		
		<div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
				<div class="form-group">
				<label for="password">Hasło</label>
				<input type="password" name="password" class="form-control" id="password" placeholder="Hasło"/>
				<div class="col-md-8 offset-sm-2 text-center text-danger" >
				<?php
					if (isset($_SESSION['error']))
					{
						echo $_SESSION['error'];
					}
				?>
				</div>
				</div>
			</div>

			
		</div>
		<div class="row" style="padding-top: 1rem">
            <div class="col-md-3"></div>
            <div class="col-md-6">
			<button type="submit" class="btn btn-primary" class="fa fa-sign-in">Zaloguj się</button>
			<a class="btn btn-link" data-toggle="modal" data-target="#mailReminder"><small>Zapomniałeś hasła?</small></a>
			</div>
		</div>
	</div>
	<div>
		
		</div>
	<!--<p class="help-block">
		<a class="btn btn-link" data-toggle="modal" data-target="#mailReminder"><small>Zapomniałeś hasła?</small></a>
	</p>-->
	</form>
	
	
	<!--MODAL REMIND PASSWORD -->
	<div id="mailReminder" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
		   <form action="admin_helper.php" method="post" id="user_form" enctype="multipart/form-data">

			   <div class="modal-content">
					<div class="modal-header">
						<h1 class="text-center">Przypomnienie hasła</h1>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="text-center">
										<p>Wpisz swój login. Na tego maila zostanie wysłane Twoje hasło:</p>
					
									<div class="panel-body">
									<fieldset>
										<div class="form-group">
											<input class="form-control input-lg" type="email" name="myMail" /> 
										</div>

										<button type="submit" class="btn btn-lg btn-primary btn-block"  name="sendPassword">Przypomnij hasło</button>
									</fieldset>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>	
				</div>
			</form>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
