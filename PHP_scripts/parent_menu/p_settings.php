<html> 
<head> 
	<title>Rodzic-ustawienia</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="p_style.css" title="Arkusz stylów CSS">
	
		<script src="../js/jquery-2.2.4.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
	
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
  <a href="p_choiceChild.php">Wybór dziecka</a>
  <a href="p_history.php">Historia wpłat</a>
  <a href="p_classAccount.php">Konto klasowe</a>
  <a href="p_settings.php" class="active">Ustawienia</a>
<a href="../logout.php"> Wyloguj się</a>
</div>

<div class="lewa_strona">
	<h1> Ustawienia </h1>
	<h2> Informacja </h2>
	
	<h4> Dane rodzica </h4>
	
	<form name="parent_data">
	
	<div id="parent_data"></div>
	</form>
	<br>
	<h2> Zmien hasło </h2>
	
	<form action="../parent_helper.php" method="post">
	<table>
		<tr><td>Podaj nowe hasło: </td><td><input type="password" name="newPassword"/></td></tr> 
	
		<tr><td colspan="2"><input type="submit" name="changePassword" class="btn_commit" value="Zatwierdz"/></td></tr>
	<table>
	</form>


</div>








</body>
</html>



<script>
$(document).ready(function(){
function fetch_parent_data()
	{
		$.ajax({
			url:"../parent_helper.php",
			method:"POST",
			data:{function2call:'parent_data'},
			success:function(data){
				$('#parent_data').html(data);
			}
		});
		
	}
	fetch_parent_data();  
	  
	  
 }); 
  

</script>