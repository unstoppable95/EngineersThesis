<html> 
<head> 
	<title>Rodzic-historia</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="p_style.css" title="Arkusz stylów CSS">
</head>
<?php
session_start();

if (!isset($_SESSION['loggedIn']))
	{
		header('Location: ../index.php');
		exit();
	}
?>
<body>

<div class="menu">
	<a href="../menu_parent.php" >Strona główna</a>
	  <?php
		if ($_SESSION['type'] =="t"){
					$myVar='<a href="../menu_treasurer.php">Panel skarbnika</a>';
					echo $myVar;
				}
  ?>
  <a href="p_choiceChild.php">Wybór dziecka</a>
  <a href="p_history.php"  class="active">Historia wpłat</a>
  <a href="p_classAccount.php">Konto klasowe</a>
  <a href="p_settings.php">Ustawienia</a>
 <a href="../logout.php"> Wyloguj się</a>
</div>


<div class="lewa_strona">
	<h1> Historia wpłat </h1>
		...<br>
		...<br>
		Tu tabelka z całą chistorią wpłat, na końcu uregulowane ... <br>
		...<br> 
		...<br>


</div>








</body>