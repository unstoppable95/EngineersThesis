

<html> 
<head> 
	<title>Skarbnik-panel główny</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="treasuer_menu/style.css" title="Arkusz stylów CSS">
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
		header('Location: ../index.php');
		exit();
	}
$_SESSION['treasurerAsParent']=false;

	require_once "connection.php";
	
	//stworzenie polaczenia z baza danych -> @ wyciszanie bledow zeby dac swoje
	$conn = @new mysqli($servername, $username, $password, $dbName);
	
	if ($conn->connect_errno!=0){
		echo "Blad: ".$conn->connect_errno;// " Opis bledu: ".$conn->connect_error;
	}
	else {
				$sql="SELECT * FROM event";
				$results=$conn->query($sql);
	}
	
	$result=$conn->query(sprintf("select * from username where login='%s' and first_login=TRUE", mysqli_real_escape_string($conn, $_SESSION['user'])));
	$isUser = $result->num_rows;
	if ($isUser <= 0){
		echo "To nie jest pierwsze logowanie";
		$_SESSION['firstLog']=null;
	}
	else{
		$_SESSION['firstLog']=true;
		echo "Piersze lgoowanie";
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
		<h1> Konto klasy ...Ia... </h1>
		<h3> Wydarzenia klasy ...Ia... </h3>
	</div>

	<div class="tabela_wydarzen">
		<table width="600" border = "1" cellpaddin="1" cellspacing="1">
			<tr>
			<th>Nazwa</th>
			<th>Cena</th>
			<th>Data</th>
			<th> </th>
			<th> </th>
			

			</tr>
			
			<?php
			while($event=mysqli_fetch_assoc($results)){
				echo "<tr>";
				echo "<td>".$event['name']."</td>";
				echo "<td>".$event['price']."</td>";
				echo "<td>".$event['date']."</td>";
				echo "<td><input type='button' class='btn_details' value='Szczegoly' /></td>";
				echo "<td><input type='button' class='btn_delate' value='Usun' /></td>";
				echo "</tr>";
			}
			?>
			
		</table>
	</div>
</div>

<!--MODAL DETAILS -->
<div id="userModal" class="modal fade in">
 <div class="modal-dialog modal fade in">
  <form method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    
	<div id="class_list"></div>
   
    <div class="modal-footer">
     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
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
	
	/*function check_first_login()
	{
		
    $(window).on('load',function(){
        $('#userModal').modal('show');
    });


		$.ajax({
			url:"treasurer_helper.php",
			method:"POST",
			data:{function2call:'check'},
			success:function(data){
				$('#class_list').html(data);
			}
		});
		
	}
	check_first_login(); */
	
	$('body').append('<button>Login</button>');
	
	}

	  
	  
 }); 
  

</script>