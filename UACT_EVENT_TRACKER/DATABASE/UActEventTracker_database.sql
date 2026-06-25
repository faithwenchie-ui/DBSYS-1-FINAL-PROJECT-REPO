-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2026 at 09:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uact_event_tracker`
--
CREATE DATABASE IF NOT EXISTS `uact_event_tracker` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `uact_event_tracker`;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'scheduled',
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `organization_id`, `venue_id`, `event_name`, `description`, `event_type`, `event_date`, `start_time`, `end_time`, `capacity`, `status`, `created_by`, `created_date`) VALUES
(1, 1, 1, 'IT Career Seminar', 'Seminar on career opportunities in IT.', 'seminar', '2026-03-05', '09:00:00', '12:00:00', 40, 'completed', 1, '2026-02-20 00:00:00'),
(2, 2, 2, 'Science Quiz Bee', 'Inter-class science competition.', 'contest', '2026-03-10', '13:00:00', '16:00:00', 35, 'completed', 4, '2026-02-22 01:30:00'),
(3, 3, 3, 'Entrepreneurship Workshop', 'Workshop on business startup basics.', 'workshop', '2026-03-12', '08:30:00', '11:30:00', 50, 'completed', 3, '2026-02-23 02:00:00'),
(4, 4, 4, 'Culture Night', 'Performance night showcasing student talent.', 'social', '2026-03-15', '17:00:00', '20:00:00', 200, 'completed', 6, '2026-02-24 06:00:00'),
(5, 5, 5, 'Math Challenge', 'Problem-solving challenge for students.', 'contest', '2026-03-18', '09:00:00', '12:00:00', 30, 'completed', 9, '2026-02-25 03:00:00'),
(6, 6, 6, 'Essay Writing Contest', 'Competition for creative and academic writing.', 'contest', '2026-03-20', '10:00:00', '12:00:00', 25, 'completed', 10, '2026-02-26 01:00:00'),
(7, 7, 7, 'Sports Fest Orientation', 'Orientation for upcoming sports fest.', 'seminar', '2026-03-22', '08:00:00', '10:00:00', 300, 'completed', 5, '2026-02-27 05:00:00'),
(8, 8, 8, 'Tourism Expo Prep', 'Preparation meeting for tourism expo.', 'workshop', '2026-03-25', '14:00:00', '16:00:00', 60, 'completed', 6, '2026-02-28 07:00:00'),
(9, 9, 9, 'Debate Training', 'Training session for debate participants.', 'workshop', '2026-03-28', '13:00:00', '15:00:00', 45, 'scheduled', 3, '2026-03-01 00:30:00'),
(10, 10, 10, 'Community Outreach Planning', 'Planning for outreach and volunteer activities.', 'meeting', '2026-03-30', '09:00:00', '11:00:00', 40, 'scheduled', 2, '2026-03-02 02:30:00'),
(11, 5, 5, 'Math Quiz Bee', 'This event showcase the mastery of students in solving mathematical problems.', 'contest', '2026-06-22', '09:30:00', '17:00:00', 20, 'scheduled', 7, '2026-06-02 06:28:41');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `year_level` int(11) DEFAULT NULL,
  `course` varchar(100) NOT NULL,
  `registration_date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `student_id`, `first_name`, `last_name`, `email`, `phone`, `year_level`, `course`, `registration_date`) VALUES
(1, '2026-0001', 'Juan', 'Dela Cruz', 'juan.delacruz@school.edu', '09170000001', 3, 'BSIT', '2026-02-01'),
(2, '2026-0002', 'Maria', 'Santos', 'maria.santos@school.edu', '09170000002', 2, 'BSCS', '2026-02-01'),
(3, '2026-0003', 'Pedro', 'Reyes', 'pedro.reyes@school.edu', '09170000003', 4, 'BSBA', '2026-02-02'),
(4, '2026-0004', 'Angela', 'Torres', 'angela.torres@school.edu', '09170000004', 1, 'BSBIO', '2026-02-02'),
(5, '2026-0005', 'Joshua', 'Cruz', 'joshua.cruz@school.edu', '09170000005', 3, 'BSEd', '2026-02-03'),
(6, '2026-0006', 'Nicole', 'Garcia', 'nicole.garcia@school.edu', '09170000006', 2, 'BSTM', '2026-02-03'),
(7, '2026-0007', 'Mark', 'Lopez', 'mark.lopez@school.edu', '09170000007', 4, 'BSIT', '2026-02-04'),
(8, '2026-0008', 'Camille', 'Fernandez', 'camille.fernandez@school.edu', '09170000008', 1, 'BSCS', '2026-02-04'),
(9, '2026-0009', 'Ryan', 'Mendoza', 'ryan.mendoza@school.edu', '09170000009', 2, 'BSBA', '2026-02-05'),
(10, '2026-0010', 'Bea', 'Alvarez', 'bea.alvarez@school.edu', '09170000010', 3, 'BSTM', '2026-02-05');

