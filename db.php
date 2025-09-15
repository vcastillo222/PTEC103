
<?php
$host = "localhost";
$user = "root"; // default XAMPP MySQL user
$pass = "";     // default no password
$dbname = "forumdb";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
