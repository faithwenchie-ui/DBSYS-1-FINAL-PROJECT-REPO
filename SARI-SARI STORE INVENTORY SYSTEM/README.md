===========================================
   SARI-SARI STORE INVENTORY SYSTEM
   Database Management Systems Project
   
   Group Name: JERD KYUTIES
   Scenario #04: Sari-Sari Store Inventory System
   
   Submitted to: [Kenette Roeven C. Saylo]
   Date: June 2026
===========================================

===========================================
TABLE OF CONTENTS
===========================================

1. System Overview
2. System Requirements
3. Installation Guide
4. Login Credentials
5. System Navigation
6. How to Use the System
7. Database Structure
8. SQL Features Showcase
9. File Structure
10. Troubleshooting
11. Group Members
12. References

===========================================
1. SYSTEM OVERVIEW
===========================================

The Sari-Sari Store Inventory System is a web-based database 
application designed to help small retail store owners manage 
their inventory efficiently.

Key Features:
- Product Management (Add, Edit, Delete, Search)
- Store Management (Multiple store locations)
- Supplier Management
- Inventory Tracking with Real-time Stock Levels
- Restock System with Automated Stored Procedure
- Stock Adjustment for Damaged/Expired/Returned Items
- Low Stock Report Generation
- User Authentication (Admin and Store Owner roles)
- Role-Based Access Control

Purpose:
To digitize manual inventory tracking, prevent stockouts, 
reduce waste from expired products, and provide centralized 
management for store owners with multiple branches.

===========================================
2. SYSTEM REQUIREMENTS
===========================================

Hardware:
- Minimum 4GB RAM
- 500MB free disk space
- Processor: Intel Core i3 or equivalent

Software:
- Windows 10/11 / Mac OS / Linux
- XAMPP (Apache + MySQL + PHP)
- Web browser (Chrome, Firefox, Edge)

===========================================
3. INSTALLATION GUIDE
===========================================

STEP 1: Install XAMPP
---------------------
1. Download XAMPP from: https://www.apachefriends.org/
2. Install XAMPP on your computer
3. Default installation path:
   - Windows: C:\xampp
   - Mac: /Applications/XAMPP
   - Linux: /opt/lampp

STEP 2: Copy Project Files
---------------------------
1. Copy the entire "sari_sari_store" folder to:
   - Windows: C:\xampp\htdocs\
   - Mac: /Applications/XAMPP/htdocs/
   - Linux: /opt/lampp/htdocs/

STEP 3: Start XAMPP Services
----------------------------
1. Open XAMPP Control Panel
2. Start Apache (Click "Start" button)
3. Start MySQL (Click "Start" button)
4. Verify both services show green "Running" status

STEP 4: Import Database
------------------------
1. Open web browser and go to: http://localhost/phpmyadmin or http://localhost/sari_sari_store/code/index.php
2. Click "New" on the left sidebar
3. Database name: sari-sari_store_inventory_db
4. Click "Create"
5. Click "Import" tab
6. Click "Choose File" and select: [JERD KYUTIES]_database2.sql
7. Click "Go" at the bottom
8. Wait for import to complete

STEP 5: Run the System
----------------------
1. Open web browser
2. Go to: http://localhost/sari_sari_store/
3. The system homepage will load
4. You can now login or register a new store

===========================================
4. LOGIN CREDENTIALS
===========================================

ADMIN ACCOUNT (Full System Access):
------------------------------------
Email:    admin@system.com
Password: password

STORE OWNER ACCOUNT:
--------------------
To create a store owner account:
1. Click "Register Store" on the homepage
2. Fill out the registration form:
   - Full Name: [Your Name]
   - Email: [Your Email]
   - Password: [Choose a password]
   - Store Name: [Your Store Name]
   - Store Location: [Your Store Address]
   - Store Phone: [Your Phone Number]
3. Click "Register Store"
4. Login with your registered email and password

Note: Passwords are hashed using bcrypt for security.

===========================================
5. SYSTEM NAVIGATION
===========================================

