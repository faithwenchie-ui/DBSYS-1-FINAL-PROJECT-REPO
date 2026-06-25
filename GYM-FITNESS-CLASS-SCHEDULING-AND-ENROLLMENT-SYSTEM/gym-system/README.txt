GYM FITNESS CLASS SCHEDULING AND ENROLLMENT SYSTEM

================
SYSTEM NAME
================

Gym Fitness Class Scheduling and Enrollment System

==================
SYSTEM DESCRIPTION
==================

The Gym Fitness Class Scheduling and Enrollment System is a web-based application designed to help gym administrators manage fitness classes, member enrollments, attendance records, and reports from a single platform. It provides a convenient way to organize gym operations and reduce the need for manual record keeping.

The system supports two types of users: administrators and members. Administrators can manage members, classes, enrollments, attendance records, and reports, while members can create accounts, browse available classes, enroll in sessions, manage their enrollments, and update their profile information.

==================
SETUP INSTRUCTIONS
==================

1. Install XAMPP on your computer if it is not already installed.

2. Copy the project folder named "gym-system" into the XAMPP htdocs directory.

   Example:
   C:\xampp\htdocs\gym-system

3. Open the XAMPP Control Panel and start Apache and MySQL.

4. Open your browser and go to phpMyAdmin at:

   http://localhost/phpmyadmin

5. Create a new database for the project or import the SQL file located in the sql folder.

6. If needed, update the database connection settings in the database connection file so they match your local MySQL username, password, and database name.

7. After the database is ready, open the system in your browser at:

   http://localhost/gym-system

8. Log in using the administrator account or one of the seeded member accounts listed below.

   Note: For member login, use the full name shown in the list as the username.

=============================
DEFAULT LOGIN CREDENTIALS
=============================

Administrator Access
Username: admin
Password: admin12345

======================
SEEDED MEMBER ACCOUNTS
======================

Jinggoy Estrada — Password: jinggoyestrada12345
Loren Legarda — Password: lorenlegarda12345
Cynthia Villar — Password: cynthiavillar12345
Grace Poe — Password: gracepoe12345
Chiz Escudero — Password: chizescudero12345
Nancy Binay — Password: nancybinay12345
Imee Marcos — Password: imeemarcos12345
Bong Revilla — Password: bongrevilla12345
Francis Tolentino — Password: francistolentino12345
Mark Villar — Password: markvillar12345

========================
ADDITIONAL TEST ACCOUNTS
========================

The following accounts were added during testing and debugging. Their member IDs begin at 21 because earlier test records were deleted during development. This logic also applies to other tables with independent IDs.

Neil Peter — Password: neilpeter12345
testlangja — Password: testlangja12345

=====================
DEVELOPER INFORMATION
=====================

Developed by: Millondaga, Neil Peter T.