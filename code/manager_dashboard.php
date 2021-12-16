<?php
include "config.php";

// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}
// Queries the currently logged in user's last time punch
$user = $_SESSION['user_id'];
    
//2.07-2.08 missed clock out changes button back and inserts "-" in database
//$hour24 = 86400;

$time1 = "SELECT date, clock_in, clock_out, timestamp_id FROM praexroh_test.Timestamp where user_id ='".$user."'
                    ORDER BY timestamp_id DESC LIMIT 1";
$result4 = mysqli_query($con,$time1);
$clockinTime = mysqli_fetch_array($result4);
    
$ci_time = $clockinTime['clock_in'];
$ci_date = $clockinTime['date'];
$co_time = $clockinTime['clock_out'];
$id = $clockinTime['timestamp_id'];

if (is_null($co_time)){

        
    $date = strtotime($ci_date + " " + $ci_time);
    // if it's been past 24 hours ( 86400 seconds ) - for testing purposes use shorter time
    if($date > time() + 86400) {
        $time_sql = "UPDATE praexroh_test.Timestamp SET clock_out = 'MISSED PUNCH' WHERE timestamp_id = '".$id."'";
        $stmt = mysqli_prepare($con, $time_sql);
        mysqli_stmt_execute($stmt); 
        $missedPunch = true;
        //change button back to green clock in
        echo '<script type="text/javascript">',
                'togglecol();',
                '</script>'
                ;
        
    }
}
    
$sql = "SELECT * FROM Timestamp WHERE user_id = '".$user."' ORDER BY timestamp_id DESC LIMIT 1;";

$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
        
$in  = $row['clock_in'];
$out  = $row['clock_out'];

//get business id
$sql2 = "SELECT business_id FROM User where user_id='".$user."'";
$result = mysqli_query($con,$sql2);
$row2 = mysqli_fetch_array($result);
$buss_id = $row2['business_id'];

