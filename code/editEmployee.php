<?php
include "config.php";

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($con,$_POST['id']);
}

$fname = '';
if(isset($_POST['firstName'])){
   $fname = mysqli_real_escape_string($con,$_POST['firstName']);
}

$lname = '';
if(isset($_POST['lastName'])){
   $lname = mysqli_real_escape_string($con,$_POST['lastName']);
}

$username = '';
if(isset($_POST['userName'])){
   $username = mysqli_real_escape_string($con,$_POST['userName']);
}

$email = '';
if(isset($_POST['emailA'])){
   $email = mysqli_real_escape_string($con,$_POST['emailA']);
}

if($id > 0){
    // Check record exists
  $getUserInfo = "SELECT * FROM praexroh_test.History WHERE user_id ='".$id."'";
  $checkRecord = mysqli_query($con,$getUserInfo);
  $totalrows = mysqli_num_rows($checkRecord);
  
  if($totalrows > 0){
    // Update first name in History table
    if($totalrows['first_name'] != $fname){
        $query = "UPDATE praexroh_test.History SET first_name =  '".$fname."' WHERE user_id ='".$id."'" ;
        mysqli_query($con,$query);
    }
    // Update last name in History table
    if($totalrows['last_name'] != $lname){
        $query = "UPDATE praexroh_test.History SET last_name =  '".$lname."' WHERE user_id ='".$id."'" ;
        mysqli_query($con,$query);
    }
    
    // Update user name in user table
    if($totalrows['username'] != $username){
        $query = "UPDATE praexroh_test.User SET username =  '".$username."' WHERE user_id ='".$id."'" ;
        mysqli_query($con,$query);
    }
    // Update emailin History table
    if($totalrows['email'] != $email){
        $query = "UPDATE praexroh_test.History SET email =  '".$email."' WHERE user_id ='".$id."'" ;
        mysqli_query($con,$query);
    }
    echo 1;
    exit; 
    }
}
else{
    echo 0;
    exit; 
}
echo 0;
exit;
?>