<html> 
<head> 
    <title>Skarbnik-ustawienia</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!-- Our CSS -->
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
	
	<!-- Example of icons -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
</head>
<?php
session_start();

if (!isset($_SESSION['loggedIn'])) {
    header('Location: ../index.php');
    exit();
}

?>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
		<a class="navbar-brand">Konto skarbnika</a>
			<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="../menu_treasurer.php">Strona główna</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="expenses.php">Wydatki klasowe</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="class_event_list.php">Zbiórki klasowe / Wydarzenia</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="payments.php">Wpłaty</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="students.php">Uczniowie<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item ">
					<a class="nav-link" href="settings.php">Ustawienia</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../menu_parent.php">Konto rodzica</a>
				</li>
			</ul>

			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i>Wyloguj się</a>
			</li>
			</ul>
	  </div>
</nav>    

<div class="container">
	<div class="col-md-8 offset-sm-2 text-center text-danger" >
						<?php
					if (isset($_SESSION['info_delete_student']))
					{
						echo $_SESSION['info_delete_student'];
						unset($_SESSION['info_delete_student']);
					}
				?>
				</div>
	<div class="row">
		<div class="col-md-12 mt-3 text-center">
			  <h5> Lista uczniów:</h5>
		</div>
	</div>
 </div>
 
<div id="students_list" class="container-fluid"></div>


<!--MODAL CHANGE PARENR MAIL -->
<div id="changeParMailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered container">
  <form action="../treasurer_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
   <div class="modal-header">
				<h3 class="text-center">Zmiana maila rodzica:</h3>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<span aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="text-center">
						<fieldset>
							<div class="form-group">
								<div class="row">
								<label for="newParentMail">Nowy email:</label>
								<input type="email" name="newParentMail" class="form-control"/>
								</div>
							</div>
							<button type="submit" class="btn btn-lg btn-primary btn-block btn_commitChange"  name="changeParentMail">Zatwierdź</button>
						</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
  </form>
 </div>
</div>

<!--confirm delete student-->
<div id="eventDeleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered container">
    <form action="../treasurer_helper.php" method="post" name="editEvent1" id="editEvent1" enctype="multipart/form-data">
   <div class="modal-content">
		<div class="modal-header">
				<h6 class="text-center">Czy napewno chcesz usunąć ucznia?</h6>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<span aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="text-center">
						<fieldset>
							<!--<h6>Czy napewno chcesz zakończyć zbiórkę?</h6>-->
							<button type="submit" class="btn btn-lg btn-primary btn-block btn_delete" name="deleteStudentEvent">Usuń</button>
						</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
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
    
    

//delete
   /* $(document).on('click','.btn_deleteStudent',function(){
    var id=$(this).data("id3");
    if(confirm("Czy jestes pewny, ze chcesz usunąć ucznia z tej klasy?"))  
           {
    $.ajax({
        url:"../treasurer_helper.php",
        method:"POST",
        data:{function2call: 'deleteStudent', id:id},
        dataType:"text",
        success:function(data){
            alert(data);
            fetch_students_list();
            
                     }  
                });  
           }  
      });
	  */
	  $(document).on('click','.btn_deleteStudent',function(){
	var id=$(this).data("id3");
	$.ajax({
		url:"../treasurer_helper.php",
		method:"POST",
		data:{function2call: 'saveStudentID', id:id },
		dataType:"text",
		success:function(data){
			//alert(data);
			//alert(id);
	
			
                     }  
                });  
             
      }); 
      
      //save which parent mail i want to change
      $(document).on('click','.btn_pMailChange',function(){
    var id=$(this).data("id3");
        $.ajax({
            url:"../treasurer_helper.php",
            method:"POST",
            data:{function2call: 'btn_pMailChange', id:id},
            dataType:"text",
                success:function(data){
                     }                          
                });
    
          
      });
	  
 }); 
  

</script>