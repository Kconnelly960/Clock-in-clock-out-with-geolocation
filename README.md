# Clock-in-clock-out-with-geolocation

Authors: Kevin Connelly, Julie Bowes, Racheal, Bwahana, Chen

This website was created for the "Software Engineering" class. This was a group project consisting of six people. 
The goal of the project was to design a clock-in/clock-out system. 
There are three types of users, employee, manager, and admin.
A admin creates a business with a location giving in gps coordinates and a maximum distance in meters from that location.
A manager is a user of the business and has access to clock-in/clock-out functionality.
Both admin and manager can create new employees and managers as well as update information about existing users that fall under that managers/admins structure.
An employee has the ability to clock-in/clock-out and change their passwords.

If a user's device location is farther away from the location of the business than the maximum distance allowed, the user is not allowed to clock-in/clock-out. 
If the user is within the proper distance the name of users city is entered into the database as part of their time punch. This is done using reverse geolocation using GoogleMaps API. 
The users location is retrieved using the web geolocation API. 

This website can be accessed at praelab.com 
It is hosted on namecheap and utilizes cpanel and myphpadmin. 

Our database was created using MySQL. The majority of the languages used were PHP and Javascript.
