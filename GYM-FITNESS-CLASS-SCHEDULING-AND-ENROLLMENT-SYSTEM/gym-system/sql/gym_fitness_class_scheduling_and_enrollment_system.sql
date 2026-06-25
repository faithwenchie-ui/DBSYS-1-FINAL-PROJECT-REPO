-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 04:08 PM
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
-- Database: `gym_fitness_class_scheduling_and_enrollment_system`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `EnrollMember` (IN `p_member_id` INT, IN `p_class_id` INT)   BEGIN
    -- This procedure registers a member to a class with an 'active' status
    INSERT INTO enrollments (member_id, class_id, status, enroll_date)
    VALUES (p_member_id, p_class_id, 'active', CURDATE());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password_hash`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$5ajU/ttOhMJO6p0Z9/1mBu0Z1.5F4E80dzVKVntqglXWRDv2ZZ5eG', '2026-06-21 15:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_log`
--

CREATE TABLE `attendance_log` (
  `log_id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `marked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_log`
--

INSERT INTO `attendance_log` (`log_id`, `enrollment_id`, `status`, `marked_at`) VALUES
(1, 1, 'present', '2026-05-21 19:58:39'),
(2, 2, 'present', '2026-05-21 19:58:39'),
(3, 3, 'absent', '2026-05-21 19:58:39'),
(4, 4, 'present', '2026-05-21 19:58:39'),
(5, 5, 'present', '2026-05-21 19:58:39'),
(6, 6, 'present', '2026-05-21 19:58:39'),
(7, 7, 'absent', '2026-05-21 19:58:39'),
(8, 8, 'present', '2026-05-21 19:58:39'),
(9, 9, 'present', '2026-05-21 19:58:39'),
(10, 10, 'present', '2026-05-21 19:58:39');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `schedule_at` datetime NOT NULL,
  `max_capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `trainer_id`, `class_name`, `schedule_at`, `max_capacity`) VALUES
(1, 1, 'Morning Boxing', '2025-06-01 08:00:00', 15),
(2, 2, 'Cycling Sprint', '2025-06-01 10:00:00', 20),
(3, 3, 'Bootcamp Core', '2025-06-02 07:00:00', 12),
(4, 4, 'Zen Yoga', '2025-06-02 17:00:00', 25),
(5, 5, 'Action Agility', '2025-06-03 09:00:00', 10),
(6, 6, 'Hoops Training', '2025-06-03 15:00:00', 15),
(7, 7, 'Heavy Lifting', '2025-06-04 16:00:00', 10),
(8, 8, 'CrossFit Daily', '2025-06-04 18:00:00', 20),
(9, 9, 'Endurance Run', '2025-06-05 06:00:00', 30),
(10, 10, 'Arnis Basics', '2025-06-05 14:00:00', 12);

-- --------------------------------------------------------

