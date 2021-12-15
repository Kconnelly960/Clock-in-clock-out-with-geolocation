

<?php




    include "config.php"; 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //create new DOMDocument using add_employee.html
    //create mysqli using db
    $dom = new DOMDocument();
    $dom->loadHTML("add_employee.html");
    $mysqli = new mysqli($host, $user, $password, $dbname);
    
    //Function to print to console log, using for debuggin purposes 
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }


    $userrole = $_SESSION['role'];
    $buss_id = $_SESSION['business_id'];

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];

    $username = $_POST["username"];
    $pass = $_POST["pass"];
    $passHash = hash('sha512',$pass);

    $email = $_POST["email"];
    
    if ($_POST["x"] == 'Yes'){
        $role = "manager";
    }else{
        $role = "employee";
    }
    
    $manager = $_SESSION["user_id"];

    /* sql query to business_id from Business table
    $sql_query = "select business_id from User where user_id='".$_SESSION['business_id']."'";
        
    // perform query search
    $result = mysqli_query($con,$sql_query);
    $row = mysqli_fetch_array($result);
    $buss_id = $row['business_id'];

    */
    
    $start_date = date('Y-m-d'); 

    $sql_one = "INSERT INTO User (user_id, username, password, manager_id, business_id, role)
    VALUES (NULL, '$username', '$passHash', '$manager', '$buss_id', '$role')";
    if(!($mysqli->query($sql_one))){
        echo $mysqli->error;
    } 
    
    
    // sql query to fetch user_id from User tabl
    $sql_query = "select user_id from User where username='".$username."'";
    

        
    // perform query search
    $result = mysqli_query($con,$sql_query);
    $row = mysqli_fetch_array($result);
    $user_id = $row['user_id'];

    $sql_two = "INSERT INTO History (history_id, first_name, last_name, email, start_date, end_date, user_id)
    VALUES (NULL, '$first_name', '$last_name', '$email', '$start_date', NULL, '$user_id' )";
    if(!($mysqli->query($sql_two))){
        echo $mysqli->error;
    };
    
    //leave out for now for testing purposes 
    
    //if user is manager redirect to manager dashboard
    //else redirect to admin page
    //not implemented
    //header("Location: http://www.praelab.com/dashboard.html");
    //exit();

            if($userrole == 'administrator'){
                header('Location: admin.php'); 
            }
            // else user is manager --> navigate to manager dashboard
            else {
                  header('Location: manage_employees.php'); 
            }
?>