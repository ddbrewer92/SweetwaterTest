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

$candyComments = array();
$callComments = array();
$refferedComments = array();
$signatureComments = array();
$miscComments = array();

// loop over each comment and print to webpage
while ($row = $res->fetch_assoc()) {
    $upperComment = strtoupper($row['comments']);
    $origComment = $row['comments'];
    
    if (strpos($upperComment, "CANDY") !== false) {
      array_push($candyComments, $origComment);
    }
    else if (strpos($upperComment, " CALL") !== false) {
      array_push($callComments, $origComment);
    }
    else if (strpos($upperComment, "REFERR") !== false) {
      array_push($refferedComments, $origComment);
    }
    else if (strpos($upperComment, "SIGNATURE") !== false or strpos($upperComment, " SIGN ") !== false) {
      array_push($signatureComments, $origComment);
    }
    else {
      array_push($miscComments, $origComment);
    }
}

function createUnorderedList($sectionTitle, $contentsArray) {
  echo "<h2>$sectionTitle</h2>";
  echo "<ul>";
  foreach ($contentsArray as $content) {
    echo "<li>$content</li>";
  }
  echo "</ul><br>";
}

createUnorderedList("Candy Comments", $candyComments);
createUnorderedList("Call Comments", $callComments);
createUnorderedList("Referral Comments", $refferedComments);
createUnorderedList("Signature Comments", $signatureComments);
createUnorderedList("Misc Comments", $miscComments);

$conn->close();
?>