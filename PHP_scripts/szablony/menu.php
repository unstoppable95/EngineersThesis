<html> 
<head> 
	<title>skarbnik </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
</head>

<body>

<div class="menu">
	<a href="index.php" class="active">Strona główna</a>
  <a href="addStudent.php">Dodaj ucznia do klasy</a>
  <a href="#">Dodaj event cykliczny</a>
  <a href="#">Dodaj event jednorazowy</a>
   <!-- Mozna zostawic te 2 na górze albo jedno to na dole z takim podmenu  -->
  <a class="has-sub-menu">
            Dodaj event</a><div class="show-sub-menu" tabindex="0"><span class="icon-angle-circled-down"></span></div>
            <ul class="sub-menu">
                <a href="#">Cykliczny</a>
                <a href="#">Jednorazowy</a>
            </ul>
        </a>
  
  
  <a href="settings.php">Ustawienia</a>
  <a href="#" onclick=javascript:logOut()>Wyloguj</a> <!-- Przekierowanie do funkcji wyloguj -->

</div>

<script>
         function logOut() {
           /* wylogowywanie */
            alert("Wylogowano poprawnie");
         }
 </script>


</body>
</html>