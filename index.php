<?php

// helper function to cut down on duplication
function createUnorderedList($sectionTitle, $contentsArray) {
  echo "<h2>$sectionTitle</h2>";
  echo "<ul>";
  foreach ($contentsArray as $content) {
    echo "<li>$content</li>";
  }
  echo "</ul><br>";
}

// db connection info
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
$sql = "SELECT orderid, comments FROM sweetwater_test";
$res = $conn->query($sql);

// array to hold the comments
$candyComments = array();
$callComments = array();
$refferedComments = array();
$signatureComments = array();
$miscComments = array();

// loop over each comment and push to appropriate array
while ($row = $res->fetch_assoc()) {
    $upperComment = strtoupper($row['comments']);
    $origComment = $row['comments'];
    
    // populate the comment arrays with the appropriate comments
    if (preg_match("/(CANDY|TAFFY|SMARTI)/i", $upperComment)) {
      array_push($candyComments, $origComment);
    }
    else if (preg_match("/( CALL|COMUNICARSE)/i", $upperComment)) {
      array_push($callComments, $origComment);
    }
    else if (preg_match("/(REFERR)/i", $upperComment)) {
      array_push($refferedComments, $origComment);
    }
    else if (preg_match("/(SIGNATURE| SIGN)/i", $upperComment)) {
      array_push($signatureComments, $origComment);
    }
    else {
      array_push($miscComments, $origComment);
    }

    // find the expected ship dates and update the database
    if (strpos($upperComment, "EXPECTED")) {
      // order id so we know who to update
      $orderid = $row['orderid'];
      // find just the expected ship date from the comment and convert it to a date object
      $startIndex = strpos($origComment, "Expected Ship Date: ") + strlen("Expected Ship Date: ");
      $date = date('Y-m-d', strtotime(substr($origComment, $startIndex, 8)));
      
      // update the order with the expected ship date
      $updateSql = "UPDATE sweetwater_test SET shipdate_expected='$date' WHERE orderid = $orderid";
      $conn->query($updateSql);
    }
}

// create the unordered lists
createUnorderedList("Candy Comments", $candyComments);
createUnorderedList("Call Comments", $callComments);
createUnorderedList("Referral Comments", $refferedComments);
createUnorderedList("Signature Comments", $signatureComments);
createUnorderedList("Misc Comments", $miscComments);

// clean up
$conn->close();
?>