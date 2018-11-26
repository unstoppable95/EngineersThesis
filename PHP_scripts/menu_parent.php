<html> 
<head> 
	<title>Rodzic-panel głowny</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">


	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!-- Our CSS -->
	<link rel="stylesheet" type="text/css" href="parent_menu/p_style.css" title="Arkusz stylów CSS">

	<!-- Example of icons -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">


<?php
session_start();

if (!isset($_SESSION['loggedIn']))
{
	header('Location: ../index.php');
	exit();
}

require_once "connection.php";

// stworzenie polaczenia z baza danych -> @ wyciszanie bledow zeby dac swoje

$conn = @new mysqli($servername, $username, $password, $dbName);

if ($conn->connect_errno != 0)
{
	echo "Blad: " . $conn->connect_errno; // " Opis bledu: ".$conn->connect_error;
}
else
{
	$sql = "SELECT * FROM event";
	$results = $conn->query($sql);
}

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
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
		<a class="navbar-brand">Konto rodzica</a>
			<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
				<li class="nav-item active">
					<a class="nav-link" href="#">Strona główna <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<?php
					if ($_SESSION['type'] == "t")
					{
						$myVar = '<a class="nav-link" href="menu_treasurer.php">Panel skarbnika</a>';
						$_SESSION['treasurerAsParent'] = true;
						echo $myVar;
					}
					?>
				</li>
				<li class="nav-item">
					 <?php
					if ((int)$_SESSION['amountOfChild'] > 1)
					{
						$myVar = '<a class="nav-link" href="parent_menu/p_choiceChild.php">Wybór dziecka</a>';
						echo $myVar;
					}
					?>
				</li>				
				<li class="nav-item">
					<a class="nav-link" href="parent_menu/p_history.php">Historia wpłat</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="parent_menu/p_classAccount.php">Konto klasowe</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="parent_menu/p_settings.php">Ustawienia</a>
				</li>
			</ul>

			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i>Wyloguj się</a>
			</li>
			</ul>
	  </div>
	</nav>  

<div class="container">
	<div class="row">
		<div id="child_name" class="mt-2 col-md-12 text-center"></div>
	</div>
</div>
	
<div id="live_data" class="container-fluid"></div>

<!--<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			  <h5>Stan konta</h5>
		</div>
	</div>
 </div>		-->
		
<div id="accont_balance" class="container"></div> 



<!--MODAL CHANGE PASSWORD -->
<div id="userModal" class="modal fade" >
 <div class="modal-dialog">
    <form action="treasurer_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    
		<p>
		Nowe hasło: <br /> <input type="password" name="newPassword" required/> <br /><br />
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
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>
</html>


<script>
$('#chooseChildModal').on('hidden.bs.modal', function () {
     location.reload();
});

$(document).ready(function(){

	var zmienna='<?php
echo $_SESSION['firstLog']; ?>';
	var amount='<?php
echo $_SESSION['amountOfChild']; ?>';
	var firstDisplay='<?php
echo $_SESSION['firstDisplayParent']; ?>';
	
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

	
	
	// delete

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
			fetch_balance();
			
                     }  
                });  
           }  
      });  
 }); 
  

</script>