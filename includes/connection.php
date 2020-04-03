<?php

try {
  // Create connection
  $conn = new mysqli(getenv('SQL_HOST'), getenv('SQL_USER'), getenv('SQL_PASS'), getenv('SQL_DB'));
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
}
catch (Exception $e) {
  echo "Unable to connect";
  exit;
}
?>
