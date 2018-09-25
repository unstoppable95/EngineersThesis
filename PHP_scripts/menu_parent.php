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
	
	$amountOfChild = $conn->query(sprintf("SELECT * FROM child WHERE parent_id = (SELECT id FROM parent WHERE email = '".$_SESSION['user']."')" ));
	$_SESSION['amountOfChild'] = $amountOfChild->num_rows;
	
	if($_SESSION['amountOfChild']==1){
		$tmp=$conn->query(sprintf("SELECT * FROM child WHERE parent_id = (SELECT id FROM parent WHERE email = '".$_SESSION['user']."')" ));
		$row = mysqli_fetch_array($tmp);
		$_SESSION['choosenChild']=$row["id"];
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
  
  <?php
		if ((int)$_SESSION['amountOfChild'] > 1){
					$myVar='<a href="parent_menu/p_choiceChild.php">Wybór dziecka</a>';
					echo $myVar;
				}
  ?>
  
	<a href="parent_menu/p_history.php">Historia wpłat</a>
  <a href="parent_menu/p_classAccount.php">Konto klasowe</a>
  <a href="parent_menu/p_settings.php">Ustawienia</a>
  <a href="logout.php"> Wyloguj się</a>
</div>



<div class="lewa_strona">
	<div class="naglowek" >
		<h1> Konto rodzica </h1>
		<div id="child_name"></div>
		<div id="live_data"></div>
		
		<h3> Stan konta </h3>
		
		<form>
		<table>
			<tr><td>Stan konta dziecka:  </td><td><div id="accont_balance"></div></td></tr> 
		</table>
		</form>
		
		<h3> Dokonaj wpłaty </h3>
		 <form action="parent_helper.php" method="post" id="user_form" enctype="multipart/form-data">
				Kwota: <input type="text" name="amountOfMoney" /> <br /><br />
				
				<input type="radio" name="typeOfAccount" value="normal" checked> Wpłać na konto dziecka<br>
				<input type="radio" name="typeOfAccount" value="class"> Wpłać na konto klasowe<br>
				<br>
				 <select name="paymentType">
					<option value="gotowka">Gotówka</option>
					<option value="konto">Na konto</option>
				</select>
				<br><br>
				<input type="submit" value="Wpłać" name="MakePayment"/>
		</form>
		
	</div>

	
</div>

<!--MODAL CHANGE PASSWORD -->
<div id="userModal" class="modal fade" >
 <div class="modal-dialog">
    <form action="treasurer_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    
		<p>
		Nowe hasło: <br /> <input type="password" name="newPassword" /> <br /><br />
		<input type="submit" value="Zatwierdz" name="RequiredNewPasswordAccept"/>
		</p>
		
   </div>
  </form>
 </div>
</div>


<!--MODAL CHOOSE CHILD -->
<div id="chooseChildModal" class="modal fade" >
 <div class="modal-dialog">
    <form action="parent_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    
		<h2>WYBIERZ DZIECKO</h2>
		<h3> Lista Twoich dzieci: </h3>
		<div id="child_list"></div>
		
   </div>
  </form>
 </div>
</div>


</body>
</html>


<script>
$('#chooseChildModal').on('hidden.bs.modal', function () {
     location.reload();
});

$(document).ready(function(){

	var zmienna='<?php echo $_SESSION['firstLog'];?>';
	var amount='<?php echo $_SESSION['amountOfChild'];?>';
	var firstDisplay='<?php echo $_SESSION['firstDisplayParent'];?>';
	
	if (zmienna){	
		$('#userModal').modal('show');
	}
	else {
		if(amount > 1 && firstDisplay){
		$('#chooseChildModal').modal('show');
	}
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
	
	
		function fetch_child_name()
	{
		$.ajax({
			url:"parent_helper.php",
			method:"POST",
			data:{function2call:'fetch_child_name'},
			success:function(data){
				$('#child_name').html(data);
			}
		});
		
	}
	fetch_child_name();
	
	
		function fetch_child_list()
	{
		$.ajax({
			url:"parent_helper.php",
			method:"POST",
			data:{function2call:'fetch_child_list'},
			success:function(data){
				$('#child_list').html(data);
			}
		});
		
	}
	fetch_child_list();
	
	
	$(document).on('click','.btn_choose',function(){
	var id=$(this).data("id3");

	$.ajax({
		url:"parent_helper.php",
		method:"POST",
		data:{function2call: 'choose', id:id},
		dataType:"text",
		success:function(data){
			$('#chooseChildModal').modal('hide');
			alert(data);			
                     }  
                });  
      });
	
	
	
		function fetch_balance()
	{
		$.ajax({
			url:"parent_helper.php",
			method:"POST",
			data:{function2call:'fetch_balance'},
			success:function(data){
				$('#accont_balance').html(data);
			}
		});
		
	}
	fetch_balance();
	
	
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