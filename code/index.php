<?php
include "config.php";
$cred = False;

if(isset($_POST['butSubmit'])){

    /* 
        get username and password from text fields and convert 
        text to sql string
    */
    $uname = mysqli_real_escape_string($con,$_POST['txt_uname']);
    $password = mysqli_real_escape_string($con,$_POST['txt_pwd']);
    
    // check if fields are empty 
    if ($uname != "" && $password != ""){
        $hashedPwd =  hash('sha512',$password);
        // sql query to fetch all data from table to see if user is allow to have access 
        $sql_query = "select count(*) as cntUser from praexroh_test.User where username='".$uname."' and password='".$hashedPwd."'";
        
        // perform query search
        $result = mysqli_query($con,$sql_query);
        $row = mysqli_fetch_array($result);
        $count = $row['cntUser'];
        
        if($count > 0){
            $_SESSION['uname'] = $uname; // globally stores the user name
            
            // sql query to find user first name lastname, email
            $getUserInfo = "select first_name,last_name,email, a.user_id, a.business_id 
            from praexroh_test.History 
            right join praexroh_test.User as a using (user_id) 
            where a.username='".$uname."'";
             
            $userInfo = mysqli_query($con,$getUserInfo);
            
            while ($row = mysqli_fetch_array($userInfo)) {
                 
                // globally stores user first name
                $_SESSION['firstN'] = $row['first_name'];
                $_SESSION['lastN'] = $row['last_name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['business_id'] = $row['business_id'];
            }
            
            // sql query to get business name---------11.01-------
            $busUserInfo = "select business_name 
            from praexroh_test.Business 
            join praexroh_test.User as a using (business_id) 
            where a.username='".$uname."'";
            $busInfo = mysqli_query($con,$busUserInfo);
            while ($row = mysqli_fetch_array($busInfo)) {
                 
                // globally stores user first name
                $_SESSION['busN'] = $row['business_name'];
            }
            //---------------------------------------------------- 
            // sql query to find user role 
            $sql  = "SELECT role
                    FROM praexroh_test.User
                    WHERE username ='".$uname."'";

            // preform sql 
            $stmt =  mysqli_query($con,$sql);
            $role_row = mysqli_fetch_array($stmt);
            $role = $role_row['role'];
            
            $_SESSION["role"] = $role;
             
            // check conditions for user
            if($role == 'administrator'){
                header('Location: admin.php'); 
            }
            elseif($role == 'employee'){
                 header('Location: dashboard.php'); 
            } 
            else {
                  header('Location: manager_dashboard.php'); 
            }
        }
        else{
            $cred = True;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PraeLab</title>
    <!-----------------------  CSS style  ----------------------------->
    <link href="IndexStyle.css" rel="stylesheet">
</head>
<body>
    <div id="loginbox">
       <div id="header"> <h1>Login</h1></div>
        <div id="login">
            <form method="POST" action="">  <!--action="dashboard.html"> -->
                <label>Username:</label>
                <br>
                <input type="text" class="txtbox" id="txt_uname" name="txt_uname" required>
                <br>
                <br>
                <label>Password:</label>
                <br>
                <input type="password" class="txtbox" id="txt_uname" name="txt_pwd" required>
                <br>
                <br>
                <input type="submit" value="Submit" class="sButton" name="butSubmit" id="butSubmit">
                <br>
                <br>
                <span class="psw" style="font-size:75%;margin-left:5px;"><a href="add_business.html">Create Business Account</a></span>
            </form>
            <form action="add_business.html">
            </form>
            <?php 
            if($cred == True){
                echo "<span style='margin-left: 53px;color:red;font-size:15px;'>Invalid credentials. Please input valid username or password.</span>";
                $cred = False;
            }
            ?>
        </div>
    </div>
</body>
</html>
