<html> 
<head> 
	<title>Rodzic-konto klasowe</title>
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
  <a href="p_history.php">Historia wpłat</a>
  <a href="p_classAccount.php" class="active">Konto klasowe</a>
  <a href="p_settings.php">Ustawienia</a>
  <a href="../logout.php"> Wyloguj się</a>
</div>


<div class="lewa_strona">
	<h1> Konto klasowe </h1>
	<h3> Stan konta klasowego dziecka </h3>
	
	<form>
		<table>
			<tr><td>Konto konta klasowego dziecka:  </td><td>...kwota...</td></tr> 
			<tr><td>Suma pieniędzy na koncie klasowym całej klasy:  </td><td>...kwota...</td></tr> 
		</table>
	</form>
		
	<h3> Wydatki z konta klasowego </h3>
			...<br>
		...<br>
		Tu tabelka z wydatkami klaoswymi ... <br>
		...<br> 
		...<br>


</div>








</body>