<?php
include "config.php";

// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
} 
    // fetch data from the database that belongs to the business throught 
    // business id
    $getUserInfo = "select first_name,last_name, user_id, email, a.username as c
            from praexroh_test.History 
            join praexroh_test.User as a using (user_id) 
            join praexroh_test.Business as b using (business_id)
            where a.manager_id IS NOT NULL
            and b.business_name ='".$_SESSION['busN']."'
            ORDER BY last_name, first_name";
             
    $userInfo = mysqli_query($con,$getUserInfo);
    
?>
<!-----------------------  Admin page 11.01 ----------------------------->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['busN']; ?>'s Administrator</title>

    <!-----------------------  CSS style  ----------------------------->
    <link href="AdminStyle.css" rel="stylesheet">

    <!-----------------------  HTML ----------------------------->
</head>
<body>
   <div class="sidenav">
   <!---- <a>Dashboard</a> -->
   <button style="color:black" class="tablinks" onclick="openPage(event, 'table');  hide()">My Dashboard</button>
   <button class="tablinks" onclick="openPage(event, 'accountSettings'); hide()">Account Settings</button>
    <a href="logout.php">Logout</a>
</div>

<div id="table" class="tabContent">

    <div id="dashboardHeader">
        <h1><?php echo $_SESSION['busN']; ?>'s Dashboard</h1> 
       </div> 
       <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
    <table id="myTable">
        <tr>
            <th style="height:30px;"scope="col" colspan="4">Employees</th>
        </tr>
         <?php while($row = mysqli_fetch_array($userInfo)):;?>
            <tr class="record">
                <td><?php echo $row['first_name'] ." ".$row['last_name'];?></td>
                <td><button id="timecardBB" class="tableb"  onclick="timecard('<?php echo $row['user_id'];?>',
                '<?php echo $row['first_name'];?>','<?php echo $row['last_name'];?>')">Time card</button></td>
                <td><button id="edit" class="tableb" onclick="editt('<?php echo $row['user_id'];?>','<?php echo $row['first_name'];?>'
                ,'<?php echo $row['last_name'];?>','<?php echo $row['c'];?>','<?php echo $row['email'];?>')">Edit/View</button></td>
                <td><input data-id="<?php echo $row['user_id'];?>" type='button' value="Remove" class="deletebb tableb"/></td>
            </tr>
        <?php endwhile;?>
    </table>
    <div class="add_employee_button">
        <a href="add_employee.html">Add Employee</a>
    </div>
</div>

<!--4.06-->
<div id="accountSettings" class="tabContent">

     <div id="dashboardHeader" style= "left: 0">
        <h1><?php echo $_SESSION['busN']; ?>'s Dashboard</h1>
       </div> 
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
         <button id="changePassBtn" onclick="togglePasswordChange()">Change Password</button>
         <br>
        <div id="changePassBox">
            <table>
                <tr>
                    <td><label>Enter Current Password </label></td>
                    <td><input id="currentPass" name="currentPass" type="password" required onkeyup="vlidatePass()"></td>
                </tr>
                <tr>
                    <td><label>Enter New Password </label></td>
                    <td><input id="newPass" name="newPass" type="password" required onkeyup="vlidatePass()"></td>
                </tr>
                <tr>
                    <td><label>Confirm New Password </label></td>
                    <td><input id="confirmPass" name="confirmPass" type="password" onkeyup="vlidatePass()" required></td>
                </tr>
                <tr id="passAlert">
                    <td><label>Passwords do not match! </label></td>
                </tr>
                <TR id="shortPass" style="color: red;font-size: 80%;">
                    <TD><label>Password must be at least 8 characters long! </label></TD>
                </TR>
                <tr>
                    <td text-align="right" id="cancelChangePassBtn"><button onClick="togglePasswordChange()">Cancel</button></td>
                    <td><button type="submit" value="Submit" class="sButton" id ="butSubmit" disabled>Submit</button></td>
                </tr>
            </table>
                        
        </div>
</div>

    <!--11.01-->
<div id="editem" class="tabContent editEm">
    <div id="dashboardHeader" style= "left: 0">
        <h1><?php echo $_SESSION['busN']; ?>'s Dashboard</h1>
       </div> 
        <button id="backToTable" class="" onclick="backss()">< Back</button>
        <br>
        <br>
        <label>User ID:</label>
        <label id="iid"></label>
        <br>
        <br>
        <label>First Name:</label>
        <label id="ffname"></label>
        <br>
        <br>
        <label>Last Name:</label>
        <label id="llname"></label>
        <br>
        <br>
        <label>Username:</label>
        <label id="uuname"></label>
        <br>
        <br>
        <label>Email:</label>
        <label id="eemail"></label>
        <br>
        <br>
        <button id="canceBtn" onclick="edittE()">Edit</button>
          
