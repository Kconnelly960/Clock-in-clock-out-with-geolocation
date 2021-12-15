<?php
session_start();
$host = "localhost"; /* Host name */
$user = "praexroh_Tester"; /* User */
$password = "praexroh_Tester123"; /* Password */
$dbname = "praexroh_test"; /* Database name */

$con = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}
?>