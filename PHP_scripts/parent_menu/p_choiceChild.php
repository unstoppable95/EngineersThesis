<html> 
<head> 
	<title>Rodzic-wybór dziecka</title>
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
  <a href="p_choiceChild.php"  class="active">Wybór dziecka</a>
  <a href="p_history.php" >Historia wpłat</a>
  <a href="p_classAccount.php">Konto klasowe</a>
  <a href="p_settings.php">Ustawienia</a>
   <a href="../logout.php"> Wyloguj się</a>
</div>


<div class="lewa_strona">
	<h1> Wybór dziecka </h1>
		<div id="child_list"></div>


</div>

</body>


<script>
$(document).ready(function(){
	function fetch_data()
	{
		$.ajax({
			url:"../parent_helper.php",
			method:"POST",
			data:{function2call:'fetch_child_list'},
			success:function(data){
				$('#child_list').html(data);
			}
		});
		
	}
	fetch_data();

	$(document).on('click','.btn_choose',function(){
	var id=$(this).data("id3");
	if(confirm("Czy jestes pewny, ze chcesz zmienic dziecko?"))  
           {
	$.ajax({
		url:"../parent_helper.php",
		method:"POST",
		data:{function2call: 'choose', id:id},
		dataType:"text",
		success:function(data){
			alert(data);			
                     }  
                });  
           }  
      });

	
 }); 
  

</script>