</div>
<!-----------------------                           Edit                   ----------------------------->
 <div id="editSettings" class="tabContent editSettings" >
     <div id="dashboardHeader" style= "left: 0">
        <h1><?php echo $_SESSION['busN']; ?>'s Dashboard</h1>
       </div>     
            <label>First Name:</label>
            <input type="text" id ="FName">
            <br>
            <br>
            <label>Last Name:</label>
            <input type="text" id ="LName">
            <br>
            <br>
            <label>Username:</label>
            <input type="text" id ="UName">
            <br>
            <br>
            <label>Email:</label>
            <input type="text" id ="EMail">
            <br>
            <br>
            <button id="cancelBtn" onclick="backsss()">Cancel</button>
            <input id="changeSettingsBtn" type='button' value="Change Settings" class=""/>
      
    </div>

 <!-------------------------------      View Time Card ------------------------------------------>  
<div id="edtimecard" class="tabContent EmTimecard">
        <div id="dashboardHeader" style= "left: 0">
        <h1><?php echo $_SESSION['busN']; ?>'s Dashboard</h1>
       </div> 
    <button id="backToTable" class="" onclick="backToAdmin()">< Back</button>
    <h2><span id="timecardowner"></span>'s Timecard</h2>
    
    <div class="viewtimeCard1">
    <table class="viewtimeCard">
        <tr id="r1">
            <th scope="col" style="width:110px"><button id="lbtn" onclick="prevWeek()"><</button><span id='weekDisplay'></span><button id="rbtn" onclick="nextWeek()">></button></th>
            <th scope="col">Paycode</th>
            <th scope="col">Clock-In</th>
            <th scope="col">Clock-Out</th>
            <th scope="col">Schedule</th>
            <th scope="col">Location</th>
        </tr>
        <tr id="r2">
            <th scope="row">Sunday</th>
            <td><span id='sunPay' class="switch"><?php echo $set['Sunday']['paycode'] ?></span></td>
            <td><span id='sunCkin' class="switch"><?php echo $set['Sunday']['clock_in'] ?></span></td>
            <td><span id='sunCkOut' class="switch"><?php echo $set['Sunday']['clock_out'] ?></span></td>
            <td><span id='sunSch' class="switch"><?php echo $set['Sunday']['schedule'] ?></span></td>
            <td><span id='sunLoc' class="switch"><?php echo $set['Sunday']['location'] ?></span></td>
        </tr>
        <tr id="r3">
            <th scope="row">Monday</th>
            <td><span id='monPay' class="switch"><?php echo $set['Monday']['paycode'] ?></span></td>
            <td><span id='monCkin' class="switch"><?php echo $set['Monday']['clock_in'] ?></span></td>
            <td><span id='monCkOut' class="switch"><?php echo $set['Monday']['clock_out'] ?></span></td>
            <td><span id='monSch' class="switch"><?php echo $set['Monday']['schedule'] ?></span></td>
            <td><span id='monLoc' class="switch"><?php echo $set['Monday']['location'] ?></span></td>
        </tr>
        <tr id="r4">
            <th scope="row">Tuesday</th>
            <td><span id='tuePay' class="switch"><?php echo $set['Tuesday']['paycode'] ?></span></td>
            <td><span id='tueCkin' class="switch"><?php echo $set['Tuesday']['clock_in'] ?></span></td>
            <td><span id='tueCkOut' class="switch"><?php echo $set['Tuesday']['clock_out'] ?></span></td>
            <td><span id='tueSch' class="switch"><?php echo $set['Tuesday']['schedule'] ?></span></td>
            <td><span id='tueLoc' class="switch"><?php echo $set['Tuesday']['location'] ?></span></td>
        </tr>
        <tr id="r5">
            <th scope="row">Wednesday</th>
            <td><span id='wedPay' class="switch"><?php echo $set['Wednesday']['paycode'] ?></span></td>
            <td><span id='wedCkin' class="switch"><?php echo $set['Wednesday']['clock_in'] ?></span></td>
            <td><span id='wedCkOut' class="switch"><?php echo $set['Wednesday']['clock_out'] ?></span></td>
            <td><span id='wedSch' class="switch"><?php echo $set['Wednesday']['schedule'] ?></span></td>
            <td><span id='wedLoc' class="switch"><?php echo $set['Wednesday']['location'] ?></span></td>
        </tr>
        <tr id="r6">
            <th scope="row">Thursday</th>
            <td><span id='thurPay' class="switch"><?php echo $set['Thursday']['paycode'] ?></span></td>
            <td><span id='thurCkin' class="switch"><?php echo $set['Thursday']['clock_in'] ?></span></td>
            <td><span id='thurCkOut' class="switch"><?php echo $set['Thursday']['clock_out'] ?></span></td>
            <td><span id='thurSch' class="switch"><?php echo $set['Thursday']['schedule'] ?></span></td>
            <td><span id='thurLoc' class="switch"><?php echo $set['Thursday']['location'] ?></span></td>
        </tr>
        <tr id="r7">
            <th scope="row">Friday</th>
            <td><span id='friPay' class="switch"><?php echo $set['Friday']['paycode'] ?></span></td>
            <td><span id='friCkin' class="switch"><?php echo $set['Friday']['clock_in'] ?></span></td>
            <td><span id='friCkOut' class="switch"><?php echo $set['Friday']['clock_out'] ?></span></td>
            <td><span id='friSch' class="switch"><?php echo $set['Friday']['schedule'] ?></span></td>
            <td><span id='friLoc' class="switch"><?php echo $set['Friday']['location'] ?></span></td>
        </tr>
        <tr id="r8">
            <th scope="row">Saturday</th>
           <td><span id='satPay' class="switch"><?php echo $set['Saturday']['paycode'] ?></span></td>
            <td><span id='satCkin' class="switch"><?php echo $set['Saturday']['clock_in'] ?></span></td>
            <td><span id='satCkOut' class="switch"><?php echo $set['Saturday']['clock_out'] ?></span></td>
            <td><span id='satSch' class="switch"><?php echo $set['Saturday']['schedule'] ?></span></td>
            <td><span id='satLoc' class="switch"><?php echo $set['Saturday']['location'] ?></span></td>
        </tr>
    </table>
    <div>
        <button id="editTime">Edit</button>
        <button id="editSub">Submit</button>
    </div>
