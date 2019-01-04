<?php

//code to generate column with hashed password in our DB

require_once "connection.php";

$conn = new MyDB();
$result = $conn->query(sprintf("select * from username"));
$conn->query("UPDATE username SET hashedPassword='asd' where login='admin'");
while ($row = mysqli_fetch_array($result))
{
	$conn->query(sprintf("UPDATE username SET hashedPassword='%s' where login='%s'", password_hash($row['password'], PASSWORD_BCRYPT), $row['login']));
    echo($row['login']) . "\t" . password_hash($row['password'], PASSWORD_BCRYPT) . "<br>";
}
$conn->close();
?>