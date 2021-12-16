<?php
include "config.php";
// Queries the currently logged in user's last time punch
$user = $_POST['user_id'];
$sql = "SELECT * FROM Timestamp WHERE user_id = '".$user."' ORDER BY timestamp_id DESC LIMIT 1;";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
$timestamp = $row['timestamp_id'];
$ci = $row['clock_in'];
$co = $row['clock_out'];

// If there is no clock-in, this is the user's first timepunch
if ($ci == null)
{
    //clock-in event triggered, insert data into chart
    $date = $_POST['punchdate'];
    $time = $_POST['punchtime'];
    $location = $_POST['location'];
    $sql_punchin = "INSERT INTO praexroh_test.Timestamp (date, paycode, clock_in, user_id, schedule, location) VALUES ('".$date."', 'REG', '".$time."', '".$user."', '09:00 - 17:00', '".$location."');";
    $stmt = mysqli_prepare($con, $sql_punchin);
    mysqli_stmt_execute($stmt);
}
// Else, if there is no clock-out for the last timepunch, then clock-out event triggered
elseif ($co == NULL){
    //Update the table row to include the clock-out
    $time = $_POST['punchtime'];
    $sql_punchout = "UPDATE praexroh_test.Timestamp SET clock_out = '".$time."' WHERE timestamp_id = '".$timestamp."'";
    $stmt = mysqli_prepare($con, $sql_punchout);
    mysqli_stmt_execute($stmt);
}
// Else new clock in
else{
    //clock-in event triggered, insert data into chart
    $date = $_POST['punchdate'];
    $time = $_POST['punchtime'];
    $location = $_POST['location'];
    $sql_punchin = "INSERT INTO praexroh_test.Timestamp (date, paycode, clock_in, user_id, schedule, location) VALUES ('".$date."', 'REG', '".$time."', '".$user."', '09:00 - 17:00', '".$location."');";
    $stmt = mysqli_prepare($con, $sql_punchin);
    mysqli_stmt_execute($stmt);
}
exit();
?>