</div>
<!-------------------------------   ^   View Time Card  ^ ------------------------------------------>  

<!-----------------------  include jQuery  ----------------------------->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
   
<!-----------------------  Javascript  ----------------------------->
<script type = "text/javascript">
   var id1;
   var offset = 0;
   var sun, mon, tue, wed, thur, fri, sat;
   // After page is loaded, functions will be called
   window.onload = function () { 
        // Get all div elements with class="tablinks"
        tablinks = document.getElementsByClassName("tablinks");
        tablinks[0].className += " active"; // Highlight current tab 
        // when page load up
        document.getElementById("table").style.display = "block"; // display dash board. 
    }

    // Button event for side nav
   function openPage(evt, PageName) {
       // Declare all variables
        var i, tabcontent, tablinks;

        // Get all div elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabContent");
        for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
        }

        // Get all button elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(PageName).style.display = "block";
        evt.currentTarget.className += " active";
        }
        
        var iidd, ffnamee, llastnamee, uunnamee, eemaill;
        
        // View each employee informations when user interacts with the View/Edit Button
    function editt(id,fname,lname,uname,email) {
            document.getElementById("table").style.display = "none";
            document.getElementById("table").id = "tableBehind";
            document.getElementById("editem").style.display = "block";
            document.getElementById("editem").id="table"; 
            // Sends each data to the page after being fetched
            document.getElementById("iid").innerHTML = id;
            document.getElementById("ffname").innerHTML = fname;
            document.getElementById("llname").innerHTML = lname;
            document.getElementById("uuname").innerHTML = uname;
            document.getElementById("eemail").innerHTML = email;
            iidd = id;
            ffnamee = fname;
            llastnamee = lname;
            uunnamee = uname;
            eemaill = email;
    }
    function edittE() {
            document.getElementById("table").style.display = "none";
            document.getElementById("table").id = "editBehind";
            document.getElementById("editSettings").style.display = "block";
            document.getElementById("editSettings").id="table"; 
            // Sends each data to the page after being fetched
            document.getElementById("FName").value = ffnamee;
            document.getElementById("LName").value = llastnamee;
            document.getElementById("UName").value = uunnamee;
            document.getElementById("EMail").value = eemaill;
            
    }
     // Display account settings after editing employee
    function backsss(){
            document.getElementById("table").style.display = "none";
            document.getElementById("table").id = "editSettings";
            document.getElementById("editBehind").style.display = "block";
            document.getElementById("editBehind").id = "table"; 
    }
        // Display table after user finishing viewing or editing employee
    function backss(){
            document.getElementById("table").style.display = "none";
            document.getElementById("table").id = "editem";
            document.getElementById("tableBehind").style.display = "block";
            document.getElementById("tableBehind").id = "table"; 
    }
        
    function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }
        
       
    $(document).ready(function(){

        // Delete 
        $('.deletebb').click(function(){
        var el = this;
  
         // Delete id
         var deleteid = $(this).data('id');
 
        var confirmalert = confirm("Are you sure?");
        if (confirmalert == true) {
        // AJAX Request
            $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: { id:deleteid },
            success: function(response){

            if(response == 1){
	        // Remove row from HTML Table
	        $(el).closest('tr').css('background','tomato');
	        $(el).closest('tr').fadeOut(800,function(){
	        $(this).remove();
	     });
            }else{
	        alert('Failed.');
             }
            }
        });
        }
        });
    });
       

    //--------------------------- Change account settings ---------------------------
    $(document).ready(function(){

        $('#changeSettingsBtn').click(function(){
            
            ffnamee = document.getElementById("FName").value;
            llastnamee = document.getElementById("LName").value;
            uunnamee = document.getElementById("UName").value;
            eemaill = document.getElementById("EMail").value;
 
        var confirmalert = confirm("Are you sure?");
        if (confirmalert == true) {
        // AJAX Request
            $.ajax({
            url: 'editEmployee.php',
            type: 'POST',
            data: { id:iidd, firstName: ffnamee, lastName: llastnamee, userName: uunnamee, emailA: eemaill },
            success: function(response){

            if(response == 1){
	        location.reload();
            }else{
	        alert('Failed.');
             }
            }
        });
        }
        });
    });
   // View each employee timecard with timecard Button
    function timecard(id,fname,lname) {
        
        
    document.getElementById("editSub").style.display = "none";

        id1 = id;
            document.getElementById("table").style.display = "none";
            document.getElementById("table").id = "tableBehind";
            document.getElementById("edtimecard").style.display = "block";
            document.getElementById("edtimecard").id="table"; 
            
            document.getElementById("timecardowner").innerHTML = fname+" "+lname;
            displayWeek(); 
            
        var userId = id1;
                
        $.ajax({  
            type: 'GET',  
            url: 'displayTimecard.php',
            data:  {
                user_id: id1
            },
            dataType: "json",
            success:function(data){
    
        // loop through days
        for (var day in data) {

        switch(day) {
        
             case "Sunday": 
                document.getElementById("sunPay").innerHTML = data['Sunday']['paycode'];
                document.getElementById("sunCkin").innerHTML = data['Sunday']['clock_in'];
                document.getElementById("sunCkOut").innerHTML = data['Sunday']['clock_out'];
                document.getElementById("sunSch").innerHTML = data['Sunday']['schedule'];
                document.getElementById("sunLoc").innerHTML = data['Sunday']['location'];
                break;
             
             case "Monday":
                document.getElementById("monPay").innerHTML = data['Monday']['paycode'];
                document.getElementById("monCkin").innerHTML = data['Monday']['clock_in'];
                document.getElementById("monCkOut").innerHTML = data['Monday']['clock_out'];
                document.getElementById("monSch").innerHTML = data['Monday']['schedule'];
                document.getElementById("monLoc").innerHTML = data['Monday']['location'];
                break;
                
             case 'Tuesday':
                document.getElementById("tuePay").innerHTML = data['Tuesday']['paycode'];
                document.getElementById("tueCkin").innerHTML = data['Tuesday']['clock_in'];
                document.getElementById("tueCkOut").innerHTML = data['Tuesday']['clock_out'];
                document.getElementById("tueSch").innerHTML = data['Tuesday']['schedule'];
                document.getElementById("tueLoc").innerHTML = data['Tuesday']['location'];
                break;
                
            case 'Wednesday':
                document.getElementById("wedPay").innerHTML = data['Wednesday']['paycode'];
                document.getElementById("wedCkin").innerHTML = data['Wednesday']['clock_in'];
                document.getElementById("wedCkOut").innerHTML = data['Wednesday']['clock_out'];
                document.getElementById("wedSch").innerHTML = data['Wednesday']['schedule'];
                document.getElementById("wedLoc").innerHTML = data['Wednesday']['location'];
                break;
                
            case 'Thursday':
                document.getElementById("thurPay").innerHTML = data['Thursday']['paycode'];
                document.getElementById("thurCkin").innerHTML = data['Thursday']['clock_in'];
                document.getElementById("thurCkOut").innerHTML = data['Thursday']['clock_out'];
                document.getElementById("thurSch").innerHTML = data['Thursday']['schedule'];
                document.getElementById("thurLoc").innerHTML = data['Thursday']['location'];
                break;
                
            case 'Friday':
                document.getElementById("friPay").innerHTML = data['Friday']['paycode'];
                document.getElementById("friCkin").innerHTML = data['Friday']['clock_in'];
                document.getElementById("friCkOut").innerHTML = data['Friday']['clock_out'];
                document.getElementById("friSch").innerHTML = data['Friday']['schedule'];
                document.getElementById("friLoc").innerHTML = data['Friday']['location'];
                break;
                
            case 'Saturday':
                 document.getElementById("satPay").innerHTML = data['Saturday']['paycode'];
                document.getElementById("satCkin").innerHTML = data['Saturday']['clock_in'];
                document.getElementById("satCkOut").innerHTML = data['Saturday']['clock_out'];
                document.getElementById("satSch").innerHTML = data['Saturday']['schedule'];
                document.getElementById("satLoc").innerHTML = data['Saturday']['location'];
                break;
             default:
                 console.log("Error parsing json document")
            }
        }
            }
        });    
            
            
            
    }
    
