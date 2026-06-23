-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jun 22, 2026 at 06:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sari-sari_store_inventory_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `RestockProduct` (IN `p_store_id` INT, IN `p_product_id` INT, IN `p_restock_quantity` INT, IN `p_restocked_by` VARCHAR(100), IN `p_unit_cost` DECIMAL(10,2))   BEGIN
    INSERT INTO restock_log (store_id, product_id, restock_quantity, 
                             restocked_by, unit_cost_at_restock)
    VALUES (p_store_id, p_product_id, p_restock_quantity, 
            p_restocked_by, p_unit_cost);
    
    UPDATE inventory 
    SET quantity_on_hand = quantity_on_hand + p_restock_quantity,
        last_restocked_date = CURDATE()
    WHERE store_id = p_store_id AND product_id = p_product_id;
    
    IF ROW_COUNT() = 0 THEN
        INSERT INTO inventory (store_id, product_id, quantity_on_hand, reorder_level)
        VALUES (p_store_id, p_product_id, p_restock_quantity, 10);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `store_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_on_hand` int(11) NOT NULL DEFAULT 0,
  `reorder_level` int(11) NOT NULL,
  `max_stock_level` int(11) DEFAULT NULL,
  `last_restocked_date` date DEFAULT NULL,
  `location_in_store` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`store_id`, `product_id`, `quantity_on_hand`, `reorder_level`, `max_stock_level`, `last_restocked_date`, `location_in_store`) VALUES
