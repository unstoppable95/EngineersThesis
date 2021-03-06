<?php
session_start();

if (!isset($_SESSION['loggedIn']))
{
	header('Location: ../index.php');
	exit();
}

$_SESSION['treasurerAsParent'] = false;
require_once "../connection.php";

// stworzenie polaczenia z baza danych -> @ wyciszanie bledow zeby dac swoje

$conn = new MyDB();
$result = $conn->query(sprintf("select * from username where login='%s' and first_login=TRUE", mysqli_real_escape_string($conn, $_SESSION['user'])));
$isUser = $result->num_rows;

if ($isUser <= 0)
{
	$_SESSION['firstLog'] = null;
}
else
{
	$_SESSION['firstLog'] = true;
}

$amountOfChild = $conn->query(sprintf("SELECT * FROM child WHERE parent_id = (SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "')"));
$_SESSION['amountOfChild'] = $amountOfChild->num_rows;

if ($_SESSION['amountOfChild'] == 1)
{
	$tmp = $conn->query(sprintf("SELECT * FROM child WHERE parent_id = (SELECT id FROM parent WHERE email = '" . $_SESSION['user'] . "')"));
	$row = mysqli_fetch_array($tmp);
	$_SESSION['choosenChild'] = $row["id"];
}

?>
<html> 
<head> 
	<title>Skarbnik-panel główny</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!-- Our CSS -->
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
	
	<!-- Example of icons -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

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
				<li class="nav-item active">
					<a class="nav-link" href="class_event_list.php">Zbiórki klasowe / Wydarzenia<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="payments.php">Wpłaty</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="students.php">Uczniowie</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="transfer.php">Wypłaty</a>
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
			</ul>
	  </div>
</nav>    



<div class="container-fluid">			
	<div class="col-md-8 offset-sm-2 text-center text-danger" >
				<?php
					if (isset($_SESSION['errorEditEvent']))
					{
						echo $_SESSION['errorEditEvent'];
						unset($_SESSION['errorEditEvent']);
					}
					if (isset($_SESSION['errorDeleteEvent']))
					{
						echo $_SESSION['errorDeleteEvent'];
						unset($_SESSION['errorDeleteEvent']);
					}
				?>
				</div>
	<div class="row">
		<div class="ml-3" id ="children_account_information"></div>
	</div>
	<div class="row text-center">
		<h5 class="col-md-12" >Lista zbiórek:</h5>
	</div>
</div>

<div id="class_event" class="container-fluid "></div>


<div id="eventDetailsModal" class="modal fade" >
 <div class="modal-dialog">
    <form action="../treasurer_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    
		<h2>Szczegóły </h2>
		<div id="event_details"></div>			
   </div>
  </form>
 </div>
</div>



<!--PAY FOR EVENT -->
<div id="payForEventModal" class="modal fade" >
 <div class="modal-dialog">
    <form action=".//treasurer_helper.php" method="post" name="payForEvent" id="payForEvent" enctype="multipart/form-data">
   <div class="modal-content">
    
		<h2>Opłać wydarzenie</h2>
			Wprowadż kwotę, jaką chcesz opłacić: <input type="number" step="0.01" min="0"  name="amount"/>
			<br>
			<input type="checkbox" name="payAll" value="payAllval" > Oplac całość<br>
			<br>
			<input type="submit" name="payForChildEvent"  class="btn_pay" value="Opłać"/>
		
   </div>
  </form>
 </div>
</div>

<!--EVENT EDIT--> 
<div id="eventEditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered container">
    <form action="../treasurer_helper.php" method="post" name="editEvent1" id="editEvent1" enctype="multipart/form-data">
   <div class="modal-content">
		<div class="modal-header">
				<h3 class="text-center">Edycja wydarzenia</h3>
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
								<label for="newEventName">Nazwa:</label>
								<input type="text" name="newEventName" class="form-control"/>
								</div>
								<div class="row">
								<label for="newEventPrice">Cena:</label>
								<input type="number" step="0.01" min="0"  name="newEventPrice" class="form-control"/>
								</div>
								<div class="row">
								<label for="newEventName">Data:</label>
								<input type="date" placeholder="YYYY-MM-DD" name="newEventDate"class="form-control" />
							</div>
							</div>
							<button type="submit" class="btn btn-lg btn-primary btn-block btn_edit"  name="editEvent">Zatwierdź</button>
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


<div id="eventEndModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered container">
    <form action="../treasurer_helper.php" method="post" name="editEvent1" id="editEvent1" enctype="multipart/form-data">
   <div class="modal-content">
		<div class="modal-header">
				<h6 class="text-center">Czy na pewno chcesz zakończyć zbiórkę?</h6>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<span aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="text-center">
						<fieldset>
							<button type="submit" class="btn btn-lg btn-primary btn-block btn_edit" name="endEvent">Zakończ</button>
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

<div id="eventDeleteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered container">
    <form action="../treasurer_helper.php" method="post" name="editEvent1" id="editEvent1" enctype="multipart/form-data">
   <div class="modal-content">
		<div class="modal-header">
				<h6 class="text-center">Czy na pewno chcesz usunąć zbiórkę?</h6>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<span aria-hidden="true">&times;</span></button>
		</div>
		<div class="modal-body">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="text-center">
						<fieldset>
							<button type="submit" class="btn btn-lg btn-primary btn-block btn_delete" name="deleteEvent">Usuń</button>
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

	 
    function fetch_children_account_information()
    {
        $.ajax({
            url:"../treasurer_helper.php",
            method:"POST",
            data:{function2call:'fetch_children_account_information'},
            success:function(data){
                $('#children_account_information').html(data);
            }
        });
        
    }
    fetch_children_account_information();


	$(document).on('click','.btn_editEvent',function(){
		var id=$(this).data("id4");
		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call: 'saveEditEvent', id:id},
			dataType:"text",
		
				success:function(data){
					
                     }      					 
                });
	 
           
      });

	//zakończ
	$(document).on('click','.btn_endEvent',function(){
		var id=$(this).data("id4");
		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call: 'saveEditEvent', id:id},
			dataType:"text",
		
				success:function(data){
					
                     }      					 
                });
	 
           
      });
	  
	// delete event

	$(document).on('click','.btn_deleteEvent',function(){
		var id=$(this).data("id4");
		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call: 'saveEditEvent', id:id},
			dataType:"text",
		
				success:function(data){
					
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
			}
        });
    });
	
	
	function fetch_event_list()
	{
		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call:'fetch_event_list'},
			success:function(data){
				$('#class_event').html(data);
			}
		});
		
	}
	fetch_event_list();
	
	function fetch_class_name()
	{
		$.ajax({
			url:"../treasurer_helper.php",
			method:"POST",
			data:{function2call:'fetch_class_name'},
			success:function(data){
				$('#class_name').html(data);
			}
		});
		
	}
	fetch_class_name();

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