/* 
    ***************************** Edit TimeCard **********************************
*/
// store all span values into an array
var spans = [];
paycodeCells = [0,5,10,15,20,25,30];
    
$('#editTime').click(function() {
    
    
    $('#editSub').toggle();
        
    
    // clear spans
    spans = [];
    
    var i = 0;
    
    // switch span elements to text field  
    $(".switch").each(function () {
        
        var text = $(this).text();
         
        if (paycodeCells.includes(i)){
            
            if(text == "REG"){
                var input = $('<select name="paycode" id="paycode"><option></option><option value="REG" selected="selected">REG</option><option value="OVT">OVT</option><option value="PTO">PTO</option></select>');
            }
            else if(text == "OVT"){
                var input = $('<select name="paycode" id="paycode"><option></option><option value="REG">REG</option><option value="OVT" selected="selected">OVT</option><option value="PTO">PTO</option></select>');
            }
            else if(text == "PTO"){
                var input = $('<select name="paycode" id="paycode"><option></option><option value="REG">REG</option><option value="OVT">OVT</option><option value="PTO" selected="selected">PTO</option></select>');
            }
            else{
                var input = $('<select name="paycode" id="paycode"><option value="" selected="selected"></option><option value="REG">REG</option><option value="OVT">OVT</option><option value="PTO">PTO</option></select>');
            }
        }
        else{
            var input = $('<input type="text" value="' + text + '" style="width: 100px"/>');
        }
        
        // append inpt tags inside span tags
        $(this).text('').append(input);
        
        // push all values to spans array
        spans.push(text);
        
        // increment index
        i++;
    });

});
    

