# 1. Condition
To create a web application that allows registration of graduates and recruiters. The purpose of the app is to bring FMI graduate students together by allowing them to add events that are visible to peers in their class and major.
The other role the app supports is recruiters who have the option to view all events as well as add events. In addition, recruiters have the functionality to add job postings that are visible to all members of the FMI Alumni Club.

# 2. Introduction
## 2.1. Roles
- unregistered user
- graduate student
- recruiter
- administrator
  
## 2.2. Functional requirements

### Unregistered user:
Registration - enter name, surname, email, password and phone number. Depending on the role with which the user wants to register, two options are provided, if he is a recruiter, enter a company name, if he is a graduate student, enter his faculty number, major, graduation, location and employment status.

### Recruiter:
Login to the system - email and password are entered
Home page - view accepted events, view all events added by other users. The user has the option to accept an event, when accepted it is moved to the accepted events column.
Add an event - enter a name, description, date and time and a photo. The event is visible to all users of the system.
View all added ads
Add an ad - enter a name and description. The date added is generated automatically.
View profile - possibility to edit name, surname, phone number, password, photo and company name
Exit the system

### Graduate student:
Login to the system - email and password are entered
Home page - view of accepted events, view of all events that have been added by users in his specialty or graduate school, as well as by recruiters. The user has the option to accept an event, when accepted it is moved to the accepted events column.
Add an event - enter a name, description, date and time and a photo. The event is visible to all users who are in the same major or class or are recruiters.
View all added ads
View profile - possibility to edit name, surname, phone number, password, photo, faculty number, major, graduate, location and status
Exit the system


### Administrator:
Login to the system
Import user information from a CSV file
Export information about registered users to a CSV file


## 2.3. Non-functional requirements

- The system to guarantee security of users' data.
- The system must be compatible with all major web browsers (Chrome, Firefox, Safari, Edge).
- The system uses a modular architecture for easy maintenance and updating.
- The system must be able to restore normal operation within 15 minutes of any failure or crash.
- The system has an intuitive and easy-to-navigate interface, with access to each functionality in up to 4 clicks from the home page with login and registration options and up to 2 clicks after logging in to the system.
- The system should allow the page to load in no more than 2 seconds under standard network traffic conditions.
- The system must have an uptime of at least 99.9%

## 2.4. Benefits of project implementation
From the point of view of users who are graduate students, the biggest benefit is that the existence of the system keeps them connected with colleagues after graduation. By adding and attending different events, they can continue to exchange experiences and ideas. On the other hand, through the ads added by recruiters, they can find out what the demand is in the market or find a job if they don't already have one.
For recruiters, the benefits are that they have the opportunity to present the companies they work for and the available positions in them to a group of people who have interests in the specific field. Thus, the search for quality personnel becomes more efficient.

# 3. Theory – analysis and design of the solution 
## 3.1. Database 
- table 'Users' - contains the key information for each user, extended by the tables 'Graduate' and 'Recruiter', which contain information about the specific user roles. The connection in the database is made by Foreign key from user id to the specific role id, and in the code it is represented by inheritance.
- A 'Major' table containing the name of a user's major. The entries in the table have been added in advance to limit the majors to those in FMI.
- Table 'AdInfo' - contains information about ads added by a recruiter
- Table 'EventInfo' - contains event information
- Table 'UserToEvent' - a cross table that contains the id of a user and an event, as well as boolean variables that keep whether the event was created by the specific user or received by him

![alt text](https://github.com/vils03/AlumniClub/blob/main/database/er_diagram.png)

## 3.2. Application architecture
The developed web application uses the MVC(Model View Controller) software architecture model.
Model - responsible for data logic and interaction with the database. Models have been developed for the main data objects - user, event, advertisement.
View & Controller - implemented through various APIs that accept information and convert it into commands for the model and vice versa - send information from the user after a request from him.

# 4. Technologies used
The technologies studied in the course were used for the development of the application - HTML 5, CSS 3, JavaScript and PHP. To create a local environment that includes all the necessary tools for the development of a web application, we have used the software package XAMPP (v.3.3.0), which contains an Apache server and a MariaDB database.


# 5. Installation, Setup and DevOps 

## 5.1. Prerequisites
installed XAMPP package to work with Apache HTTP Server and MySQL.
## 5.2. Steps to prepare the application
- the project folder must be located in the htdocs subdirectory of the xampp directory
- we start xampp - Apache and MySQL
- From xampp we navigate to the Admin panel of phpmyadmin
in phpmyadmin we run the script 'alumni_db.sql' - this script adds the necessary tables and constraints to our database
through a browser we access localhost and navigate to the directory with the project
- We open the welcome.html page, where we need to register as an administrator with the email 'admin@admin.com'
- After registration and login as an administrator, we use the option "Import students from a csv file" by attaching the file 'import.csv'
- Now that we've created the database and added the users to it, we go back to phpmyadmin and run the 'add_info.sql' script which brings in information about the events and announcements created.
- The purpose of this multi-step data preparation is to import users through the developed APIs so that their password can be hashed. Every user that is imported from a csv file is assigned a default password (12345678), which can be changed later.

## 5.3. 
SQL_SERVER - “localhost”
SQL_PORT - “3306”
SQL_USERNAME - “root”
SQL_PASSWORD - “”
DB_NAME - “alumniclub”

# 7. Sample data 
To test the system, after starting the project you can log in with profiles of different roles and use the different functionalities such as adding an event, announcement, changing data and see how they are displayed to other users according to their role, specialty or graduation.

