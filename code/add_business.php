<?php
    include "config.php"; 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    //create new DOMDocument using add_business.html
    //create mysqli using db
    $dom = new DOMDocument();
    $dom->loadHTML("add_ business.html");
    $mysqli = new mysqli($host, $user, $password, $dbname);

// Trello Card 5.15
    $company_name = $_POST["company_name"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    //$confirm_password = $_POST["password_confirmation"];
    $hashedPwd = hash('sha512',$password);
    $email = $_POST["email"];
    $start_date = date("Y-m-d");
    $manager = $_SESSION["user_id"];
    $loc = $_POST["location"];
    $maxD = $_POST["maxD"];
    $site_name = $_POST["site_name"];


    $bus_sql = "INSERT INTO Business (business_name, business_id, location, max_distance, location_name)
    VALUES ('$company_name', NULL, '$loc', '$maxD', '$site_name')";
    $mysqli->query($bus_sql); 

    // sql query to business_id from Business table
    $sql_query = "select business_id from Business where business_name='".$company_name."'";
            
    // perform query search
    $result = mysqli_query($con,$sql_query);
    $row = mysqli_fetch_array($result);
    $business_id = $row['business_id'];


    $user_sql = "INSERT INTO User (user_id, username, password, manager_id, business_id, role)
    VALUES (NULL, '$username', '$hashedPwd', NULL, '$business_id', 'administrator')";
    if(!($mysqli->query($user_sql))){
        echo $mysqli->error;
    };
    // sql query to fetch user_id from User tabl 
    $sql_query = "select user_id from User where username='".$username."'";
            
    // perform query search
    $result = mysqli_query($con,$sql_query);
    $row = mysqli_fetch_array($result);
    $user_id = $row['user_id'];

    $hist_sql = "INSERT INTO History (history_id, first_name, last_name, email, start_date, end_date, user_id)
    VALUES (NULL, '$first_name', '$last_name', '$email', '$start_date', NULL, '$user_id')";
    $mysqli->query($hist_sql);
    
    header("Location: http://www.praelab.com/index.php");
?>