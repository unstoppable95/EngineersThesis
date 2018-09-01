<html>
<head>
	<title>MENU </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="admin_menu/a_style.css" title="Arkusz stylów CSS">

	<script src="js/jquery-2.2.4.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
	
	
</head>
<?php
	session_start();
	
	if (!isset($_SESSION['loggedIn']))
	{
		header('Location: index.php');
		exit();
	}
	require_once "admin_helper.php";
	
?>
	
<body>

	<div class="menu">
		<a href="#" class="active">Strona główna</a>
		<a href="admin_menu/a_addClass.php">Dodaj klasę</a>
		<a href="admin_menu/a_settings.php">Ustawienia</a>
		<a href="logout.php">Wyloguj się</a>
	</div>	
		
	
<div class="lewa_strona">

	<h1>Konto administratora</h1>
	<h3>Lista klas w szkole</h3>
	<form action="admin_helper.php" method="post">
		
			<div id="class_list"></div>
		
		<textarea cols="50" rows="3" wrap="on">
			<?php 
			if (isset($_SESSION['funDisplay_1]']))
			{
				echo $_SESSION['funDisplay'];
			}
			?></textarea>
		<input type="submit" name="showClasses" value=" Pokaz"/>
	</form>

</div>
	
</body>
</html>


<script>
$(document).ready(function(){
	function fetch_data()
	{
		$.ajax({
			url:"admin_helper.php",
			method:"POST",
			data:{function2call:'fetch'},
			success:function(data){
				$('#class_list').html(data);
			}
		});
		
	}
	fetch_data();
	
//delete
$(document).on('click','.btn_delete',function(){
	var id=$(this).data("id3");
	if(confirm("Czy jestes pewny, ze chcesz usunąć tą klasę?"))  
           {
	$.ajax({
		url:"admin_helper.php",
		method:"POST",
		data:{function2call: 'delete', id:id},
		dataType:"text",
		success:function(data){
			alert(data);
			fetch_data();
			
                     }  
                });  
           }  
      });  
 }); 
  

</script>