--
-- Stand-in structure for view `class_enrollment_summary`
-- (See below for the actual view)
--
CREATE TABLE `class_enrollment_summary` (
`class_id` int(11)
,`class_name` varchar(100)
,`trainer_name` varchar(100)
,`schedule_at` datetime
,`total_enrolled` bigint(21)
,`max_capacity` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `enroll_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `member_id`, `class_id`, `status`, `enroll_date`) VALUES
(1, 1, 1, 'active', '2025-05-20'),
(2, 2, 2, 'active', '2025-05-20'),
(3, 3, 3, 'active', '2025-05-21'),
(4, 4, 4, 'active', '2025-05-21'),
(5, 5, 5, 'active', '2025-05-22'),
(6, 6, 6, 'active', '2025-05-22'),
(7, 7, 7, 'active', '2025-05-23'),
(8, 8, 8, 'active', '2025-05-23'),
(9, 9, 9, 'active', '2025-05-24'),
(10, 10, 10, 'active', '2025-05-24');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `full_name`, `email`, `phone`, `password_hash`, `created_at`) VALUES
(1, 'Jinggoy Estrada', 'jinggoy.estrada@gmail.com', '09171112222', '$2y$10$.56zLWmKwP1luT3LtcTK5e6CNfnPEsbvxOqeS651TgfumCSYmQKle', '2026-05-21 19:55:44'),
(2, 'Loren Legarda', 'loren.legarda@gmail.com', '09173334444', '$2y$10$FF2UKam9CkTFxwLbqNXFNe3p2uubJ.5rWlsZQ5esceNPeRYE.O0Qm', '2026-05-21 19:55:44'),
(3, 'Cynthia Villar', 'cynthia.villar@gmail.com', '09175556666', '$2y$10$z2GS/1hdDX/mOS/TgxbhlOO8wsMzDzMzdkaMqufKtchdbXrJfkD/a', '2026-05-21 19:55:44'),
(4, 'Grace Poe', 'grace.poe@gmail.com', '09177778888', '$2y$10$5BcYnrHCx2LkHF9WWvh1r.wCj7jEks4RriAMfT5BUVp2yzy.4SvJa', '2026-05-21 19:55:44'),
(5, 'Chiz Escudero', 'chiz.escudero@gmail.com', '09179990000', '$2y$10$AjacUhTgUdAsBA.DXaWgVeavqe4W/oB5za7Jro68laKJsxVy/NBcG', '2026-05-21 19:55:44'),
(6, 'Nancy Binay', 'nancy.binay@gmail.com', '09181112222', '$2y$10$D.LjJHI6tJ3yKRvK3tyok.KKazPnY279ALQc2vA6FtgyYMuKOBJZG', '2026-05-21 19:55:44'),
(7, 'Imee Marcos', 'imee.marcos@gmail.com', '09183334444', '$2y$10$41yOR9b0vBZzoIYnaUkHYeZUYyKAiK65Ikw7XR9Krr6J9B6G2YMvC', '2026-05-21 19:55:44'),
(8, 'Bong Revilla', 'bong.revilla@gmail.com', '09185556666', '$2y$10$93i1mnlYt7HGxp8aw1Xh8OkboiDqvCs8QDc70xN7UlxMKuNcJ18z2', '2026-05-21 19:55:44'),
(9, 'Francis Tolentino', 'francis.tolentino@gmail.com', '09187778888', '$2y$10$K0veHVE0oDrDQJvrG8a7b.d7kWOPl7ot2leidLkjInnIPiOqyqugK', '2026-05-21 19:55:44'),
(10, 'Mark Villar', 'mark.villar@gmail.com', '09189990000', '$2y$10$gGhG5D/vWjujo7Pvq2eSHO9ceoxGWG6xG.lSStN9wQ2JxiVxOE6CC', '2026-05-21 19:55:44'),
(21, 'Neil Peter', 'neilpeter@gmail.com', '09123456789', '$2y$10$M9tdOD5jQmkkxEKvpqkuzu6DmJBVbADopu4dAOvtgQSp9vfsIBLu.', '2026-06-12 14:46:22'),
(22, 'testlangja', 'testlangja@gmail.com', '09736223329', '$2y$10$O07gZ1QY9DvA3aT48PDkrupnLon/L/0Ni21G1vRPEbANGdAdonEWG', '2026-06-23 02:39:52');

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `trainer_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`trainer_id`, `full_name`, `specialty`, `email`) VALUES
(1, 'Robin Padilla', 'Boxing & Combat', 'robin.padilla@gym.ph'),
(2, 'Pia Cayetano', 'Triathlon & Cycling', 'pia.cayetano@gym.ph'),
(3, 'Ronald Dela Rosa', 'Tactical Fitness', 'bato.delarosa@gym.ph'),
(4, 'Risa Hontiveros', 'Yoga & Wellness', 'risa.hontiveros@gym.ph'),
(5, 'Lito Lapid', 'Agility & Stunts', 'lito.lapid@gym.ph'),
(6, 'Bong Go', 'Basketball Drills', 'bong.go@gym.ph'),
(7, 'Win Gatchalian', 'Weightlifting', 'win.gatchalian@gym.ph'),
(8, 'Sonny Angara', 'CrossFit', 'sonny.angara@gym.ph'),
(9, 'Joel Villanueva', 'Endurance Training', 'joel.villanueva@gym.ph'),
(10, 'Migz Zubiri', 'Arnis & Martial Arts', 'migz.zubiri@gym.ph');

-- --------------------------------------------------------

--
-- Structure for view `class_enrollment_summary`
--
DROP TABLE IF EXISTS `class_enrollment_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `class_enrollment_summary`  AS SELECT `c`.`class_id` AS `class_id`, `c`.`class_name` AS `class_name`, `t`.`full_name` AS `trainer_name`, `c`.`schedule_at` AS `schedule_at`, count(`e`.`enrollment_id`) AS `total_enrolled`, `c`.`max_capacity` AS `max_capacity` FROM ((`classes` `c` join `trainers` `t` on(`c`.`trainer_id` = `t`.`trainer_id`)) left join `enrollments` `e` on(`c`.`class_id` = `e`.`class_id`)) GROUP BY `c`.`class_id`, `c`.`class_name`, `t`.`full_name`, `c`.`schedule_at`, `c`.`max_capacity` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendance_log`
--
ALTER TABLE `attendance_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_attendance_enrollment` (`enrollment_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `idx_classes_trainer` (`trainer_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `idx_enrollments_member` (`member_id`),
  ADD KEY `idx_enrollments_class` (`class_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`trainer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance_log`
--
ALTER TABLE `attendance_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `trainer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance_log`
--
ALTER TABLE `attendance_log`
  ADD CONSTRAINT `attendance_log_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`enrollment_id`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`trainer_id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