(1, 1, 85, 10, 50, '2026-06-03', 'Shelf A1'),
(1, 2, 40, 15, 60, NULL, 'Shelf B2'),
(1, 3, 12, 8, 30, NULL, 'Shelf C3'),
(1, 4, 50, 20, 100, NULL, 'Shelf D4'),
(1, 5, 8, 5, 20, NULL, 'Refrigerator'),
(1, 9, 15, 10, NULL, NULL, NULL),
(2, 1, 30, 10, 50, NULL, 'Shelf A1'),
(2, 4, 65, 15, 70, '2026-06-03', 'Shelf D4'),
(2, 6, 20, 8, 40, NULL, 'Shelf E5'),
(2, 7, 100, 30, 200, NULL, 'Shelf F6'),
(2, 8, 15, 5, 25, NULL, 'Shelf C3'),
(3, 2, 20, 10, 40, NULL, 'Shelf B2'),
(3, 3, 5, 8, 20, NULL, 'Shelf C3'),
(3, 5, 10, 5, 25, NULL, 'Refrigerator'),
(3, 9, 12, 6, 30, NULL, 'Shelf G7'),
(3, 10, 18, 8, 35, NULL, 'Shelf E5'),
(4, 1, 45, 15, 80, NULL, 'Shelf A1'),
(4, 4, 60, 20, 120, NULL, 'Shelf D4'),
(4, 6, 25, 10, 50, NULL, 'Shelf E5'),
(4, 11, 30, 12, 55, NULL, 'Shelf H8'),
(4, 12, 5, 3, 15, NULL, 'Freezer'),
(5, 2, 15, 8, 35, NULL, 'Shelf B2'),
(5, 3, 20, 10, 40, NULL, 'Shelf C3'),
(5, 7, 80, 20, 150, NULL, 'Shelf F6'),
(5, 8, 10, 5, 20, NULL, 'Shelf C3'),
(5, 9, 8, 6, 25, NULL, 'Shelf G7'),
(6, 1, 13, 10, NULL, '2026-06-15', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `low_stock_report`
-- (See below for the actual view)
--
CREATE TABLE `low_stock_report` (
`store_id` int(11)
,`store_name` varchar(100)
,`location` varchar(255)
,`product_id` int(11)
,`product_name` varchar(100)
,`category` varchar(50)
,`quantity_on_hand` int(11)
,`reorder_level` int(11)
,`units_to_order` bigint(12)
);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `weight_kg` decimal(8,2) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `category`, `unit_price`, `weight_kg`, `supplier_id`) VALUES
(1, 'Coca-Cola 1.5L', 'Beverages', 65.00, 1.50, 2),
(2, 'Piattos 85g', 'Snacks', 25.00, 0.09, 2),
(3, 'Nescafe 3-in-1 10pcs', 'Coffee', 55.00, 0.20, 3),
(4, 'Lucky Me Pancit Canton', 'Noodles', 15.00, 0.06, 1),
(5, 'Bear Brand Milk 350ml', 'Dairy', 45.00, 0.35, 3),
(6, 'Corned Beef 150g', 'Canned Goods', 50.00, 0.15, 1),
(7, 'Skyflakes 25g', '', 9.00, 0.03, 4),
(8, 'Coffee Mate Creamer', 'Coffee', 30.00, 0.20, 3),
(9, 'Silver Swan Soy Sauce', 'Condiments', 25.00, 0.50, 5),
(10, 'Reno Liver Spread', 'Canned Goods', 42.00, 0.10, 1),
(11, 'Boy Bawang 50g', 'Snacks', 12.00, 0.05, 4),
(12, 'Selecta Ice Cream 1L', 'Frozen', 120.00, 1.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `restock_log`
--

CREATE TABLE `restock_log` (
  `restock_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `restock_quantity` int(11) NOT NULL,
  `restock_date` datetime DEFAULT current_timestamp(),
  `expected_delivery_date` date DEFAULT NULL,
  `restocked_by` varchar(100) DEFAULT NULL,
  `supplier_order_ref` varchar(50) DEFAULT NULL,
  `unit_cost_at_restock` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restock_log`
--

INSERT INTO `restock_log` (`restock_id`, `store_id`, `product_id`, `restock_quantity`, `restock_date`, `expected_delivery_date`, `restocked_by`, `supplier_order_ref`, `unit_cost_at_restock`) VALUES
(1, 1, 1, 20, '2026-06-03 20:34:29', NULL, 'Ginavi Fabila', NULL, 55.00),
(2, 1, 3, 15, '2026-06-03 20:34:29', NULL, 'Ginavi Fabila', NULL, 48.00),
(3, 2, 4, 30, '2026-06-03 20:34:29', NULL, 'June Dela Cruz', NULL, 13.00),
(4, 2, 6, 25, '2026-06-03 20:34:29', NULL, 'June Dela Cruz', NULL, 42.00),
(5, 3, 5, 12, '2026-06-03 20:34:29', NULL, 'Elisia Mondragon', NULL, 38.00),
(6, 3, 2, 20, '2026-06-03 20:34:29', NULL, 'Elisia Mondragon', NULL, 20.00),
(7, 4, 1, 30, '2026-06-03 20:34:29', NULL, 'Dan Gero', NULL, 52.00),
(8, 4, 11, 15, '2026-06-03 20:34:29', NULL, 'Dan Gero', NULL, 9.00),
(9, 5, 3, 15, '2026-06-03 20:34:29', NULL, 'Baby Fernandez', NULL, 48.00),
(10, 5, 7, 50, '2026-06-03 20:34:29', NULL, 'Baby Fernandez', NULL, 6.00),
(11, 1, 1, 10, '2026-06-03 20:53:39', NULL, 'Nena Dimagiba', NULL, 55.00),
(12, 1, 1, 10, '2026-06-03 20:54:05', NULL, 'Ginavi Fabila', NULL, 55.00),
(14, 1, 1, 10, '2026-06-03 20:55:42', NULL, 'Ginavi Fabila', NULL, 55.00),
(15, 1, 1, 20, '2026-06-03 20:57:50', NULL, 'Ginavi Fabila', NULL, 55.00),
(16, 2, 4, 30, '2026-06-03 20:59:48', NULL, 'June Dela Cruz', NULL, 48.00),
(17, 1, 1, 10, '2026-06-03 21:01:26', NULL, 'Ginavi Fabila', NULL, 55.00),
(18, 6, 1, 10, '2026-06-15 14:01:23', NULL, 'raezzel', NULL, 75.00),
(19, 6, 1, 5, '2026-06-15 14:02:09', NULL, 'raezzel', NULL, 100.00),
(20, 1, 9, 15, '2026-06-15 14:08:32', NULL, 'Ginavi Fabila', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment_log`
--

CREATE TABLE `stock_adjustment_log` (
  `adjustment_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `adjustment_type` varchar(50) NOT NULL,
  `quantity_changed` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `adjustment_date` datetime DEFAULT current_timestamp(),
  `authorized_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_adjustment_log`
--

INSERT INTO `stock_adjustment_log` (`adjustment_id`, `store_id`, `product_id`, `adjustment_type`, `quantity_changed`, `reason`, `adjustment_date`, `authorized_by`) VALUES
(1, 1, 5, 'Expired', -3, 'Milk expired on Dec 15', '2026-06-03 20:41:37', 'Ginavi Fabila'),
(2, 2, 7, 'Damaged', -5, 'Crushed during transport', '2026-06-03 20:41:37', 'June Dela Cruz'),
(3, 3, 3, 'Return', -2, 'Customer returned damaged pack', '2026-06-03 20:41:37', 'Elisia Mondragon'),
(4, 4, 12, 'Loss', -1, 'Missing inventory count', '2026-06-03 20:41:37', 'Dan Gero'),
(5, 5, 2, 'Correction', 2, 'Found misplaced stock', '2026-06-03 20:41:37', 'Baby Fernandez'),
(6, 6, 1, 'Correction', -2, 'buy', '2026-06-15 14:02:46', 'raezzel');

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(11) NOT NULL,
  `store_name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_id`, `store_name`, `location`, `phone`, `owner`) VALUES
(1, 'Fabila Sari-Sari Store', 'Bia-an Hamtic, Antique', '09123456789', 'Ginavi Fabila'),
(2, 'Manong June\'s Store', 'Dalipe San Jose, Antique', '09234567890', 'June Dela Cruz'),
(3, 'Elizabetha Mini Mart', 'Malandog Hamtic, Antique', '09345678901', 'Elisia Mondragon'),
(4, 'Danny\'s Shop', 'Punda Hamtic, Antique', '09456789012', 'Dan Gero'),
(5, 'Tita Baby\'s Store', 'Maybato San Jose, Antique', '09567890123', 'Baby Fernandez'),
(6, 'RaezzelStore', 'Bia-an Hamtic, Antique', '09123457642', 'raezz');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `contact_person`, `phone`, `email`, `address`) VALUES
(1, 'CDO Food Products', 'Maria Reyes', '09986542312', 'cdo@food.com', '123 Food St., Pasig City'),
(2, 'Universal Robina', 'John Santos', '09372835928', 'urc@corp.com', '456 Corporate Ave., Taguig'),
(3, 'Nestlé Philippines', 'Anna Cruz', '09532721982', 'nestle@ph.com', '789 N. Garcia St., Makati'),
(4, 'Monde Nissin', 'Robert Gomez', '09764312234', 'monde@nissin.com', '101 E. Rodriguez Ave., QC'),
(5, 'San Miguel Foods', 'Catherine Lopez', '09886544327', 'smf@sanmiguel.com', '202 SMC Complex, Pasig');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `role` enum('admin','store_owner') DEFAULT 'store_owner',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `store_id`, `role`, `created_at`) VALUES
(1, 'System Administrator', 'admin@system.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', '2026-06-03 17:20:57'),
(3, 'Ginavi Fabila', 'fabilastore@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'store_owner', '2026-06-04 02:16:00'),
(4, 'June Dela Cruz', 'junedelacruz@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'store_owner', '2026-06-04 02:17:03'),
(5, 'Elisia Mondragon', 'eli@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'store_owner', '2026-06-04 02:18:04'),
(6, 'Dan Gero', 'dangero@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'store_owner', '2026-06-04 02:18:49'),
(7, 'Baby Fernandez', 'titababystore@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 5, 'store_owner', '2026-06-04 02:19:45'),
(8, 'raezz', 'raezzelloufabila@gmail.com', '$2y$10$QJTJoeRd45E4QymuxzxAr.4f0veAySwUxr.czvXu3hWmoPqC07N7.', 6, 'store_owner', '2026-06-14 03:25:15');

-- --------------------------------------------------------

--
-- Structure for view `low_stock_report`
--
DROP TABLE IF EXISTS `low_stock_report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `low_stock_report`  AS SELECT `s`.`store_id` AS `store_id`, `s`.`store_name` AS `store_name`, `s`.`location` AS `location`, `p`.`product_id` AS `product_id`, `p`.`product_name` AS `product_name`, `p`.`category` AS `category`, `i`.`quantity_on_hand` AS `quantity_on_hand`, `i`.`reorder_level` AS `reorder_level`, `i`.`reorder_level`- `i`.`quantity_on_hand` AS `units_to_order` FROM ((`inventory` `i` join `store` `s` on(`i`.`store_id` = `s`.`store_id`)) join `product` `p` on(`i`.`product_id` = `p`.`product_id`)) WHERE `i`.`quantity_on_hand` <= `i`.`reorder_level` ORDER BY `i`.`quantity_on_hand` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`store_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_inventory_quantity` (`quantity_on_hand`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `idx_product_name` (`product_name`),
  ADD KEY `idx_product_category` (`category`);

--
-- Indexes for table `restock_log`
--
ALTER TABLE `restock_log`
  ADD PRIMARY KEY (`restock_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_restock_date` (`restock_date`);

--
-- Indexes for table `stock_adjustment_log`
--
ALTER TABLE `stock_adjustment_log`
  ADD PRIMARY KEY (`adjustment_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_adjustment_date` (`adjustment_date`),
  ADD KEY `idx_adjustment_type` (`adjustment_type`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `store_id` (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `restock_log`
--
ALTER TABLE `restock_log`
  MODIFY `restock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `stock_adjustment_log`
--
ALTER TABLE `stock_adjustment_log`
  MODIFY `adjustment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE SET NULL;

--
-- Constraints for table `restock_log`
--
ALTER TABLE `restock_log`
  ADD CONSTRAINT `restock_log_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `restock_log_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustment_log`
--
ALTER TABLE `stock_adjustment_log`
  ADD CONSTRAINT `stock_adjustment_log_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_log_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
