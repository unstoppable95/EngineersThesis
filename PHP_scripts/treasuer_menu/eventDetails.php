<html> 
<head> 
    <title>Skarbnik-wydatki</title>
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
			<!--<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="../menu_treasurer.php">Strona główna</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="expenses.php">Wydatki klasowe<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="class_event_list.php">Zbiórki klasowe / Wydarzenia</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="payments.php">Wpłaty</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="students.php">Uczniowie</a>
				</li>
				<li class="nav-item">
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
			</ul>-->
	  </div>
</nav>    

<div id="event_details" class="container"></div>	
        


<!--PAY FOR EVENT -->
<div id="payForEventModal" class="modal fade" abindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered container">
    <form action="../treasurer_helper.php" method="post" name="payForEvent" id="payForEvent" enctype="multipart/form-data">
   <div class="modal-content">
		<div class="modal-header">
				<h3 class="text-center">Opłać wydarzenie</h3>
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
									<label for="amount">Wprowadż kwotę, jaką chcesz opłacić z konta:</label>
									<input type="number" step="0.01" min="0"  name="amount" class="form-control"/>
									<label for="amountCash">Wprowadż kwotę, jaką chcesz opłacić gotówką:</label>
									<input type="number" step="0.01" min="0"  name="amountCash" class="form-control"/>
								</div>
								<div class="radio">
									<label><input type="radio" name="payAll" value="payAllval">Opłać całość z konta</label>
								</div>
								<div class="radio">
									<label><input type="radio" name="payAll" value="payAllCash">Opłać całość gotówką</label>
								</div>
							</div>
							<button type="submit" class="btn btn-lg btn-primary btn-block btn_edit"  name="payForChildEvent">Opłać</button>
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
	
	function fetch_event_details()
    {
	var id=$(this).data("id4");
       $.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call: 'fetch_event_details', id:id},
			dataType:"text",
				success:function(data){
					$('#event_details').html(data);
			
			
                     }      					 
                });   
        
    }
    fetch_event_details();

	
	
	
 /*	$(document).on('click','.btn_detailsEvent',function(){
	var id=$(this).data("id4");

		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call: 'fetch_event_details', id:id},
			dataType:"text",
				success:function(data){
					$('#event_details').html(data);
			
			
                     }      					 
                });   
      });
	  
	 
	 	$(document).on('click','.btn_detailsEvent',function(){
	var id=$(this).data("id4");

		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call: 'set_selected_rowID', id:id},
			//dataType:"text",
			success:function(){
				window.open('eventDetails.php','_blank');
					//window.open('http://www.w3schools.com');
                     }      					 
                });   
      });*/
	$(document).on('click','.btn_payForEvent',function(){
	var childID=$(this).data("id3");
	var eventID=$(this).data("id4");

	
		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call: 'payForEventTmp', childID:childID, eventID:eventID},
			dataType:"text",
				success:function(data){
                     }      					 
                });   
      });
 
 
        
 }); 
  </script>