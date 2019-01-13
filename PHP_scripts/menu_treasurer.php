<?php
session_start();

if (!isset($_SESSION['loggedIn']))
{
	header('Location: ../index.php');
	exit();
}

$_SESSION['treasurerAsParent'] = false;
require_once "connection.php";

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
	<link rel="stylesheet" type="text/css" href="treasuer_menu/style_table.css" title="Arkusz stylów CSS">
	
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
				<li class="nav-item active">
					<a class="nav-link" href="#">Strona główna<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="treasuer_menu/expenses.php">Wydatki klasowe</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="treasuer_menu/class_event_list.php">Zbiórki klasowe / Wydarzenia</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="treasuer_menu/payments.php">Wpłaty</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="treasuer_menu/students.php">Uczniowie</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="treasuer_menu/transfer.php">Wypłaty</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="treasuer_menu/settings.php">Ustawienia</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="menu_parent.php">Konto rodzica</a>
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
	<div class="row text-center">
		<div id="class_name" class="col text-center"></div>
	</div>
	
		<div class="text-center" id="accounts_amount">

	</div>
	<div class="row">
		<h5 class="col-md text-center">Stan kont dzieci:</h5>
		<button class="btn btn-default col-md-2 " onclick="window.open('treasuer_menu/general_report.php','_blank')" role="button">Generuj raport</button>
	</div>
</div>
<div id="students_balances_list" class="container-fluid"></div>



<!--MODAL CHANGE PASSWORD-->
<div id="userModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered container">
    <form action="treasurer_helper.php" method="post" id="user_formuser_form_password" enctype="multipart/form-data">
    <div class="modal-content">
		<div class="modal-header">
		<h3 class="text-center">Zmień hasło</h3>
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
									<label for="newPassword">Nowe hasło:</label>
									<input type="password" name="newPassword" class="form-control"/>
								</div>
								</div>
									<button type="submit" class="btn btn-lg btn-primary btn-block"  name="RequiredNewPasswordAccept">Zatwierdź</button>
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

	var zmienna='<?php
echo $_SESSION['firstLog']; ?>';
	
	if (zmienna){	
	$('#userModal').modal('show');
		}


    function fetch_accounts_amount()
    {
        $.ajax({
            url:"treasurer_helper.php",
            method:"POST",
            data:{function2call:'fetch_accounts_amount'},
            success:function(data){
                $('#accounts_amount').html(data);
            }
        });
        
    }
    fetch_accounts_amount();
	
	function students_balances_list()
    {
        $.ajax({
            url:"treasurer_helper.php",
            method:"POST",
            data:{function2call:'students_balances_list'},
            success:function(data){
                $('#students_balances_list').html(data);
            }
        });
        
    }
    students_balances_list();
	
	

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