$('#editSub').click(function() {
    
    $('#editSub').toggle();
        
    var userId = id1;       // user_id
    var orig = {};          // 2D array of original values where orig[weekday number][cell value]
    var _new = {};          // 2D array of new values where new[weekday number][cell value]
    var new_values = [];    // array of new span values
    var paycodes = [];
        
    // each row in the table before edit
    orig[0] = spans.slice(0,5);
    orig[1] = spans.slice(5,10);
    orig[2] = spans.slice(10,15);
    orig[3] = spans.slice(15,20);
    orig[4] = spans.slice(20,25);
    orig[5] = spans.slice(25,30);
    orig[6] = spans.slice(30,35);
    
    // get each paycode cell in the table after edit
        $(".switch select option:selected").each(function () {
            
        paycodes.push($(this).val());
            
    });
        
    var x = 0;
    var y = 0;
    
    // getting each cell in the table after edit 
    $(".switch").each(function () {
        
        if (paycodeCells.includes(x)){
            
            var val = paycodes[y];
            new_values.push(val);
            y++;
        }
        else{
            var v = $(this).find('input').val();
            new_values.push(v);
        }
        x++;
    
    });
        
    //each row in the table after edit
    _new[0] = new_values.slice(0,5); 
    _new[1] = new_values.slice(5,10); 
    _new[2] = new_values.slice(10,15);
    _new[3] = new_values.slice(15,20);
    _new[4] = new_values.slice(20,25);
    _new[5] = new_values.slice(25,30);
    _new[6] = new_values.slice(30,35);


    console.log(orig);
    console.log(_new);
    /*
        For each day, 
            if original values were empty and new values are not
                -> insert first new value and update the rest
            else
                -> update existing row
    */
    for(var i = 0; i < 7; i++){
        
        // Get the start date of currently displayed week
        var date = $("#weekDisplay").html();
        var start = date.substr(0, date.indexOf('-'));
        var cellDate = getCellDate(start, i);

        
        // INSERT
        if((isEmpty(orig[i])) && (!isEmpty(_new[i]))){
                
            var insertVal = firstVal(_new[i]);
            var insertIndex = _new[i].indexOf(insertVal);

            // insert initial value
            $.ajax({  
                type: 'POST',  
                url: 'insert.php',
                data:  {
                    user: userId, 
                    update : insertVal,
                    date: cellDate
                },
                success: function(response){
                    console.log("Inserted: " + response);
                    setTimeout(() => {  }, 1000);
                }
            });
                
            // update remaining values in row
            for(var j = insertIndex + 1; j < 5; j++){
                    
                if(_new[i][j].length > 1){
                    $.ajax({  
                        type: 'POST',  
                        url: 'update.php',
                        data:  {
                            user: userId, 
                            off : offset,
                            day : i,
                            update: _new[i][j],                       
                            cellNum: j,
                            date: cellDate
                        },
                        success: function(response){
                            console.log("Updated after insert: " + response);
                            setTimeout(() => {  }, 1000);
                        }
                    });
                }
            }
        }
        // UPDATE EXISTING
        else{
                
            for(var j = 0; j < 5; j++){
                    
                if (orig[i][j] != _new[i][j]){
                    $.ajax({  
                        type: 'POST',  
                        url: 'update.php',
                        data:  {
                            user: userId, 
                            off : offset,
                            day : i,
                            update: _new[i][j],                        
                            cellNum: j,
                            date: cellDate
                        },
                        success: function(response){
                            console.log("Updated Existing: " + response);
                            setTimeout(() => {  }, 1000);
                        }
                    });
                }
            }
        }
    }
    
    // Remove input tags within span items
    $('.switch').find('input').each(function() {
        $(this).replaceWith("<span>" + this.value + "</span>");
    });
    
     // Remove input tags within span items
    $('.switch').find('select').each(function() {
        var newVal = this.value;
        $(this).replaceWith("<span>" + newVal.toUpperCase() + "</span>");
    });
});

/*
    This function takes a 2D array as a parameter and
    returns true if all values are "" and false if not
*/
function isEmpty(array){
    for (var i = 0; i < array.length; i++){
        if (array[i].length > 0){
            return false;
        }
    }
    return true;
}

/*
    This function takes a 2D array as a parameter and
    returns the first value not set to ""
*/
function firstVal(array){
    for (var i = 0; i < array.length; i++){
        if (array[i].length > 0){
            return array[i];
        }
    }
}

/*
    This function takes a MM-D date as a parameter and
    an offset number to return a date as YYYY-MM-DD
    with offset from original parameter
*/
function getCellDate(inDate, index){
    
    var year = new Date().getFullYear();  
    var dateToFormat = year + "/" + inDate;
    dateToFormat = dateToFormat.replace("/", ",");
    dateToFormat = dateToFormat.replace("/", ",");
  
    var dateObject = new Date(dateToFormat);
    result = addDays(dateObject, index);
    return result;
}

