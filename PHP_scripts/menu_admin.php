<html>
<head>
	<title>ADMIN-panel glowny</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="admin_menu/a_style.css" title="Arkusz stylów CSS">

	<script src="js/jquery-2.2.4.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  

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
			
	</form>
	<div id="xxx"></div>
</div>



<!--MODAL DETAILS -->
<div id="userModal" class="modal fade">
 <div class="modal-dialog">
  <form method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    
    <div id="parent_data"></div>
   
    <div class="modal-footer">
     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
   </div>
  </form>
 </div>
</div>

<!--MODAL CHANGE TR email -->
<div id="changeTrEmail" class="modal fade">
 <div class="modal-dialog">
  <form action="admin_helper.php" method="post" enctype="multipart/form-data"">
   <div class="modal-content">
	
	
    <h2>Zmiana skarbnika</h2>
	<h3>Podaj nowy email skarbnika </h3>
	Email : <input type="text" name="trNewMail"/>
	
	<input type="submit" name="changeNewTreasurer" value="Zatwierdź"/>
   </div>
  </form>
 </div>
</div>

<!--MODAL CHANGE TR -->
<div id="changeTrModal" class="modal fade">
 <div class="modal-dialog">
  <form method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
	

    <h2>Zmiana skarbnika</h2>
	<h3>Podaj dane nowego skarbnika</h3>
	<form action="admin_helper.php" method="post">
	Email: <input type="text" name="trMail"/><br>
	<input type="submit" name="changeTreasuer2" class="btn_commitChange" value="Zatwierdź"/>
	
	</form>

   
   </div>
  </form>
 </div>
</div>	


<!--MODAL ADD STUDENT-->
<div id="addStudentModal" class="modal fade">
 <div class="modal-dialog">
  <form method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
	
	<form action="admin_helper.php" method="post">
		<h1> Dodaj ucznia do klasy</h1> 
		<h3> Dane ucznia </h3>
		<table>
			<tr><td>Imię: </td><td><input type="text" name="childName"/></td></tr> 
			<tr><td>Nazwisko: </td><td><input type="text" name="childSurname" /></td></tr> 
			<tr><td>Data urodzenia: </td><td><input type="date" name="childBirthdate" /> </td></tr> 
			<tr><td> </td><td> </td></tr> 
			<tr><td><h3> Dane rodzica </h3></td></tr> 
			<tr><td>Imię: </td><td><input type="text" name="parentName" /></td></tr>  
			<tr><td>Nazwisko: </td><td><input type="text" name="parentSurname" /></td></tr> 
			<tr><td>Mail: </td><td><input type="text" name="parentEmail" /></td></tr> 
			<tr><td colspan="2"><input type="submit" name="addStudent" class="btn_add" value="Zatwierdz" /></td></tr>
	<table>
	</form>

   
   </div>
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
			url:"admin_helper.php",
			method:"POST",
			data:{function2call:'fetch'},
			success:function(data){
				$('#class_list').html(data);
			}
		});
		
	}
	fetch_data();

	
// delete

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
	  
	  //load from csv
$(document).on('click','.btn_addStudentsFile',function(){
	var id=$(this).data("id3");
	var filename=$('#chooseFile')[0].files[0]['name'];
	
	
	//$_FILES["UploadFileName"]; 
	//var filename = $('#chooseFile').val();
	$.ajax({
		url:"admin_helper.php",
		method:"POST",
		data:{function2call: 'addStudentsFile', id:id,filename:filename },
		dataType:"text",
		success:function(data){
			
		$('#xxx').html(data);
			
                     }  
                });  
             
      }); 
	  
	$(document).on('click','.btn_details',function(){
	var id=$(this).data("id3");
	
		$.ajax({
			url:"admin_helper.php",
			method:"POST",
			data:{function2call: 'details', id:id},
			dataType:"text",
				success:function(data){
				$('#parent_data').html(data);
			
                     }  
                });
	 
           
      }); 
	  
	  
	$(document).on('click','.btn_trChange',function(){
	var id=$(this).data("id3");
		$.ajax({
			url:"admin_helper.php",
			method:"POST",
			data:{function2call: 'changeTreasuer', id:id},
			dataType:"text",
				success:function(data){			
                     }      					 
                });         
      });
	  
	  
	  
	$(document).on('click','.btn_addStudent',function(){
	var id=$(this).data("id3");
		$.ajax({
			url:"admin_helper.php",
			method:"POST",
			data:{function2call: 'addStudent2', id:id},
			dataType:"text",
				success:function(data){			
                     }      					 
                });         
      });
	  
	  
	  
 }); 
  
</script>