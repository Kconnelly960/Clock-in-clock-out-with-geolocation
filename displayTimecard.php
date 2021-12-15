<?php
include "config.php";


    // fetch data from the database
    $user = $_GET['user_id'];
    $sql = "select * from praexroh_test.Timestamp where (week(date)=week(now())) AND (user_id = '".$user."');";
    $result = mysqli_query($con,$sql);
    
    // Store each row of data for a week into an array
    while($row = mysqli_fetch_array($result)){
        
        // get current date
        $date = strtotime($row['date']);
        
        // find which date of the week it is
        $day = date('l', $date);
        
        // add to set array
        $set[$day] = $row;
        
        // include it in a days array
        $days[] = $day;
        }
        
        // if any days missing, set value to ""
        $missing = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ($missing as $value) {
        
            if (!in_array($value, $days)){
            $row['paycode'] = "";
            $row['clock_in'] = "";
            $row['clock_out'] = "";
            $row['schedule'] = "";
            $row['location'] = "";
            $set[$value] = $row;
        }
    }
    echo json_encode($set);
    exit();
?>