FOR ADMIN USERS (admin@system.com):
-----------------------------------------------------------
| Menu Item        | Description                           |
|------------------|---------------------------------------|
| Dashboard        | Overview of all stores and statistics |
| Products         | Add, edit, delete, search products    |
| Inventory        | View stock levels across all stores   |
| Suppliers        | Manage supplier information           |
| Low Stock Report | View products needing reordering      |
| Register Store   | Create new store owner accounts       |
| Logout           | Exit the system                       |

FOR STORE OWNERS (Registered Users):
-----------------------------------------------------------
| Menu Item        | Description                           |
|------------------|---------------------------------------|
| Dashboard        | Overview of your store only           |
| My Products      | View products in your store           |
| My Inventory     | Check current stock levels            |
| Restock          | Add new stock to your store           |
| Adjust Stock     | Record sales, damage, returns         |
| Low Stock Report | View products needing reorder         |
| Logout           | Exit the system                       |

===========================================
6. HOW TO USE THE SYSTEM
===========================================

6.1 ADDING A NEW PRODUCT (Admin only)
--------------------------------------
Products → Add New Product → Fill form → Save

6.2 RESTOCKING INVENTORY
-------------------------
Inventory/My Inventory → Restock → Select store → Select product 
→ Enter quantity → Process Restock

6.3 RECORDING A SALE (Decrease Stock)
--------------------------------------
Adjust Stock → Select store → Select product 
→ Adjustment Type: "Correction" 
→ Quantity Change: Negative number (-1, -2, etc.)
→ Reason: "Customer purchase" → Apply

6.4 RECORDING DAMAGED ITEMS (Decrease Stock)
---------------------------------------------
Adjust Stock → Select store → Select product
→ Adjustment Type: "Damaged" or "Expired"
→ Quantity Change: Negative number
→ Reason: Describe damage → Apply

6.5 RECORDING CUSTOMER RETURNS (Increase Stock)
------------------------------------------------
Adjust Stock → Select store → Select product
→ Adjustment Type: "Return"
→ Quantity Change: Positive number (+1, +2, etc.)
→ Reason: "Customer return" → Apply

6.6 SEARCHING FOR PRODUCTS
---------------------------
Products → Type product name in search bar → Search

6.7 VIEWING LOW STOCK REPORT
-----------------------------
Low Stock Report → Shows all products below reorder level

6.8 REGISTERING A NEW STORE
----------------------------
Click "Register Store" → Fill form → Submit → Login

===========================================
7. DATABASE STRUCTURE
===========================================

7.1 TABLES
-----------
| Table Name              | Description                           |
|-------------------------|---------------------------------------|
| supplier                | Product supplier information          |
| store                   | Store location and contact details    |
| product                 | Product catalog with prices           |
| inventory               | Stock levels (M:N junction table)     |
| restock_log             | Restock transaction history           |
| stock_adjustment_log    | Stock adjustment records              |
| users                   | User accounts (admin & store owners)  |

7.2 RELATIONSHIPS
-----------------
- Supplier 1────< Product (One supplier supplies many products)
- Store 1────< Inventory (One store has many inventory records)
- Product 1────< Inventory (One product appears in many inventory records)
- Store 1────< Restock Log (One store has many restock logs)
- Product 1────< Restock Log (One product appears in many restock logs)
- Store 1────< Stock Adjustment Log (One store has many adjustments)
- Product 1────< Stock Adjustment Log (One product has many adjustments)

7.3 M:N RELATIONSHIP
--------------------
Store (M) ────< INVENTORY >──── (M) Product
The inventory table serves as a junction table to resolve the
many-to-many relationship between Store and Product.

7.4 STORED PROCEDURE
--------------------
Name: RestockProduct()
Purpose: Automates restocking by inserting into restock_log
         and updating inventory quantity in one transaction
Parameters: p_store_id, p_product_id, p_restock_quantity, 
            p_restocked_by, p_unit_cost

7.5 VIEW
--------
Name: low_stock_report
Purpose: Shows products where quantity_on_hand <= reorder_level
         with calculated units_to_order
Used in: reports/low_stock.php

===========================================
8. SQL FEATURES SHOWCASE
===========================================

