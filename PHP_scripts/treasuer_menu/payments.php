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
				<li class="nav-item active">
					<a class="nav-link" href="payments.php">Wpłaty<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="students.php">Uczniowie</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="settings.php">Ustawienia </a>
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
	<div class="row">
		<div class="col-md-12 mt-3 text-center">
			  <h5> Uczniowie:</h5>
		</div>
	</div>
 </div>
 

<div id="students_list_payments" class="container-fluid"></div>


<!--MODAL MAKE PAYMENT -->
<div id="makePaymentModal" class="modal fade" >
 <div class="modal-dialog">
    <form action="../treasurer_helper.php" method="post" id="user_form" enctype="multipart/form-data">
   <div class="modal-content">
    
		<h3> Dokonaj wpłaty </h3>
		 <form action="parent_helper.php" method="post" id="user_form" enctype="multipart/form-data">
				Kwota: <input type="text" name="amountOfMoney" /> <br /><br />
				
				<input type="radio" name="typeOfAccount" value="normal" checked> Wpłać na konto dziecka<br />
				<input type="radio" name="typeOfAccount" value="class"> Wpłać na konto klasowe<br />
				<br />
				 <select name="paymentType">
					<option value="gotowka">Gotówka</option>
					<option value="konto">Na konto</option>
				</select>
				<br /><br />
				<input type="submit" value="Wpłać" name="makePayment2"/>
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
function fetch_students_list_payments()
    {
        $.ajax({
            url:"../treasurer_helper.php",
            method:"POST",
            data:{function2call:'fetch_students_list_payments'},
            success:function(data){
                $('#students_list_payments').html(data);
            }
        });
        
    }
    fetch_students_list_payments();  
    

	  
	  
	  
	$(document).on('click','.btn_makePayment',function(){
    var id=$(this).data("id3");
        $.ajax({
            url:"../treasurer_helper.php",
            method:"POST",
            data:{function2call: 'makePayment', id:id},
            dataType:"text",
                success:function(data){
                     }                          
                });
    
          
      });
    
      
      
 }); 
  

</script>