/*
    This function takes a YYYY-MM-DD paramater
    and an adds paramater. It returns the date plus days
*/
function addDays(date, days) {

  date.setDate(date.getDate() + days);
  return date.toISOString().split('T')[0];
}

/* 
    ***************************** ^ Edit TimeCard ^ **********************************
*/  
        // Display table after user finishing viewing or editing employee
    function backToAdmin(){
        offset = 0;
            document.getElementById("table").style.display = "none";
            document.getElementById("table").id = "edtimecard";
            document.getElementById("tableBehind").style.display = "block";
            document.getElementById("tableBehind").id = "table"; 
            //Sunday
            document.getElementById("sunPay").innerHTML ="";
            document.getElementById("sunCkin").innerHTML = "";
            document.getElementById("sunCkOut").innerHTML = "";
            document.getElementById("sunSch").innerHTML = "";
            document.getElementById("sunLoc").innerHTML = "";
            //Monday
            document.getElementById("monPay").innerHTML = "";
            document.getElementById("monCkin").innerHTML = "";
            document.getElementById("monCkOut").innerHTML = "";
            document.getElementById("monSch").innerHTML = "";
            document.getElementById("monLoc").innerHTML = "";
            //Tuesday
            document.getElementById("tuePay").innerHTML = "";
            document.getElementById("tueCkin").innerHTML = "";
            document.getElementById("tueCkOut").innerHTML = "";
            document.getElementById("tueSch").innerHTML = "";
            document.getElementById("tueLoc").innerHTML = "";
            //Wednesday
            document.getElementById("wedPay").innerHTML = " ";
            document.getElementById("wedCkin").innerHTML = " ";
            document.getElementById("wedCkOut").innerHTML = " ";
            document.getElementById("wedSch").innerHTML = " ";
            document.getElementById("wedLoc").innerHTML = " ";
            //Thuesday
            document.getElementById("thurPay").innerHTML = " ";
            document.getElementById("thurCkin").innerHTML = " ";
            document.getElementById("thurCkOut").innerHTML = " ";
            document.getElementById("thurSch").innerHTML = " ";
            document.getElementById("thurLoc").innerHTML = " ";
            //Friday
            document.getElementById("friPay").innerHTML = " ";
            document.getElementById("friCkin").innerHTML = " ";
            document.getElementById("friCkOut").innerHTML = " ";
            document.getElementById("friSch").innerHTML = " ";
            document.getElementById("friLoc").innerHTML = " ";
            //Saturday
            document.getElementById("satPay").innerHTML = " ";
            document.getElementById("satCkin").innerHTML = " ";
            document.getElementById("satCkOut").innerHTML = " ";
            document.getElementById("satSch").innerHTML = " ";
            document.getElementById("satLoc").innerHTML = " ";
    }
    
    
   
