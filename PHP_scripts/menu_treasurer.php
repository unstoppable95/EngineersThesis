<html> 
<head> 
	<title>Skarbnik-panel główny</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="treasuer_menu/style.css" title="Arkusz stylów CSS">
	<script src="js/jquery-2.2.4.js"></script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 

	
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
		header('Location: ../index.php');
		exit();
	}
$_SESSION['treasurerAsParent']=false;

	require_once "connection.php";
	
	//stworzenie polaczenia z baza danych -> @ wyciszanie bledow zeby dac swoje
	$conn = @new mysqli($servername, $username, $password, $dbName);
	
	$result=$conn->query(sprintf("select * from username where login='%s' and first_login=TRUE", mysqli_real_escape_string($conn, $_SESSION['user'])));
	$isUser = $result->num_rows;
	if ($isUser <= 0){
	
		$_SESSION['firstLog']=null;
	}
	else{
		$_SESSION['firstLog']=true;
		
	}
?>
<body>

<div class="menu">
	<a href="#" class="active">Strona główna</a>
	<a href="menu_parent.php">Moje dzieci</a>
  <a href="treasuer_menu/addStudent.php">Dodaj ucznia do klasy</a>
  <a href="treasuer_menu/addCyclicEvent.php">Dodaj event cykliczny</a>
  <a href="treasuer_menu/addOnceEvent.php">Dodaj event jednorazowy</a>
  <a href="treasuer_menu/settings.php">Ustawienia</a>
	<a href="logout.php"> Wyloguj się</a>
</div>

<div class="lewa_strona">
	<div class="naglowek" >
		<div id="class_name"></div>
		<h3> Wydarzenia klasy</h3>
		<div id="class_event"></div>
	</div>

</div>

<!--MODAL DETAILS -->
<div id="userModal" class="modal fade" >
 <div class="modal-dialog">
    <form action="treasurer_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    
		<h2>ZMIEŃ HASŁO </h2>
		<p>
		
		Nowe hasło: <br /> <input type="password" name="newPassword" /> <br /><br />
		<input type="submit" value="Zatwierdz" name="RequiredNewPasswordAccept"/>
		</p>
			
   </div>
  </form>
 </div>
</div>

<!--EVENT DETAILS -->
<div id="eventDetailsModal" class="modal fade" >
 <div class="modal-dialog">
    <form action="treasurer_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    
		<h2>Szczegoly </h2>
		<div id="event_details"></div>
<!--EVENT DETAILS		<p>
		Nowe hasło: <br /> <input type="password" name="newPassword" /> <br /><br />
		<input type="submit" value="Zatwierdz" name="RequiredNewPasswordAccept"/>
		</p>-->
			
   </div>
  </form>
 </div>
</div>

</body>
</html>


<script>
$(document).ready(function(){

	var zmienna='<?php echo $_SESSION['firstLog'];?>';
	
	if (zmienna){	
	$('#userModal').modal('show');
		}
	
	function fetch_event_details()
	{	
	
		var id=$(this).data("id3");
		$.ajax({
			url:"treasurer_helper.php",
			method:"POST",
			data:{function2call:'fetch_event_details', id:id},
			success:function(data){
				$('#event_details').html(data);
			}
		});
		
	}
	fetch_event_details();
	
	
	
	function fetch_event_list()
	{
		$.ajax({
			url:"treasurer_helper.php",
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
			url:"treasurer_helper.php",
			method:"POST",
			data:{function2call:'fetch_class_name'},
			success:function(data){
				$('#class_name').html(data);
			}
		});
		
	}
	fetch_class_name();


 

		
 }); 
  </script>