//get business location
$sql3 = "SELECT * FROM Business where business_id='".$buss_id."'";
$result = mysqli_query($con,$sql3);
$row3 = mysqli_fetch_array($result);
$loc = $row3['location'];
$maxD = $row3['max_distance'];
$site_name = $row3['location_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-----------------------  CSS style  ----------------------------->
    <link href="ManagerStyle.css" rel="stylesheet">

    <!-----------------------  HTML ----------------------------->
    <!-- link jquery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="dashboardHeader">
       <h1><?php echo $_SESSION['firstN']; ?>'s Dashboard</h1> 
    </div>
    <div class='sidenav'>
        <!---- <a>Dashboard</a> -->
        <a class="dash" href='manager_dashboard.php'>My Dashboard</a>
        <a class="timecard" href='view_timecard.php'>Timecard</a>
        <a class="manage" href='manage_employees.php'>Manage Employees</a>
        <a class="account" href='account_settings.php'>Account Settings</a>
        <a href='logout.php'>Logout</a>
    </div>
    <div id="clockin">
        <div id="clock">
            <p id = "time"></p>
            <p id = "date"></p>
            </div>
            <div id = punch-btn>
            <?php
            if(is_null($in) || !is_null($out)){
                echo "<button id ='btn' name='Btn' style='background-color: #79E052'>CLOCK-IN</button>";}
            else{
                echo "<button id ='btn' name='Btn' style='background-color: #F23232'>CLOCK-OUT</button>";}
            ?>
        </div>
   </div>
   <!-----------------------  Javascript  ----------------------------->
   <script type = "text/javascript">
   window.onload = setInterval(clock,1000);
   
   function clock()
   {
       var date = new Date();
       var date1 = date.getDate();
       var month = date.getMonth();
       // Months of the year
       var months = ["Jan","Feb","Mar","April","May","June","July","Aug","Sept","Oct","Nov","Dec" ]
       month = months[month];
       var year = date.getFullYear();
       var day = date.getDay();
       // Days of the week
       var days = ["Sun", "Mon","Tue","Wed","Thur","Fri","Sat"];
       day = days[day];
       var hour = date.getHours(); //0 to 23 hours
       var min = date.getMinutes();
       if(min < 10){
           min = "0"+min;
       }
       // Am or PM modifications
       var ampm = hour >= 12 ? "PM":"AM";
       if(hour==0){
           hour = 12;
       }
       if(hour > 12){
           hour -= 12;
       }
       document.getElementById("date").innerHTML = day+" "+date1+", "+month+" "+year;
       document.getElementById("time").innerHTML = hour+":"+min+" "+ampm;
   }
   button = document.getElementById('btn');
   function toggleCol(){
       if (document.getElementById("btn").innerHTML == "CLOCK-IN")
       {
           button.style.backgroundColor = "#F23232";
           button.innerHTML = "CLOCK-OUT";
       }
       else{
           button.style.backgroundColor = "#79E052";
           button.innerHTML = "CLOCK-IN";
       }
   }
   /* 
    5.12 - This function is triggered once btn is clicked. It takes the time of the punch and passes it to the 
    timepunch.php file to store into the database. Upon success, the button displays correctly. 
   */
   $("#btn").click(function(){
       //If geolocation capabilities exists for the device, get its location
       if(navigator.geolocation){
           navigator.geolocation.getCurrentPosition(position => {
               //get latitude and longitude
               var lat = position.coords.latitude;
               var long = position.coords.longitude;
               //set maximum distance and business latitude and longitude
               var maxDistance = <?php echo $maxD?>;
               var bussLoc = [];
               bussLoc.push(<?php echo $loc?>);
               var bussLat = bussLoc[0];
               var bussLong = bussLoc[1];
               
               //Math to determine distance in meters between user location and business location
               const R = 6371e3; // metres
               const φ1 = lat * Math.PI/180; // φ, λ in radians
               const φ2 = long * Math.PI/180;
               const Δφ = (bussLat-lat) * Math.PI/180;
               const Δλ = (bussLong-long) * Math.PI/180;
               const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
               const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
               const d = R * c; // in metres between user and business
               
               var cityName;
               var loc_name = "<?php echo $site_name?>";
               
               //Check if users distance from business location is less than the specified maximum distance
               if(d <= maxDistance){
                   toggleCol();
                   let url = 'https://maps.googleapis.com/maps/api/geocode/json?key=KEY=' + lat + ',' + long;
                   //Get information about users location using reverse geocoding
                   $.getJSON(url, function(data) {
                       let parts = data.results[0].address_components;
                       parts.forEach ( part => {
                           //If the part name is "locality" set that to the cityName
                           if(part.types[0] == "locality"){
                               cityName = part.long_name;
                               //check if there is a pre-defined name for business location, if so use that. Else use city name.
                               if(loc_name != 0){
                                   cityName = loc_name;
                               };
                               
                               var curr_date = new Date();
                               var yyyy = curr_date.getFullYear().toString();
                               var mm = (curr_date.getMonth()+1).toString();
                               var dd  = curr_date.getDate().toString();
                               var mmChars = mm.split('');
                               var ddChars = dd.split('');
                               var format = yyyy + '-' + (mmChars[1]?mm:"0"+mmChars[0]) + '-' + (ddChars[1]?dd:"0"+ddChars[0]);
                               var time = (curr_date.getHours() + ":" + curr_date.getMinutes() + ":" + curr_date.getSeconds());
                               var user = <?php echo $_SESSION['user_id'] ?>;
                               
                               //insert data into database
                               $.ajax({
                                   type: 'POST',
                                   url: 'timepunch.php',
                                   dataType: 'json',
                                   data: {
                                       user_id: user,
                                       punchdate: format,
                                       punchtime: time,
                                       location: cityName
                                   }
                               });
                           }
                       })
                   });
                   //If the users distance from the business is greater than the set max distance
                   //do not let the user clock-in or clock-out, display alert.
                   } else {
                       alert("Not in proper location");
                   }
           })
       }
   });
   
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
</body>
</html>
