<?php
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Asia/Kolkata');
 
// variables used for jwt
$key = "m3avenue_key";
$iss = "http://sansdigitals.com";
$aud = "http://sansdigitals.com";
$iat = time();
$nbf = time();

?>