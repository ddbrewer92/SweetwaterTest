<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sweetwater_test";

// create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// get all comments from db
$sql = "SELECT comments FROM sweetwater_test";
$res = $conn->query($sql);

// loop over each comment and print to webpage
echo "Comments from DB<br>";
while ($row = $res->fetch_assoc()) {
    echo "<br>comment = " . $row['comments'];
}

$conn->close();
?> 