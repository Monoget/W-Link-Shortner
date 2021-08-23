<?php
$servername = "server147";
$username = "privxzvw_shortner";
$password = "qb&bZ*a9F)sn";
$dbname = "privxzvw_shortner";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}