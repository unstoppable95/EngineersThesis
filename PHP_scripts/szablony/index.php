<?php
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
?>



<html> 
<head> 
	<title>skarbnik </title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="style.css" title="Arkusz stylów CSS">
</head>

<body>

<div class="menu">
	<a href="#" class="active">Strona główna</a>
  <a href="addStudent.php">Dodaj ucznia do klasy</a>
  <a href="addCyclicEvent.php">Dodaj event cykliczny</a>
  <a href="addOnceEvent.php">Dodaj event jednorazowy</a>
  <a href="settings.php">Ustawienia</a>
  <a href="#" onclick=javascript:logOut()>Wyloguj</a> <!-- Przekierowanie do funkcji wyloguj -->
</div>


<script>
         function logOut() {
           /* wylogowywanie */
            alert("Wylogowano poprawnie");
         }
 </script>


<div class="lewa_strona">
	<div class="naglowek" >
		<h1> Konto klasy ...Ia... </h1>
		<h3> Wydarzenia klasy ...Ia... </h3>
	</div>

	<div class="tabela_wydarzen">
		<table width="600" border = "1" cellpaddin="1" cellspacing="1">
			<tr>
			<th>Nazwa</th>
			<th>Cena</th>
			<th>Data</th>
			<th> </th>
			<th> </th>
			
			<!--	<td> Nazwa Wydarzenia </td>
				<td> wplacono </td>
				<td> / </td>
				<td> Pozostalo </td>
				<td> <input type="button" class="btn_szczegoly" value="Szczegoly" /> </td>
				<td> <input type="button" class="btn_usun" value="Usun" /> </td> -->
			</tr>
			
			<?php
			while($event=mysqli_fetch_assoc($results)){
				echo "<tr>";
				echo "<td>".$event['name']."</td>";
				echo "<td>".$event['price']."</td>";
				echo "<td>".$event['date']."</td>";
				echo "<td><input type='button' class='btn_details' value='Szczegoly' /></td>";
				echo "<td><input type='button' class='btn_delate' value='Usun' /></td>";
				echo "</tr>";
			}
			?>
			
		</table>
	</div>
</div>


</body>
</html>