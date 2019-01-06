<?php
session_start();

if (!isset($_SESSION['loggedIn'])) {
    header('Location: ../index.php');
    exit();
}
?>
<html> 
<head> 
    <title>Rodzic-wybór dziecka</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<!-- Our CSS -->
	<link rel="stylesheet" type="text/css" href="p_style.css" title="Arkusz stylów CSS">

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
		<a class="navbar-brand">Konto rodzica</a>
			<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="../menu_parent.php">Strona główna</a>
				</li>
				<li class="nav-item active">
					 <?php
					if ((int)$_SESSION['amountOfChild'] > 1)
					{
						$myVar = '<a class="nav-link" href="p_choiceChild.php">Wybór dziecka <span class="sr-only">(current)</span></a>';
						echo $myVar;
					}
					?>
				</li>				
				<li class="nav-item">
					<a class="nav-link" href="p_history.php">Historia wpłat</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="p_classAccount.php">Konto klasowe</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="p_settings.php">Ustawienia</a>
				</li>
				<li class="nav-item">
					<?php
					if ($_SESSION['type'] == "t")
					{
						$myVar = '<a class="nav-link" href="../menu_treasurer.php">Panel skarbnika</a>';
						$_SESSION['treasurerAsParent'] = true;
						echo $myVar;
					}
					?>
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
	<div class="row text-center">
		<h5 class="mt-2 col-md-12">Twoje dzieci:</h5>
	</div>
</div>

<div class="container mt-2">
	<div id="child_list"></div>
</div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>


<script>
$(document).ready(function(){
    function fetch_data()
    {
        $.ajax({
            url:"../parent_helper.php",
            method:"POST",
            data:{function2call:'fetch_child_list'},
            success:function(data){
                $('#child_list').html(data);
            }
        });
        
    }
    fetch_data();

    $(document).on('click','.btn_choose',function(){
    var id=$(this).data("id3");
    if(confirm("Czy jestes pewny, ze chcesz zmienic dziecko?"))  
           {
    $.ajax({
        url:"../parent_helper.php",
        method:"POST",
        data:{function2call: 'choose', id:id},
        dataType:"text",
        success:function(data){
            alert(data);            
                     }  
                });  
           }  
      });

    
 }); 
  

</script>