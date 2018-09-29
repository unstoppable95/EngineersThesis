<html> 
<head> 
	<title>Rodzic-historia</title>
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
		if ($_SESSION['type'] =="t"){
					$myVar='<a href="../menu_treasurer.php">Panel skarbnika</a>';
					echo $myVar;
				}
  ?>

  <?php
		if ((int)$_SESSION['amountOfChild'] > 1){
					$myVar='<a href="p_choiceChild.php">Wybór dziecka</a>';
					echo $myVar;
				}
  ?>
  <a href="p_history.php"  class="active">Historia wpłat</a>
  <a href="p_classAccount.php">Konto klasowe</a>
  <a href="p_settings.php">Ustawienia</a>
 <a href="../logout.php"> Wyloguj się</a>
</div>


<div class="lewa_strona">
	<h1> Historia wpłat </h1>
	<h3> Wpłaty na konto dziecka </h3>
		<div id="payment_history"></div>
		
			<h3> Wpłaty na konto klasowe </h3>
		<div id="class_account_payment_history"></div>


</div>


</body>
</html>



<script>

$(document).ready(function(){
	
		function fetch_payment_history()
	{
		$.ajax({
			url:"../parent_helper.php",
			method:"POST",
			data:{function2call:'fetch_payment_history'},
			success:function(data){
				$('#payment_history').html(data);
			}
		});
		
	}
	fetch_payment_history();
	
		
	function fetch_class_account_payment_history()
	{
		$.ajax({
			url:"../parent_helper.php",
			method:"POST",
			data:{function2call:'fetch_class_account_payment_history'},
			success:function(data){
				$('#class_account_payment_history').html(data);
			}
		});
		
	}
	fetch_class_account_payment_history();
	


 }); 
  


</script>