<html>
<head>
<title>Pierwszy skrypt php</title>
</head>
<body>

<button onclick="myFunction()">Try it</button>

 <?php
$servername = "localhost";
$username = "root";
$password = "";
$my_db_name="school_schema";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $my_db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

$sql = "SELECT name from child";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        //echo "<br>" ."name: " . $row["name"]. "<br>" ;
		$zmiennaName = $row["name"];
    }
} else {
    echo "0 results";
}

//dodawanie do tabeli wartości
/*
$sql_insert = "INSERT INTO child (name,surname,date_of_birth, parent_id,class_id) values ('Kasia','Piciowa','2018-08-16',1,1) ";
if ($conn->query($sql_insert) === TRUE) {
    echo "<br>"."New record created successfully";
} else {
    echo "<br>"."Error: " . $sql_insert . "<br>" . $conn->error;
}
*/

//usuwanie z tabeli wartosci 
/*$sql_del_val = "delete from child where id=1";
if ($conn->query($sql_del_val) === TRUE) {
    echo "<br>"."Record deleted successfully";
} else {
    echo "<br>"."Error: " . $sql_del_val. "<br>" . $conn->error;
}*/

//dodawanie kolumny do tabeli
/*
$sql_add = "alter table account add mytest varchar(20) default 'x' ";
if ($conn->query($sql_add) === TRUE) {
    echo "<br>"."New column created successfully";
} else {
    echo "<br>"."Error: " . $sql_add . "<br>" . $conn->error;
}
*/

/*
//usuwanie kolumny z tabeli 
$sql_del = "alter table account drop column mytest";
if ($conn->query($sql_del) === TRUE) {
    echo "<br>"."Column deleted successfully";
} else {
    echo "<br>"."Error: " . $sql_del . "<br>" . $conn->error;
}
*/

//dodawanie tabeli do bazy
/*$sql_add_table = "create table mytest ( imie varchar(20), pensja int, primary key (pensja))";
if ($conn->query($sql_add_table) === TRUE) {
    echo "<br>"."Table add successfully";
} else {
    echo "<br>"."Error: " . $sql_add_table . "<br>" . $conn->error;
}
*/

//usuwanie tabeli 
/*
$sql_del_table = "drop table mytest";
if ($conn->query($sql_del_table) === TRUE) {
    echo "<br>"."Table deleted successfully";
} else {
    echo "<br>"."Error: " . $sql_del_table . "<br>" . $conn->error;
}
*/

//modyfikacja danych w tabeli
/*
$sql_modify = "update child set name='Kaaaasia' where name='Hanina' ";
if ($conn->query($sql_modify) === TRUE) {
    echo "<br>"."Data modified successfully";
} else {
    echo "<br>"."Error: " . $sql_modify . "<br>" . $conn->error;
}
*/

/*zliczanie sesji php
session_start(); // można pominąć jeśli jest się pewnym że włączona jest opcja auto_start

if (!isset($_SESSION['count'])) { // jeśli zmienna nie jest zarejestrowana
    $_SESSION['count'] = 0;       // przypisz jej początkową wartość
} else {                          // jeśli jest zarejestrowana
    $_SESSION['count']++;         // zwiększ jej wartość
}

echo 'Strona odczytana '.$_SESSION['count'].' razy w ciągu tej sesji';

*/


$sql = "SELECT name from child";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       // echo "<br>" ."name after changes: " . $row["name"]. "<br>" ;
		$zmiennaName2 = $row["name"];
    }
} else {
    echo "<br>"."0 results";
}



$conn->close();
?>

<script>
function myFunction() {
    var btn = document.createElement("BUTTON");
	//$("#result").load( "index.php" );
	//document.write($("#result").load( "index.php" ));
	document.write('<?php echo $zmiennaName . ", name after change:  " . $zmiennaName2; ?>');

}
</script>

</body>
</html>