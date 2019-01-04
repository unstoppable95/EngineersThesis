<?php

//code to generate column with hashed password in our DB

require_once "connection.php";

$conn = new MyDB();
$result = $conn->query(sprintf("select * from username"));
$conn->query("UPDATE username SET hashedPassword='asd' where login='admin'");
while ($row = mysqli_fetch_array($result))
{
    $hash = password_hash($row['password'], PASSWORD_BCRYPT);
	$conn->query(sprintf("UPDATE username SET hashedPassword='$hash' where login='%s'", $row['login']));
    echo($row['login']) . "\t" . $hash . "<br>";
}
$conn->close();
?>