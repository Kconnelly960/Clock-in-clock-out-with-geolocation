<?php
include "config.php";

// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}
    // fetch data from the database
    $user = $_SESSION['user_id'];
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['firstN']; ?>'s Timecard</title>
    

    <!-----------------------  CSS style  ----------------------------->
    <link href="TimecardStyle.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-----------------------  HTML ----------------------------->
</head>
<body onload='displayWeek()'>
   <div id="dashboardHeader">
    <h1><?php echo $_SESSION['firstN']; ?>'s Time Card</h1> 
   </div>

   <div class="sidenav">
       <!-- Dashboard< -->
       <!-- 14.01 Distinguish user for sidebar options -->
       <?php 
            if($_SESSION["role"] == 'manager'){
                echo "<a class='dash' href='manager_dashboard.php'>My Dashboard</a>";
            }
            else{
                echo "<a class = 'dash' href='dashboard.php'>My Dashboard</a>";
            }
        ?>
        <a class='timecard' href="view_timecard.php">Timecard</a>
        <?php 
    
            if($_SESSION["role"] == 'manager'){
                echo "<a class='manage' href='manage_employees.php'>Manage Employees</a>";
            }
        ?>
        <a class='account' href="account_settings.php">Account Settings</a>
        <a href="logout.php">Logout</a>
    </div>

<div class="table">
    <table>
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
            <td><span id='sunPay'><?php echo $set['Sunday']['paycode'] ?></span></td>
            <td><span id='sunCkin'><?php echo $set['Sunday']['clock_in'] ?></span></td>
            <td><span id='sunCkOut'><?php echo $set['Sunday']['clock_out'] ?></span></td>
            <td><span id='sunSch'><?php echo $set['Sunday']['schedule'] ?></span></td>
            <td><span id='sunLoc'><?php echo $set['Sunday']['location'] ?></span></td>
        </tr>
        <tr id="r3">
            <th scope="row">Monday</th>
            <td><span id='monPay' title="Paycode"><?php echo $set['Monday']['paycode'] ?></span></td>
            <td><span id='monCkin'title="Paycode"><?php echo $set['Monday']['clock_in'] ?></span></td>
            <td><span id='monCkOut' title="Paycode"><?php echo $set['Monday']['clock_out'] ?></span></td>
            <td><span id='monSch'title="Paycode"><?php echo $set['Monday']['schedule'] ?></span></td>
            <td><span id='monLoc'title="Paycode"><?php echo $set['Monday']['location'] ?></span></td>
        </tr>
        <tr id="r4">
            <th scope="row">Tuesday</th>
            <td><span id='tuePay'title="Paycode"><?php echo $set['Tuesday']['paycode'] ?></span></td>
            <td><span id='tueCkin'title="Paycode"><?php echo $set['Tuesday']['clock_in'] ?></span></td>
            <td><span id='tueCkOut'title="Paycode"><?php echo $set['Tuesday']['clock_out'] ?></span></td>
            <td><span id='tueSch'title="Paycode"><?php echo $set['Tuesday']['schedule'] ?></span></td>
            <td><span id='tueLoc'title="Paycode"><?php echo $set['Tuesday']['location'] ?></span></td>
        </tr>
        <tr id="r5">
            <th scope="row">Wednesday</th>
            <td><span id='wedPay'title="Paycode"><?php echo $set['Wednesday']['paycode'] ?></span></td>
            <td><span id='wedCkin'title="Paycode"><?php echo $set['Wednesday']['clock_in'] ?></span></td>
            <td><span id='wedCkOut'title="Paycode"><?php echo $set['Wednesday']['clock_out'] ?></span></td>
            <td><span id='wedSch'title="Paycode"><?php echo $set['Wednesday']['schedule'] ?></span></td>
            <td><span id='wedLoc'title="Paycode"><?php echo $set['Wednesday']['location'] ?></span></td>
        </tr>
        <tr id="r6">
            <th scope="row">Thursday</th>
            <td><span id='thurPay'title="Paycode"><?php echo $set['Thursday']['paycode'] ?></span></td>
            <td><span id='thurCkin'title="Paycode"><?php echo $set['Thursday']['clock_in'] ?></span></td>
            <td><span id='thurCkOut'title="Paycode"><?php echo $set['Thursday']['clock_out'] ?></span></td>
            <td><span id='thurSch'title="Paycode"><?php echo $set['Thursday']['schedule'] ?></span></td>
            <td><span id='thurLoc'title="Paycode"><?php echo $set['Thursday']['location'] ?></span></td>
        </tr>
        <tr id="r7">
            <th scope="row">Friday</th>
            <td><span id='friPay'title="Paycode"><?php echo $set['Friday']['paycode'] ?></span></td>
            <td><span id='friCkin'title="Paycode"><?php echo $set['Friday']['clock_in'] ?></span></td>
            <td><span id='friCkOut'title="Paycode"><?php echo $set['Friday']['clock_out'] ?></span></td>
            <td><span id='friSch'title="Paycode"><?php echo $set['Friday']['schedule'] ?></span></td>
            <td><span id='friLoc'title="Paycode"><?php echo $set['Friday']['location'] ?></span></td>
        </tr>
        <tr id="r8">
            <th scope="row">Saturday</th>
           <td><span id='satPay'title="Paycode"><?php echo $set['Saturday']['paycode'] ?></span></td>
            <td><span id='satCkin'title="Paycode"><?php echo $set['Saturday']['clock_in'] ?></span></td>
            <td><span id='satCkOut'title="Paycode"><?php echo $set['Saturday']['clock_out'] ?></span></td>
            <td><span id='satSch'title="Paycode"><?php echo $set['Saturday']['schedule'] ?></span></td>
            <td><span id='satLoc'title="Paycode"><?php echo $set['Saturday']['location'] ?></span></td>
        </tr>
    </table>
</div>

<span title="Regular">REG</span>
<div>Hover over <span title="Regular">paycode</span></div>
   
   <!-----------------------  Javascript  ----------------------------->
  <script type = "text/javascript">
  const paycode_id = ['sunPay', 'monPay', 'tuePay', 'wedPay', 'thurPay', 'friPay', 'satPay'];
  setHover();
   var offset = 0;
   var sun, mon, tue, wed, thur, fri, sat;
   
/* 
    5.14 Database - populate timecard
    If right button event, grab next weeks timepunch data. If offset > 0, print
    no data because it is a future event.
*/
function nextWeek() {
    offset +=1;
    displayWeek();
    var userId = <?php echo $_SESSION['user_id'] ?>;
                
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
        setTimeout(() => { setHover(); }, 1000);
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
    offset -=1;
    displayWeek();
                
    var userId = <?php echo $_SESSION['user_id'] ?>;
                
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
   setTimeout(() => { setHover(); }, 1000);
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
    
function setHover(){
    
    for (var i = 0; i < paycode_id.length; i++){
        setTitle(paycode_id[i]);
    }
    
}

function setTitle(id){
    
    
    var val = document.getElementById(id);
    
    if(val.innerHTML == 'PTO') {
        document.getElementById(id).title = 'Paid Time Off';
    }
    else if(val.innerHTML  == 'OVT') {
        document.getElementById(id).title = 'Overtime';
    }
    else if(val.innerHTML  == 'REG'){
        document.getElementById(id).title = 'Regular';
    }
    else{
        document.getElementById(id).title = 'Paycode';
    }
}

   

    </script>
</body>
</html>