-- --------------------------------------------------------

--
-- Stand-in structure for view `most_attended_events`
-- (See below for the actual view)
--
CREATE TABLE `most_attended_events` (
`event_id` int(11)
,`event_name` varchar(150)
,`org_name` varchar(100)
,`event_date` date
,`total_registrations` bigint(21)
,`total_attended` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `organization_id` int(11) NOT NULL,
  `org_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `advisor_name` varchar(100) NOT NULL,
  `advisor_email` varchar(100) NOT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `created_date` date NOT NULL DEFAULT curdate(),
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`organization_id`, `org_name`, `description`, `advisor_name`, `advisor_email`, `contact_phone`, `created_date`, `status`) VALUES
(1, 'UACT Computer Society', 'Organization for IT and computing students.', 'Dr. Maria Cruz', 'maria.cruz@school.edu', '09171234561', '2026-01-10', 'active'),
(2, 'UACT Science Club', 'Promotes scientific research and activities.', 'Prof. Ana Santos', 'ana.santos@school.edu', '09171234562', '2026-01-12', 'active'),
(3, 'UACT Business Circle', 'Develops leadership and entrepreneurship skills.', 'Mr. Joel Reyes', 'joel.reyes@school.edu', '09171234563', '2026-01-15', 'active'),
(4, 'UACT Arts Guild', 'Supports creative and performance activities.', 'Ms. Liza Garcia', 'liza.garcia@school.edu', '09171234564', '2026-01-18', 'active'),
(5, 'UACT Math Society', 'Encourages problem-solving and analytical thinking.', 'Dr. Peter Lim', 'peter.lim@school.edu', '09171234565', '2026-01-20', 'active'),
(6, 'UACT English Club', 'Focuses on writing, reading, and communication.', 'Ms. Grace Tan', 'grace.tan@school.edu', '09171234566', '2026-01-22', 'active'),
(7, 'UACT PE Club', 'Promotes sports and wellness activities.', 'Coach Ben dela Cruz', 'ben.delacruz@school.edu', '09171234567', '2026-01-25', 'active'),
(8, 'UACT Tourism Society', 'Supports tourism and hospitality awareness.', 'Ms. Carla Dizon', 'carla.dizon@school.edu', '09171234568', '2026-01-28', 'active'),
(9, 'UACT Debate Society', 'Trains students in argumentation and public speaking.', 'Mr. Ivan Mercado', 'ivan.mercado@school.edu', '09171234569', '2026-02-01', 'active'),
(10, 'UACT Volunteer Group', 'Leads community outreach and service activities.', 'Ms. Helen Padilla', 'helen.padilla@school.edu', '09171234570', '2026-02-03', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `registration_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `attendance_status` varchar(20) NOT NULL DEFAULT 'registered',
  `check_in_time` timestamp NULL DEFAULT NULL,
  `check_out_time` timestamp NULL DEFAULT NULL,
  `confirmation_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`registration_id`, `event_id`, `member_id`, `registration_date`, `attendance_status`, `check_in_time`, `check_out_time`, `confirmation_status`) VALUES
(1, 1, 1, '2026-03-01 00:00:00', 'checked_in', '2026-03-05 00:45:00', '2026-03-05 03:55:00', 'confirmed'),
(2, 1, 2, '2026-03-01 00:05:00', 'checked_in', '2026-03-05 00:50:00', '2026-03-05 03:50:00', 'confirmed'),
(3, 1, 3, '2026-03-01 00:10:00', 'checked_in', '2026-03-05 00:55:00', '2026-03-05 03:40:00', 'confirmed'),
(4, 2, 4, '2026-03-02 01:00:00', 'checked_in', '2026-03-10 04:55:00', '2026-03-10 07:45:00', 'confirmed'),
(5, 2, 5, '2026-03-02 01:05:00', 'checked_in', '2026-03-10 04:58:00', '2026-03-10 07:40:00', 'confirmed'),
(6, 3, 6, '2026-03-03 02:00:00', 'checked_in', '2026-03-12 00:20:00', '2026-03-12 03:15:00', 'confirmed'),
(7, 3, 7, '2026-03-03 02:05:00', 'checked_in', '2026-03-12 00:25:00', '2026-03-12 03:10:00', 'confirmed'),
(8, 4, 8, '2026-03-04 03:00:00', 'checked_in', '2026-03-15 08:50:00', '2026-03-15 11:45:00', 'confirmed'),
(9, 5, 9, '2026-03-05 04:00:00', 'checked_in', '2026-03-18 00:50:00', '2026-03-18 03:45:00', 'confirmed'),
(10, 6, 10, '2026-03-06 05:00:00', 'checked_in', '2026-03-20 01:50:00', '2026-03-20 03:55:00', 'confirmed'),
(11, 7, 1, '2026-03-07 06:00:00', 'registered', NULL, NULL, 'pending'),
(12, 8, 2, '2026-03-08 07:00:00', 'registered', NULL, NULL, 'pending'),
(13, 9, 3, '2026-03-09 08:00:00', 'registered', NULL, NULL, 'pending'),
(14, 10, 4, '2026-03-10 09:00:00', 'registered', NULL, NULL, 'pending'),
(15, 9, 5, '2026-06-02 06:23:17', 'registered', NULL, NULL, 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `venue_id` int(11) NOT NULL,
  `venue_name` varchar(100) NOT NULL,
  `building` varchar(100) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `capacity` int(11) NOT NULL,
  `floor` int(11) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`venue_id`, `venue_name`, `building`, `room_number`, `capacity`, `floor`, `features`, `is_available`) VALUES
(1, 'Computer Lab 1', 'Main Building', 'M101', 40, 1, 'Projector, computers, Wi-Fi', 1),
(2, 'Science Laboratory', 'Science Building', 'S201', 35, 2, 'Microscopes, lab tables, sink', 1),
(3, 'Conference Room A', 'Admin Building', 'A301', 50, 3, 'Sound system, projector', 1),
(4, 'Auditorium', 'Event Center', 'E001', 200, 0, 'Stage, microphone, LED screen', 1),
(5, 'Math Room', 'Academic Building', 'A204', 30, 2, 'Whiteboard, projector', 1),
(6, 'English Room', 'Academic Building', 'A205', 25, 2, 'Tables, chairs, speakers', 1),
(7, 'Gymnasium', 'Sports Complex', 'G101', 300, 1, 'Court, lights, seating', 1),
(8, 'Tourism Hall', 'Business Building', 'B102', 60, 1, 'Projector, aircon', 1),
(9, 'Debate Hall', 'Admin Building', 'A401', 45, 4, 'Podium, speakers, projector', 1),
(10, 'Community Room', 'Extension Center', 'C101', 40, 1, 'Tables, chairs, whiteboard', 1);

-- --------------------------------------------------------

--
-- Structure for view `most_attended_events`
--
DROP TABLE IF EXISTS `most_attended_events`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `most_attended_events`  AS SELECT `e`.`event_id` AS `event_id`, `e`.`event_name` AS `event_name`, `o`.`org_name` AS `org_name`, `e`.`event_date` AS `event_date`, count(`r`.`registration_id`) AS `total_registrations`, sum(case when `r`.`attendance_status` = 'checked_in' then 1 else 0 end) AS `total_attended` FROM ((`events` `e` join `organizations` `o` on(`e`.`organization_id` = `o`.`organization_id`)) left join `registrations` `r` on(`e`.`event_id` = `r`.`event_id`)) GROUP BY `e`.`event_id`, `e`.`event_name`, `o`.`org_name`, `e`.`event_date` ORDER BY sum(case when `r`.`attendance_status` = 'checked_in' then 1 else 0 end) DESC, count(`r`.`registration_id`) DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `idx_events_organization_id` (`organization_id`),
  ADD KEY `idx_events_venue_id` (`venue_id`),
  ADD KEY `idx_events_event_date` (`event_date`),
  ADD KEY `idx_events_created_by` (`created_by`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`organization_id`),
  ADD UNIQUE KEY `org_name` (`org_name`),
  ADD UNIQUE KEY `advisor_email` (`advisor_email`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `uq_event_member` (`event_id`,`member_id`),
  ADD KEY `idx_registrations_event_id` (`event_id`),
  ADD KEY `idx_registrations_member_id` (`member_id`),
  ADD KEY `idx_registrations_attendance_status` (`attendance_status`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`venue_id`),
  ADD UNIQUE KEY `venue_name` (`venue_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `organization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_created_by` FOREIGN KEY (`created_by`) REFERENCES `members` (`member_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_events_organization` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`organization_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_events_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`venue_id`) ON UPDATE CASCADE;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `fk_registrations_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registrations_member` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
