<html> 
<head> 
	<title>Rodzic-panel głowny</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="parent_menu/p_style.css" title="Arkusz stylów CSS">

	
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

<?php
session_start();

if (!isset($_SESSION['loggedIn']))
	{
		header('Location: ../index.php');
		exit();
	}
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
	
		$_SESSION['firstLog']=null;
	}
	else{
		$_SESSION['firstLog']=true;
		
	}


	
?>  
</head>


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
</body>
</html>


<script>
$(document).ready(function(){
	
	var zmienna='<?php echo $_SESSION['firstLog'];?>';
	
	if (zmienna){	
	$('#userModal').modal('show');
		}
	
	
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