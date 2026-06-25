========================================================================
UACT EVENT TRACKER SYSTEM
System Setup & Deployment Overview
========================================================================

SYSTEM REQUIREMENT SPECIFICATION PRE-REQUISITES:
- Apache HTTP Web Server Server Instance Engine
- MariaDB / MySQL Server Relational Database Management Service (RDBMS)
- PHP Script Execution Runtime Environment Engine (PHP version >= 7.4)
- Local Server Deployment Suite (XAMPP / WAMP / MAMP stack instances)

MANUAL DEPLOYMENT & STEP-BY-STEP WORKFLOW INSTRUCTIONS:

STEP 1: ROOT ARCHIVE PROJECT UNPACKING DIRECTORY ROUTE
Extract the complete contents of the project source archive zip into your 
local target system hosting path:
- On Windows systems: C:\xampp\htdocs\uact-event-tracker\
- On macOS systems: /Applications/MAMP/htdocs/uact-event-tracker/

STEP 2: LOCAL INFRASTRUCTURE RELATIONAL DATA EXPORT IMPORT
- Start your local environment panel instances (Apache and MySQL).
- Launch your web browser interface and browse to: http://localhost/phpmyadmin/
- Select the "New" option inside the left sidebar tab menu column.
- Declare the database instance name container token exactly as: uact_event_tracker
- Define Collation tracking attributes as: utf8mb4_general_ci
- Click "Create" to initialize the database container.
- Navigate to the top options row area and choose the "Import" tab window pane.
- Click "Choose File" and select your exported schema file: UActEventTracker_database_sql.sql
- Leave default configurations intact and click "Import" / "Go" at the bottom.

STEP 3: SOURCE CONNECTION PARAMETER VERIFICATION
- Open the configuration link script located inside: db_connect.php
- Verify that your local environment parameter profiles match:
  * Server Name = localhost
  * Username = root
  * Password = [Your database password - default blank "" for fresh XAMPP installs]
  * Database Name = uact_event_tracker

STEP 4: LIVE WEB INTERFACE INTERACTION TESTING WORKFLOW
- Open your preferred desktop browser.
- Browse to the centralized index application entrance path address:
  http://localhost/uact-event-tracker/index.php
- The interactive system command hub dashboard should now be fully live, 
  rendering live aggregate database query computations.
========================================================================
