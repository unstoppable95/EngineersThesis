<html>
<head>
<title>Pierwszy skrypt php</title>
</head>
<body>
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
        echo "<br>" ."name: " . $row["name"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();


?> 
</body>
</html>