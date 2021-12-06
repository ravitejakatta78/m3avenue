<?php

$servername = "localhost";

$username = "superqcp_fooduse";

$password = "fN&co]*qDG@r";

$dbname = "superqcp_m3avenue_dev";


// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection

if ($conn->connect_error) {

die("Connection failed: " . $conn->connect_error);

}

?>