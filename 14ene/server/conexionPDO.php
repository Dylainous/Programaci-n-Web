m
<?php
$servername = "localhost";
$username = "admin";
$password = "admin";
$database = "programcionweb";

try {
  $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password, $database);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
//$conn = null;
?>
