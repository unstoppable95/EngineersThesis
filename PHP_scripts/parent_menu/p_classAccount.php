<html> 
<head> 
	<title>Rodzic-konto klasowe</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="p_style.css" title="Arkusz stylów CSS">
	
		<script src="../js/jquery-2.2.4.js"></script>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  
</head>
<?php
session_start();

if (!isset($_SESSION['loggedIn']))
{
	header('Location: ../index.php');
	exit();
}

?>
<body>

<div class="menu">
<a href="../menu_parent.php" >Strona główna</a>
  <?php

if ($_SESSION['type'] == "t")
{
	$myVar = '<a href="../menu_treasurer.php">Panel skarbnika</a>';
	echo $myVar;
}

?>
 
  <?php

if ((int)$_SESSION['amountOfChild'] > 1)
{
	$myVar = '<a href="p_choiceChild.php">Wybór dziecka</a>';
	echo $myVar;
}

?>
  <a href="p_history.php">Historia wpłat</a>
  <a href="p_classAccount.php" class="active">Konto klasowe</a>
  <a href="p_settings.php">Ustawienia</a>
  <a href="../logout.php"> Wyloguj się</a>
</div>


<div class="lewa_strona">
	<h1> Konto klasowe </h1>
	<h3> Stan konta klasowego dziecka </h3>
	<div id="class_account_data"></div>

	<h3> Wydatki z konta klasowego </h3>
		<div id="class_expenses_list"></div>
	<h3> Opłacone miesiące pieniędzy klasowych </h3>
		<div id="paid_months"></div>
	


</div>
</body>
</html>



<script>

$(document).ready(function(){
	
	function fetch_class_account_data()
	{
		$.ajax({
			url:"../parent_helper.php",
			method:"POST",
			data:{function2call:'fetch_class_account_data'},
			success:function(data){
				$('#class_account_data').html(data);
			}
		});
		
	}
	fetch_class_account_data();
	
	
	function fetch_class_expenses_list()
	{
		$.ajax({
			url:"../parent_helper.php",
			method:"POST",
			data:{function2call:'fetch_class_expenses_list'},
			success:function(data){
				$('#class_expenses_list').html(data);
			}
		});
		
	}
	fetch_class_expenses_list();
	
	
	
	function fetch_paid_months()
	{
		$.ajax({
			url:"../parent_helper.php",
			method:"POST",
			data:{function2call:'fetch_paid_months'},
			success:function(data){
				$('#paid_months').html(data);
			}
		});
		
	}
	fetch_paid_months();
	
	


 }); 
  


</script>