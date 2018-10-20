<html>
<head>
<title>System skarbnik klasowy-panel logowania</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">

<script src="js/jquery-2.2.4.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="index_style.css" title="Arkusz stylów CSS">
<!--<style>

.modal {
  display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
.modal-content {
    background-color: #red;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}
</style>-->



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

<body class="text-center">
	<form action="login.php" method="post" class="form-signin">
	
		<div class="form-group">
		 <label for="login">Login</label>
		<input type="text" name="login" class="form-control" id="login" placeholder="Enter email"/>
		</div>
		<div class="form-group">
		<label for="password">Hasło</label>
		<input type="password" name="password" class="form-control" id="password" placeholder="Password"/>
		</div>
		<button type="submit" class="btn btn-primary btn-block">Zaloguj się</button>

		<?php
			if (isset($_SESSION['error']))
			{
				echo $_SESSION['error'];
			}
		?>
	<p class="help-block">
	<a class="pull-right text-muted" data-toggle="modal" data-target="#mailReminder"><small>Zapomniałeś hasła?</small></a>
	</p>
	</form>
	
	
<!--MODAL REMIND PASSWORD -->
<div id="mailReminder" class="modal fade" >
 <div class="modal-dialog">
   <form action="admin_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
		<p>
		<h2>PRZYPOMNIENIE HASŁA</h2>
		Wpisz swój login. Na tego maila zostanie wysłane Twoje hasło: <br /> <input type="myMail" name="myMail" /> <br /><br />
		<input type="submit" value="Przypomnij hasło" name="sendPassword"/>
		</p>
		
   </div>
  </form>
 </div>
</div>