/* 
    5.14 Database - populate timecard
    If right button event, grab next weeks timepunch data. If offset > 0, print
    no data because it is a future event.
*/
function nextWeek() {
    
    document.getElementById("editSub").style.display = "none";
    
    offset +=1;
    displayWeek();
    var userId = id1;
                
    if (offset < 1){
        id_numbers = new Array();
        $.ajax({  
            type: 'POST',  
            url: 'nextWeek.php',
            data:  {
                user: userId, 
                off : offset
            },
            dataType: "json",
            success:function(data){
    
        // loop through days
        for (var day in data) {

        switch(day) {
        
             case "Sunday": 
                document.getElementById("sunPay").innerHTML = data['Sunday']['paycode'];
                document.getElementById("sunCkin").innerHTML = data['Sunday']['clock_in'];
                document.getElementById("sunCkOut").innerHTML = data['Sunday']['clock_out'];
                document.getElementById("sunSch").innerHTML = data['Sunday']['schedule'];
                document.getElementById("sunLoc").innerHTML = data['Sunday']['location'];
                break;
             
             case "Monday":
                document.getElementById("monPay").innerHTML = data['Monday']['paycode'];
                document.getElementById("monCkin").innerHTML = data['Monday']['clock_in'];
                document.getElementById("monCkOut").innerHTML = data['Monday']['clock_out'];
                document.getElementById("monSch").innerHTML = data['Monday']['schedule'];
                document.getElementById("monLoc").innerHTML = data['Monday']['location'];
                break;
                
             case 'Tuesday':
                document.getElementById("tuePay").innerHTML = data['Tuesday']['paycode'];
                document.getElementById("tueCkin").innerHTML = data['Tuesday']['clock_in'];
                document.getElementById("tueCkOut").innerHTML = data['Tuesday']['clock_out'];
                document.getElementById("tueSch").innerHTML = data['Tuesday']['schedule'];
                document.getElementById("tueLoc").innerHTML = data['Tuesday']['location'];
                break;
                
            case 'Wednesday':
                document.getElementById("wedPay").innerHTML = data['Wednesday']['paycode'];
                document.getElementById("wedCkin").innerHTML = data['Wednesday']['clock_in'];
                document.getElementById("wedCkOut").innerHTML = data['Wednesday']['clock_out'];
                document.getElementById("wedSch").innerHTML = data['Wednesday']['schedule'];
                document.getElementById("wedLoc").innerHTML = data['Wednesday']['location'];
                break;
                
            case 'Thursday':
                document.getElementById("thurPay").innerHTML = data['Thursday']['paycode'];
                document.getElementById("thurCkin").innerHTML = data['Thursday']['clock_in'];
                document.getElementById("thurCkOut").innerHTML = data['Thursday']['clock_out'];
                document.getElementById("thurSch").innerHTML = data['Thursday']['schedule'];
                document.getElementById("thurLoc").innerHTML = data['Thursday']['location'];
                break;
                
            case 'Friday':
                document.getElementById("friPay").innerHTML = data['Friday']['paycode'];
                document.getElementById("friCkin").innerHTML = data['Friday']['clock_in'];
                document.getElementById("friCkOut").innerHTML = data['Friday']['clock_out'];
                document.getElementById("friSch").innerHTML = data['Friday']['schedule'];
                document.getElementById("friLoc").innerHTML = data['Friday']['location'];
                break;
                
            case 'Saturday':
                 document.getElementById("satPay").innerHTML = data['Saturday']['paycode'];
                document.getElementById("satCkin").innerHTML = data['Saturday']['clock_in'];
                document.getElementById("satCkOut").innerHTML = data['Saturday']['clock_out'];
                document.getElementById("satSch").innerHTML = data['Saturday']['schedule'];
                document.getElementById("satLoc").innerHTML = data['Saturday']['location'];
                break;
             default:
                 console.log("Error parsing json document")
            }
        }
            }
        });
    }
    else{
        document.getElementById("sunPay").innerHTML = " ";
        document.getElementById("sunCkin").innerHTML = " ";
        document.getElementById("sunCkOut").innerHTML = " ";
        document.getElementById("sunSch").innerHTML = " ";
        document.getElementById("sunLoc").innerHTML = " ";
        
        document.getElementById("monPay").innerHTML = " ";
        document.getElementById("monCkin").innerHTML = " ";
        document.getElementById("monCkOut").innerHTML = " ";
        document.getElementById("monSch").innerHTML = " ";
        document.getElementById("monLoc").innerHTML = " ";
        
        document.getElementById("tuePay").innerHTML = " ";
        document.getElementById("tueCkin").innerHTML = " ";
        document.getElementById("tueCkOut").innerHTML = " ";
        document.getElementById("tueSch").innerHTML = " ";
        document.getElementById("tueLoc").innerHTML = " ";
        
        document.getElementById("wedPay").innerHTML = " ";
        document.getElementById("wedCkin").innerHTML = " ";
        document.getElementById("wedCkOut").innerHTML = " ";
        document.getElementById("wedSch").innerHTML = " ";
        document.getElementById("wedLoc").innerHTML = " ";
        
        document.getElementById("thurPay").innerHTML = " ";
        document.getElementById("thurCkin").innerHTML = " ";
        document.getElementById("thurCkOut").innerHTML = " ";
        document.getElementById("thurSch").innerHTML = " ";
        document.getElementById("thurLoc").innerHTML = " ";
        
        document.getElementById("friPay").innerHTML = " ";
        document.getElementById("friCkin").innerHTML = " ";
        document.getElementById("friCkOut").innerHTML = " ";
        document.getElementById("friSch").innerHTML = " ";
        document.getElementById("friLoc").innerHTML = " ";
        
        document.getElementById("satPay").innerHTML = " ";
        document.getElementById("satCkin").innerHTML = " ";
        document.getElementById("satCkOut").innerHTML = " ";
        document.getElementById("satSch").innerHTML = " ";
        document.getElementById("satLoc").innerHTML = " ";
    }
}
    
