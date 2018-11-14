<html>
	<head>
		<title>ADMIN-panel glowny</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

		<!-- Our CSS -->
		<link rel="stylesheet" type="text/css" href="admin_menu/a_style.css" title="Arkusz stylów CSS">

		<!-- Example of icons -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

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

<!-- #TO-DO set data-target and 2x aria argument value -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <a class="navbar-brand">Konto administratora</a>
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="#">Strona główna <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_menu/a_addClass.php">Dodaj klasę</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin_menu/a_settings.php">Ustawienia</a>
      </li>
    </ul>

	<ul class="navbar-nav ml-auto">
		<li class="nav-item">
			<a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i>Wyloguj się</a>
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
			<div class="modal-header">
				<h3 class="modal-title">Szczegóły</h3>
			</div>
			<div class="modal-body">
			<div id="parent_data"></div>
			</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
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

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
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