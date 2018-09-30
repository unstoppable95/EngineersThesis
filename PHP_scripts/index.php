<html>
<head>
<title>System skarbnik klasowy-panel logowania</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="index_style.css" title="Arkusz stylów CSS">


	<script src="js/jquery-2.2.4.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<style>
/* Modal (background) */
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
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}
</style>



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
<div>
<div class="container">
	<div class="vertical-al">
	<form action="login.php" method="post">
	
		<h2> Zaloguj się </h2>
		<p>
		Login: <br /> <input type="text" name="login" /> <br /> 
		Hasło: <br /> <input type="password" name="password" /> <br /><br />
		<input type="submit" value="Zaloguj się" /> <br><br>
		
		</p>
		
		
		<?php
			if (isset($_SESSION['error']))
			{
				echo $_SESSION['error'];
			}
		?>
	
	</form>
	<button type="button" data-toggle="modal" data-target="#mailReminder" class="btn_remindPasswd">Przypomnij hasło</button>
	</div>
	</div>
</div>	
	
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

</body>
</html>