8.1 INNER JOIN (Used in inventory/index.php)
----------------------------------------------
SELECT i.*, s.store_name, p.product_name, p.unit_price
FROM inventory i
JOIN store s ON i.store_id = s.store_id
JOIN product p ON i.product_id = p.product_id

8.2 LEFT JOIN (Used in products/index.php)
-------------------------------------------
SELECT p.*, s.supplier_name 
FROM product p
LEFT JOIN supplier s ON p.supplier_id = s.supplier_id

8.3 AGGREGATE with GROUP BY (Used in Dashboard)
------------------------------------------------
SELECT p.product_name, 
       SUM(i.quantity_on_hand) as total_quantity,
       SUM(i.quantity_on_hand * p.unit_price) as total_value
FROM inventory i
JOIN product p ON i.product_id = p.product_id
GROUP BY p.product_id
ORDER BY total_value DESC LIMIT 5

8.4 STORED PROCEDURE CALL (Used in restock.php)
------------------------------------------------
CALL RestockProduct(?, ?, ?, ?, ?)

8.5 VIEW (Used in reports/low_stock.php)
-----------------------------------------
SELECT * FROM low_stock_report

8.6 SUBQUERY (Used in store_restock.php)
-----------------------------------------
SELECT * FROM product 
WHERE product_id NOT IN (
    SELECT product_id FROM inventory WHERE store_id = ?
)

===========================================
9. FILE STRUCTURE
===========================================

sari_sari_store/
│
├── index.php                 # Homepage
├── db_connect.php            # Database connection
├── style.css                 # Styling
├── login.php                 # Login page
├── register.php              # Store registration
├── logout.php                # Logout handler
├── dashboard.php             # Admin dashboard
├── store_owner_dashboard.php # Store owner dashboard
├── store_products.php        # Store owner products
├── store_inventory.php       # Store owner inventory
├── store_restock.php         # Store owner restock
├── store_adjust.php          # Store owner adjustment
├── README.txt                # This file
│
├── products/                 # Admin product management
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   └── delete.php
│
├── inventory/                # Admin inventory management
│   ├── index.php
│   ├── restock.php
│   └── adjust.php
│
├── suppliers/                # Supplier management
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   └── delete.php
│
└── reports/                  # Reports
    └── low_stock.php

===========================================
10. TROUBLESHOOTING
===========================================

PROBLEM: "Connection failed" error
SOLUTION: Make sure MySQL is running in XAMPP Control Panel

PROBLEM: "404 Not Found" error
SOLUTION: Make sure files are in C:\xampp\htdocs\sari_sari_store\

PROBLEM: "Access denied for user" error
SOLUTION: Check db_connect.php has username = "root", password = ""

PROBLEM: Blank white page
SOLUTION: Enable error reporting or check PHP error logs

PROBLEM: Cannot login
SOLUTION: Use admin@system.com / password or register a new account

PROBLEM: "Unknown database" error
SOLUTION: Database name must be exactly: sari-sari_store_inventory_db

PROBLEM: Session lost when navigating
SOLUTION: Clear browser cache and cookies, then login again

PROBLEM: Port 80 already in use
SOLUTION: Change Apache port in XAMPP or use localhost:8080

===========================================
11. GROUP MEMBERS & CONTRIBUTIONS
===========================================

1. [Reazzel Lou Fabila] - Developed the backend PHP scripts, frontend HTML/CSS/JS
   interface, database connection, and system integration.

2. [Darleen Joy Esteves] - Designed the initial database structure, created
   tables and relationships, and performed system testing.

3. [Jann Mhareoul Abancio] - Conducted quality assurance testing, verified
   all features work correctly, and reported bugs for resolution.

4. [Elizabeth Satumbaga] - Prepared system documentation and assisted with presentation materials.

===========================================
12. REFERENCES
===========================================

- XAMPP: https://www.apachefriends.org/
- PHP Documentation: https://www.php.net/docs.php
- MySQL Documentation: https://dev.mysql.com/doc/
- W3Schools PHP Tutorial: https://www.w3schools.com/php/
- PHP Password Hashing: https://www.php.net/manual/en/function.password-hash.php

===========================================
END OF README
===========================================

For questions or support, contact the group leader.

Date Created: June 2026
Last Updated: June 2026