<html> 
<head> 
	<title>add student </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
</head>

<body>

<?php include('menu.php'); ?> 

<div>
	<h1> Dodaj ucznia do klasy ...Ia... </h1> 
	<h3> Dane ucznia </h3>
	Imię: <input type="text" name="student_name" /> <br /> 
	Nazwisko: <input type="text" name="student_surname" /> <br />
	Data urodzenia: <input type="date" name="student_birthdata" /> <br />
	<h3> Dane rodzica </h3>
	Imię: <input type="text" name="parent_name" /> <br /> 
	Nazwisko: <input type="text" name="parent_surname" /> <br />
	Mail: <input type="date" name="parent_mail" /> <br />
	
	<br> <input type="button" class="btn_add" value="Zatwierdz" />
	


</div>








</body>
</html>