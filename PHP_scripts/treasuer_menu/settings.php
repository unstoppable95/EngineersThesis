<html> 
<head> 
	<title>Skarbnik-ustawienia</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
	
	
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
	<a href="../menu_treasurer.php" >Strona główna</a>
	<a href="../menu_parent.php">Moje dzieci</a>
  <a href="addStudent.php">Dodaj ucznia do klasy</a>
  <a href="addCyclicEvent.php">Dodaj event cykliczny</a>
  <a href="addOnceEvent.php">Dodaj event jednorazowy</a>
  <a href="settings.php" class="active">Ustawienia</a>
	<a href="../logout.php"> Wyloguj się</a>
</div>

<div class="lewa_strona">
	<h1> Ustawienia </h1>
	<h2> Informacja o klasie </h2>
	<h4> Lista uczniów </h4>
	
	<div id="students_list"></div>
	
	<h4> Dane skarbnika </h4>

	<div id="treasuer_data"></div>

	<br>
	<h2> Zmien hasło </h2>
	
	<form action="../treasurer_helper.php" method="post">
	<table>
		<tr><td>Nowe hasło: </td><td><input type="password" name="newPassword" /></td></tr> 
		<tr><td colspan="2"><input type="submit"  name="changePassword" value="Zatwierdz"/></td></tr>
		
		
	<table>
	</form>


</div>

</body>
</html>


<script>
$(document).ready(function(){
function fetch_students_list()
	{
		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call:'students_list'},
			success:function(data){
				$('#students_list').html(data);
			}
		});
		
	}
	fetch_students_list();  
	
	
	function fetch_treasuer_data()
	{
		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call:'treasuer_data'},
			success:function(data){
				$('#treasuer_data').html(data);
			}
		});
		
	}
	fetch_treasuer_data();  
	  
	  
 }); 
  

</script>