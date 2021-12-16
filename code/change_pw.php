           <?php
           
           include "config.php";
               
               $verifypass = $_POST['current_pw'];
               $new = $_POST['new_pw'];

           if($verifypass){
               $hashedPwd =  hash('sha512',$verifypass);

            
               $sql = "SELECT password from praexroh_test.User where user_id='".$_SESSION['user_id']."'";
               
               //perform query search 
               $result = mysqli_query($con, $sql);
               $row = mysqli_fetch_array($result);
               $dbpassword = $row['password'];

               if($hashedPwd == $dbpassword)
               {
     
                   $new = mysqli_real_escape_string($con,$_POST['new_pw']);
     
                   $hashedNew =  hash('sha512',$new);
           
                   $sql = "UPDATE praexroh_test.User SET password = '".$hashedNew."' where user_id = '".$_SESSION['user_id']."';";
                    $result = mysqli_query($con, $sql);
                    
                    echo "Successfully changed password.";
                }
                           else{
               echo "Incorrect current password.";
           }
           }
                        
            ?>