/* 
    5.14 Database - populate timecard
    If left button event, grab last weeks timepunch data.
*/
function prevWeek() {
    
    document.getElementById("editSub").style.display = "none";
    
    
    offset -=1;
    displayWeek();
                
    var userId = id1;
                
    id_numbers = new Array();
    $.ajax({  
        type: 'POST',  
        url: 'lastWeek.php',
        data:  {
            user: userId, 
            off : offset
        },
        dataType: "json",
        success:function(data){
        
        // loop through days
        for (var day in data) {

        switch(day) {
        
             case "Sunday": 
                document.getElementById("sunPay").innerHTML = data['Sunday']['paycode'];
                document.getElementById("sunCkin").innerHTML = data['Sunday']['clock_in'];
                document.getElementById("sunCkOut").innerHTML = data['Sunday']['clock_out'];
                document.getElementById("sunSch").innerHTML = data['Sunday']['schedule'];
                document.getElementById("sunLoc").innerHTML = data['Sunday']['location'];
                break;
             
             case "Monday":
                document.getElementById("monPay").innerHTML = data['Monday']['paycode'];
                document.getElementById("monCkin").innerHTML = data['Monday']['clock_in'];
                document.getElementById("monCkOut").innerHTML = data['Monday']['clock_out'];
                document.getElementById("monSch").innerHTML = data['Monday']['schedule'];
                document.getElementById("monLoc").innerHTML = data['Monday']['location'];
                break;
                
             case 'Tuesday':
                document.getElementById("tuePay").innerHTML = data['Tuesday']['paycode'];
                document.getElementById("tueCkin").innerHTML = data['Tuesday']['clock_in'];
                document.getElementById("tueCkOut").innerHTML = data['Tuesday']['clock_out'];
                document.getElementById("tueSch").innerHTML = data['Tuesday']['schedule'];
                document.getElementById("tueLoc").innerHTML = data['Tuesday']['location'];
                break;
                
            case 'Wednesday':
                document.getElementById("wedPay").innerHTML = data['Wednesday']['paycode'];
                document.getElementById("wedCkin").innerHTML = data['Wednesday']['clock_in'];
                document.getElementById("wedCkOut").innerHTML = data['Wednesday']['clock_out'];
                document.getElementById("wedSch").innerHTML = data['Wednesday']['schedule'];
                document.getElementById("wedLoc").innerHTML = data['Wednesday']['location'];
                break;
                
            case 'Thursday':
                document.getElementById("thurPay").innerHTML = data['Thursday']['paycode'];
                document.getElementById("thurCkin").innerHTML = data['Thursday']['clock_in'];
                document.getElementById("thurCkOut").innerHTML = data['Thursday']['clock_out'];
                document.getElementById("thurSch").innerHTML = data['Thursday']['schedule'];
                document.getElementById("thurLoc").innerHTML = data['Thursday']['location'];
                break;
                
            case 'Friday':
                document.getElementById("friPay").innerHTML = data['Friday']['paycode'];
                document.getElementById("friCkin").innerHTML = data['Friday']['clock_in'];
                document.getElementById("friCkOut").innerHTML = data['Friday']['clock_out'];
                document.getElementById("friSch").innerHTML = data['Friday']['schedule'];
                document.getElementById("friLoc").innerHTML = data['Friday']['location'];
                break;
                
            case 'Saturday':
                 document.getElementById("satPay").innerHTML = data['Saturday']['paycode'];
                document.getElementById("satCkin").innerHTML = data['Saturday']['clock_in'];
                document.getElementById("satCkOut").innerHTML = data['Saturday']['clock_out'];
                document.getElementById("satSch").innerHTML = data['Saturday']['schedule'];
                document.getElementById("satLoc").innerHTML = data['Saturday']['location'];
                break;
             default:
                 console.log("Error parsing json document")
            }
        }
        }
    });
}

function displayWeek() {
    var curr = new Date;                    // get current date
    curr.setDate(curr.getDate() + offset*7) // update the date per offset (adding or substracting weeks)
    var currentDay = curr.getDay()          // index of the day of the week 0-6

    var firstday = new Date(curr);
    firstday = new Date(firstday.setDate(curr.getDate() - currentDay)); // get a date object of the first day (Sunday) of the selected week
    var lastday = new Date(curr);
    lastday = new Date(lastday.setDate(curr.getDate() - currentDay+6)); // get a date object of the last day (Saturday) of the selected week
    var monthStart = firstday.getMonth();   // the index of the month of the first day (0-11)
    var monthEnd = lastday.getMonth();      // the index of the month of the last day (0-11)
    var dayStart = firstday.getDate()       // the day of the month for the first day (1-31)
    var dayEnd = lastday.getDate()          // the day of the month for the last day (1-31)
    // update the div with the dates
    document.getElementById("weekDisplay").innerHTML = (monthStart+1) + '/' + dayStart + '-' + (monthEnd+1) + '/' + dayEnd;
                
    sun = curr.getFullYear().toString()+'-'+(monthStart+1).toString()+'-'+dayStart.toString();
    mon = curr.getFullYear().toString()+'-'+(monthStart+1).toString()+'-'+(dayStart+1).toString();
    tue = curr.getFullYear().toString()+'-'+(monthStart+1).toString()+'-'+(dayStart+2).toString();
    wed = curr.getFullYear().toString()+'-'+(monthStart+1).toString()+'-'+(dayStart+3).toString();
    thurs = curr.getFullYear().toString()+'-'+(monthStart+1).toString()+'-'+(dayStart+4).toString();
    fri = curr.getFullYear().toString()+'-'+(monthStart+1).toString()+'-'+(dayStart+5).toString();
    sat = curr.getFullYear().toString()+'-'+(monthStart+1).toString()+'-'+(dayEnd).toString();
}
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
   
   function hide(){
        $('#newPass').val("");
        $('#currentPass').val("");
        $('#confirmPass').val("");
        document.getElementById("passAlert").style.visibility = "hidden";
        document.getElementById("shortPass").style.visibility = "hidden";
        document.getElementById("changePassBox").style.visibility = "hidden";
   }
   function vlidatePass(){
       //storing values from each text field
       var newPassVal = document.getElementById("newPass").value;
       var confirmPassVal = document.getElementById("confirmPass").value;
       var currentPassVal = document.getElementById("currentPass").value;
       
       //new password and confirm password fields are filled
       if(newPassVal.length > 0 && confirmPassVal.length > 0){
           //new password entry matches the confirm password entry
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
</body>
</html>
