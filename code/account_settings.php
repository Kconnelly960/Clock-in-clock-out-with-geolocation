<?php
include "config.php";

// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['firstN']; ?>'s Account</title>
    <!-----------------------  CSS style  ----------------------------->
    <link href="SettingsStyle.css" rel="stylesheet">
     <!-- link jquery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-----------------------  HTML ----------------------------->
</head>
<body>
   <div id="dashboardHeader">
    <h1><?php echo $_SESSION['firstN']; ?>'s Account Settings</h1> 
   </div>

   <div class="sidenav">
       <!-- Dashboard -->
       <!-- 14.01 Distinguish user for sidebar options -->
       <?php 
            if($_SESSION["role"] == 'manager'){
                echo "<a class='dash' href='manager_dashboard.php'>My Dashboard</a>";
            }
            else{
                echo "<a class='dash' href='dashboard.php'>My Dashboard</a>";
            }
        ?>
        <a class="timecard" href="view_timecard.php">Timecard</a>
        <?php 
        
            if($_SESSION["role"] == 'manager'){
                echo "<a class='manage' href='manage_employees.php'>Manage Employees</a>";
            }
        ?>
        <a class="account" href="account_settings.php">Account Settings</a>
        <a href="logout.php">Logout</a>
    </div>
     <!-----------------------  Javascript  ----------------------------->
   <script type = "text/javascript">
   function togglePasswordChange(){
       var changePassBoxDiv = document.getElementById("changePassBox");
       if(changePassBoxDiv.style.visibility !== "visible"){
           document.getElementById("changePassBox").style.visibility = "visible";
       } else {
           document.getElementById("changePassBox").style.visibility = "hidden";
       }
        $('#newPass').val("");
        $('#currentPass').val("");
        $('#confirmPass').val("");
         document.getElementById("passAlert").style.visibility = "hidden";
         document.getElementById("shortPass").style.visibility = "hidden";
   }
   function vlidatePass(){
       //storing values from each text field
       var newPassVal = document.getElementById("newPass").value;
       var confirmPassVal = document.getElementById("confirmPass").value;
       var currentPassVal = document.getElementById("currentPass").value;
       
       //new password and confirm password fields are filled
       if(newPassVal.length > 0 && confirmPassVal.length > 0){
           //new password entry matches the confirm password entry

               document.getElementById("shortPass").style.visibility = "hidden";
               if(newPassVal === confirmPassVal){
                   //current password textfield is filled
                   if(currentPassVal.length > 0) {
                       //enable submit button
                       document.getElementById("butSubmit").disabled = false;
                   }
                   //hide "passwords do not match!" message
                   document.getElementById("passAlert").style.visibility = "hidden";
                   
                   } else {
                       //disable submit button
                       document.getElementById("butSubmit").disabled = true;
                       //display "passwords do not match!" message
                       document.getElementById("passAlert").style.visibility = "visible";
                   }

        } else {
            //hide "passwords do not match!" message
            document.getElementById("passAlert").style.visibility = "hidden";
        }
    }
    
    function checkLength(){
        
         var newPassVal = document.getElementById("newPass").value;
         
        if(newPassVal.length < 8){
            
              document.getElementById("shortPass").style.visibility = "visible";
              return false;
           }
        return true;
    }
    
       function hide(){
        $('#newPass').val("");
        $('#currentPass').val("");
        $('#confirmPass').val("");
        document.getElementById("passAlert").style.visibility = "hidden";
        document.getElementById("shortPass").style.visibility = "hidden";
        document.getElementById("changePassBox").style.visibility = "hidden";
   }
    
    // get current url to determine active link
    var url = window.location.href;
    
    var a = url.lastIndexOf('/');
    var b = url.substring(a + 1);
    
    if (b == "dashboard.php")
    {
        $('.dash').addClass('current');
    }
    else if (b == "manager_dashboard.php")
    {
        $('.dash').addClass('current');
    }
    else if (b == "view_timecard.php"){
        $('.timecard').addClass('current');
    }
    else if (b == "manage_employees.php"){
        $('.manage').addClass('current');
    }
    else{
        $('.account').addClass('current');
    }
    

   </script>
       
    
    <!--4.06-->
    <div id="accountSettings" class="tabContent" style="display:block">
        <label>Company:</label>
        <label><?php echo $_SESSION['busN']; ?></label>
        <br>
        <br>
        <label>First Name:</label>
        <label><?php echo $_SESSION['firstN']; ?></label>
        <br>
        <br>
        <label>Last Name:</label>
        <label><?php echo $_SESSION['lastN']; ?></label>
        <br>
        <br>
        <label>Username:</label>
        <label><?php echo $_SESSION['uname']; ?></label>
        <br>
        <br>
        <label>Email:</label>
        <label><?php echo $_SESSION['email']; ?></label>
        <br>
        <br>
        <!--<span class="psw" style="font-size:95%;margin-left:5px;"><a id="changePasswordLink" href="" onClick="togglePasswordChange()">Change Password</a></span>-->
        <button id="changePassBtn" onClick="togglePasswordChange()">Change Password</button>
        <br>
        <br>
        <div id="changePassBox">
                <table>
                <TR>
                    <TD><label>Enter Current Password </label></TD>
                    <TD><input id="currentPass" name="currentPass" type="password" required onkeyup="vlidatePass()"></TD>
                </TR>
                <TR>
                    <TD><label>Enter New Password </label></TD>
                    <TD><input id="newPass" name="newPass" type="password" required onkeyup="vlidatePass()"></TD>
                </TR>
                <TR>
                    <TD><label>Confirm New Password </label></TD>
                    <TD><input id="confirmPass" name="confirmPass" type="password" onkeyup="vlidatePass()" required></TD>
                </TR>
                <TR id="passAlert">
                    <TD><label>Passwords do not match! </label></TD>
                </TR>
                <TR id="shortPass" style="color: red;font-size: 80%;">
                    <TD><label>Password must be at least 8 characters long! </label></TD>
                </TR>
                <tr>
                    <TD text-align="right" id="cancelChangePassBtn"><button onClick="togglePasswordChange()">Cancel</button></TD>
                    <TD><button type="submit" value="Submit" class="sButton" id ="butSubmit" disabled>Submit</button>       


           </TD></tr>
                </table>
            
        </div>
        
        
        </div>
        <script>

        // Change password
        $('#butSubmit').click( ()=> {
            
                if (checkLength()){

                    // AJAX Request
                    $.ajax({
                    url: 'change_pw.php',
                    type: 'POST',
                    data: { 
                        new_pw: $('#newPass').val(),
                        current_pw: $('#currentPass').val()
                    },
                    success: function(response) {
                        alert(response);
                        hide();
                    }
                    });
                    
                }
        });

        </script>
        
        <br>
        
    <!--</form>   --> 
    </div>
    
</body>
</html>