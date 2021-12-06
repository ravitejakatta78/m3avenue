<?php

$servername = "localhost";

$username = "foodqc1j_m3avenu";

$password = "%$9#Rfg,$}J@";

$dbname = "foodqc1j_m3avenue";


// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection

if ($conn->connect_error) {

die("Connection failed: " . $conn->connect_error);

}

?>