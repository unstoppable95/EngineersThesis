<html>
	<head>
		<title>ADMIN-panel glowny</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="js/jquery-2.2.4.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="admin_menu/a_style.css" title="Arkusz stylów CSS">
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

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#">Konto administratora</a>
		</div>

		<ul class="nav navbar-nav">
			<li class="active"><a href="#">Strona główna</a></li>
			<li><a href="admin_menu/a_addClass.php">Dodaj klasę</a></li>
		    <li><a href="admin_menu/a_settings.php">Ustawienia</a></li>
		</ul>

		<ul class="nav navbar-nav navbar-right">
			<li>
				<a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Wyloguj się</a>
			</li>
		</ul>
	</div>
</nav>	
	

<h3 class="text-center">Lista klas w szkole</h3>

<form action="admin_helper.php" method="post">
	<div id="class_list" class="container-fluid "></div>
</form>




<!--MODAL DETAILS -->
<div id="userModal" class="modal fade">
	<div class="modal-dialog modal-lg">
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
	<div class="modal-dialog modal-lg">
		<form action="admin_helper.php" method="post" enctype="multipart/form-data">
			<div class="modal-content">
	
				<div class="modal-header">
					<h3 class="modal-title">Zmiana skarbnika</h3>
				</div>

				<div class="modal-body">
				<h4>Podaj nowy email skarbnika </h4>
					Email: <input type="text" name="trNewMail"/>
				</div>

				<div class="modal-footer">
					<input type="submit" name="changeNewTreasurer" value="Zatwierdź"/>
				</div>

			</div>
		</form>
	</div>
</div>

<!--MODAL CHANGE TR -->
<div id="changeTrModal" class="modal fade">
	<div class="modal-dialog modal-lg">
		<form method="post" id="user_form" enctype="multipart/form-data">
		   <div class="modal-content">

			<div class="modal-header">
				<h3 class="modal-title">Zmiana skarbnika</h3>
			</div>

			<div class="modal-body">
				<h4>Podaj dane nowego skarbnika </h4>
				<form action="admin_helper.php" method="post">
					Email: <input type="text" name="trMail"/>
				</form>
			</div>

			<div class="modal-footer">
				<input type="submit" name="changeTreasuer2" class="btn_commitChange" value="Zatwierdź"/>
			</div>

			</div>
		</form>
	</div>
</div>	

<!--MODAL ADD STUDENTs csv-->
<div id="addStudentCSVModal" class="modal fade">
	<div class="modal-dialog modal-lg">
		<form action="admin_helper.php" method="post" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">Wczytywanie pliku CSV</h3>
				</div>

				<div class="modal-body">
					<h4>Załaduj plik CSV z danymi</h4>
					<input type="file" name="fileToUpload" id="fileToUpload">
				</div>

				<div class="modal-footer">
					<input type="submit" name="addStudentsFile" value="Dodaj uczniów"/>
				</div>
			</div>
		</form>
	</div>
</div>

<!--MODAL ADD STUDENT-->
<div id="addStudentModal" class="modal fade">
	<div class="modal-dialog modal-lg">
		<form method="post" id="user_form" enctype="multipart/form-data">
			<div class="modal-content">
				<form action="admin_helper.php" method="post">
					<div class="modal-header">
							<h3 class="modal-title">Dodaj ucznia do klasy</h3>
					</div>

					<div class="modal-body">
						<h3 class="text-center">Dane ucznia</h3>
						<table>
							<tr><td>Imię: </td><td><input type="text" name="childName"/></td></tr>
							<tr><td>Nazwisko: </td><td><input type="text" name="childSurname" /></td></tr>
							<tr><td>Data urodzenia: </td><td><input type="date" name="childBirthdate" /> </td></tr>
						</table>

						<h3 class="text-center"> Dane rodzica </h3>
						<table>
							<tr><td>Imię: </td><td><input type="text" name="parentName" /></td></tr>
							<tr><td>Nazwisko: </td><td><input type="text" name="parentSurname" /></td></tr>
							<tr><td>Mail: </td><td><input type="text" name="parentEmail" /></td></tr>
						</table>
					</div>

					<div class="modal-footer">
						<input type="submit" name="addStudent" class="btn_add" value="Zatwierdz" />
					</div>
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
	  
	  //save classID to load date from CSV
$(document).on('click','.btn_addStudentsCSV',function(){
	var id=$(this).data("id3");
	$.ajax({
		url:"admin_helper.php",
		method:"POST",
		data:{function2call: 'saveClassID', id:id },
		dataType:"text",
		success:function(data){
			
	
			
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