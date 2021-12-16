<?php
include "config.php";

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($con,$_POST['id']);
}
if($id > 0){

  // Check record exists
  $getUserInfo = "SELECT * FROM praexroh_test.User WHERE user_id ='".$id."'";
  $checkRecord = mysqli_query($con,$getUserInfo);
  $totalrows = mysqli_num_rows($checkRecord);

  if($totalrows > 0){
      
      $date = date('Y/m/d');
      
    
    // Update end_date in History table 
    $getUrInfo = "SELECT * FROM praexroh_test.User WHERE user_id ='".$id."'";
    $checkRecordU = mysqli_query($con,$getUrInfo);
    $totalR = mysqli_num_rows($checkRecordU);
    if($totalR > 0){
        // set end_date in history table 
        $query = "UPDATE praexroh_test.History SET end_date =  '".$date."' WHERE user_id ='".$id."'" ;
        mysqli_query($con,$query);
    }
    
    // Delete record from user table
    $query = "DELETE FROM praexroh_test.User WHERE user_id =".$id;
    mysqli_query($con,$query);
    
    
    
    // Check Timestamp table
    $getUserInfo = "SELECT * FROM praexroh_test.Timestamp WHERE user_id ='".$id."'";
    $checkRecord = mysqli_query($con,$getUserInfo);
    $totalrows = mysqli_num_rows($checkRecord);
    if($totalrows > 0){
        // Delete record from timestamp table
        $query = "DELETE FROM praexroh_test.Timestamp WHERE user_id =".$id;
        mysqli_query($con,$query);
    }
    echo 1;
    exit;
  }else{
    echo 0;
    exit;
  }
}

echo 0;
exit;
?>