<html> 
<head> 
	<title>Rodzic-panel głowny</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="parent_menu/p_style.css" title="Arkusz stylów CSS">

	
	<script src="js/jquery-2.2.4.js"></script>
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
	<a href="#" class="active">Strona główna</a>
	  <?php
		if ($_SESSION['type'] =="t"){
					$myVar='<a href="menu_treasurer.php">Panel skarbnika</a>';
					$_SESSION['treasurerAsParent']=true;
					echo $myVar;
				}
  ?>
  <a href="parent_menu/p_choiceChild.php">Wybór dziecka</a>
	<a href="parent_menu/p_history.php">Historia wpłat</a>
  <a href="parent_menu/p_classAccount.php">Konto klasowe</a>
  <a href="parent_menu/p_settings.php">Ustawienia</a>
  <a href="logout.php"> Wyloguj się</a>
</div>



<div class="lewa_strona">
	<div class="naglowek" >
		<h1> Konto rodzica </h1>
		<h3> Bierzące płatności ...imie wybranego dziecka ... </h3>
		
		<div id="live_data"></div>
		
		<h3> Stan konta dziecka </h3>
		
		<form>
		<table>
			<tr><td>Konto składek klasowych:  </td><td>...kwota...</td></tr> 
			<tr><td>Konto wydarzeń:  </td><td>...kwota...</td></tr> 
		</table>
		</form>
		
	</div>

	
</div>


</body>
</html>


<script>
$(document).ready(function(){
	function fetch_data()
	{
		$.ajax({
			url:"parent_helper.php",
			method:"POST",
			data:{function2call:'fetch'},
			success:function(data){
				$('#live_data').html(data);
			}
		});
		
	}
	fetch_data();
	
	//delete
$(document).on('click','.btn_delete',function(){
	var id=$(this).data("id3");
	if(confirm("Czy jestes pewny, ze chcesz wypisac dziecko z tego wydarzenia?"))  
           {
	$.ajax({
		url:"parent_helper.php",
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