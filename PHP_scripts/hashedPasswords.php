<?php

//code to generate column with hashed password in our DB

require_once "connection.php";

$conn = new MyDB();
$result = $conn->query(sprintf("select * from username where type='t' or type='p'"));
while ($row = mysqli_fetch_array($result))
{
    $hash = password_hash('a', PASSWORD_BCRYPT);
	$conn->query(sprintf("UPDATE username SET hashedPassword='$hash' where login='%s'", $row['login']));
    echo($row['login']) . "\t" . $hash . "<br>";
}
$conn->close();
?>