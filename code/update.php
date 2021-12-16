<?php

    include "config.php";
    
    // user_id 
    $user = $_POST['user'];

    // value to update
    $update = $_POST['update'];
    
    // cell
    $i = $_POST['cellNum'];
    
    // date 
    $date = $_POST['date'];
    
    
   // find column name
    switch (true) {
        case ($i == 0):
            $cell = 'paycode';
            break;
        case ($i == 1):
            $cell = 'clock_in';
            break;
        case ($i == 2):
            $cell = 'clock_out';
            break;
        case ($i == 3):
            $cell = 'schedule';    
            break;
        case ($i == 4):
            $cell = 'location';    
            break;
        default:
            $cell = null;
    }
    
    // update week with offset 
    $sql = "UPDATE praexroh_test.Timestamp
            SET ".$cell." = '".$update."'
            WHERE date = '".$date."' AND (user_id = '".$user."');";
    
    $result = mysqli_query($con,$sql);
    echo $sql;
    exit();
?>