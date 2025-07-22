-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 02:06 PM
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
-- Database: `school_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitylog`
--

CREATE TABLE `activitylog` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` enum('CREATE','UPDATE','DELETE','LOGIN','LOGOUT') DEFAULT NULL,
  `table_affected` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `table_affected` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_id`, `action_type`, `table_affected`, `record_id`, `timestamp`, `description`) VALUES
(1, 2, 'UPDATE', 'students', 1, '2025-07-20 18:40:01', 'Updated student: Nanvuma Esther'),
(2, 2, 'CREATE', 'students', 6, '2025-07-22 07:31:06', 'Added new student: Kiddu Isma');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('present','absent','late') DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `student_id`, `class_id`, `date`, `status`, `remarks`) VALUES
(1, 3, 1, '2025-07-20', 'absent', NULL),
(2, 1, 1, '2025-07-20', 'present', NULL),
(3, 2, 1, '2025-07-20', 'present', NULL),
(4, 3, 1, '2025-07-22', 'present', NULL),
(5, 1, 1, '2025-07-22', 'absent', NULL),
(6, 5, 1, '2025-07-22', 'present', NULL),
(7, 2, 1, '2025-07-22', 'present', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `name`, `year`, `teacher_id`) VALUES
(1, 'S1', 2025, 1),
(2, 'S2', 2025, 1),
(3, 'S3', 2025, 2),
(4, 'S4', 2025, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `academic_year` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `student_id`, `class_id`, `academic_year`) VALUES
(1, 1, 1, '2025'),
(3, 2, 1, '2025'),
(4, 3, 2, '2026'),
(6, 5, 2, '2025'),
(8, 6, 1, '2025'),
(9, 13, 2, '2025');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `organizer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `name`, `description`, `event_date`, `location`, `organizer_id`) VALUES
(1, 'Music Dance and Drama', '', '2025-08-05', 'Springfield Play ground', 1),
(2, 'Music Dance and Drama', '', '2025-07-20', 'Springfield Play ground', 2);

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `exam_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `date` year(4) DEFAULT NULL,
  `term` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`exam_id`, `subject_id`, `class_id`, `name`, `date`, `term`) VALUES
(1, 1, 1, 'BOT', '2025', 'Term II'),
(2, 1, 1, 'MOD', '2025', 'Term II'),
(3, 6, 2, 'EOT', '2025', 'Term II'),
(5, NULL, NULL, 'Assessment 1', '2025', 'Term II');

-- --------------------------------------------------------

--
-- Table structure for table `exam_class`
--

CREATE TABLE `exam_class` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_class`
--

INSERT INTO `exam_class` (`id`, `exam_id`, `class_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `fee_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `term` varchar(20) DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `marks` decimal(5,2) DEFAULT NULL,
  `grade` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`grade_id`, `exam_id`, `subject_id`, `student_id`, `marks`, `grade`) VALUES
(7, 1, NULL, 1, 3.00, NULL),
(10, 3, NULL, 5, 2.10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guardians`
--

CREATE TABLE `guardians` (
  `guardian_id` int(11) NOT NULL,
  `parent_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guardians`
--

INSERT INTO `guardians` (`guardian_id`, `parent_name`, `phone`, `email`, `address`, `relationship`) VALUES
(1, 'Nyakamwe Thomas', '0750994609', 'abdusserugo7@gmail.com', 'Bombo, Uganda', ''),
(2, 'Nyakamwe Thomas', '0750994609', 'abdusserugo7@gmail.com', 'Bombo, Uganda', ''),
(3, 'Januali Moses', '0776067970', 'ssalongo@gmail.com', 'Gombe, Butamba', 'Parent');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `fee_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `method` enum('cash','card','transfer','other') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `salary_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `first_name`, `last_name`, `dob`, `role`, `department`, `email`, `phone`, `address`, `password`) VALUES
(1, 'Nyakamwe', 'Thomas', '1980-01-01', 'Administrator', 'IT', 'abdusserugo7@gmail.com', '0750994609', 'Butambala, Gombe, Ssenyomo', '$2y$10$w9Adc2pWZhevSIgTtpzA9uCbW3XfnkXM4HSXV./q/SwNGI/CyR3EW'),
(2, 'Musoowa', 'Micheal', '1989-12-02', 'user', 'Academics', 'musoowamichea2019@gmail.com', '0706203243', 'Jinja, Uganda', '$2y$10$Tj3f21KqFD2Oo5REd7ME8eMhSBSI1bml.wOUj8xHQssrl1B2vyKmm');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `guardian_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `class_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `dob`, `gender`, `address`, `email`, `phone`, `guardian_id`, `class_id`, `class_name`) VALUES
(1, 'Nanvuma', 'Esther', '1987-04-21', 'Female', 'Jakana', 'nanvumae611@gmail.com', '0776067970', NULL, 1, 'S1'),
(2, 'Tweheyo', 'Janice', '2007-08-21', 'Female', 'Kampala', 'tweheyo@gmail.com', '0776067970', NULL, 1, 'S1'),
(3, 'Kaweesi', 'Azhur', '2023-10-10', 'Male', '', '', '', NULL, 1, 'S1'),
(5, 'Opwonya', 'Denise Sande', '2001-12-12', 'Male', '', 'opwonya@gmail.com', '', NULL, 2, 'S2'),
(6, 'Kiddu', 'Isma', '2009-12-12', 'Male', 'Jakana', 'kiddu@gmail.com', '0776067970', NULL, 2, 'S2'),
(9, 'Akampa', 'Remmy', '2008-03-22', 'Male', 'Bombo, Uganda', 'akamparemmy@gmail.com', '0776067970', 1, 2, NULL),
(11, 'Akampa', 'Remmy', '2008-03-22', 'Male', 'Bombo, Uganda', 'akamparemmy1@gmail.com', '0776067970', 2, 2, NULL),
(13, 'Nankanja', 'Oliver', '2003-06-22', 'Female', 'Gombe,  Butambala', 'nankanja@gmail.com', '0776067970', 3, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_marks`
--

CREATE TABLE `student_marks` (
  `mark_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `term` varchar(20) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `marks_obtained` decimal(5,2) DEFAULT NULL,
  `max_marks` decimal(5,2) DEFAULT NULL,
  `exam_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_marks`
--

INSERT INTO `student_marks` (`mark_id`, `student_id`, `subject_id`, `class_id`, `term`, `year`, `marks_obtained`, `max_marks`, `exam_date`) VALUES
(1, 3, 1, 1, 'Term2', '2025', 2.50, 100.00, '2025-07-21'),
(3, 9, 15, 1, 'Term 2', '2025', 2.00, 3.00, '2025-07-22');

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `term` varchar(10) DEFAULT NULL,
  `year` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`id`, `student_id`, `subject_id`, `class_id`, `term`, `year`) VALUES
(56, 2, 2, 0, NULL, NULL),
(54, 2, 6, 0, NULL, NULL),
(57, 2, 7, 0, NULL, NULL),
(55, 2, 11, 0, NULL, NULL),
(51, 2, 15, 0, NULL, NULL),
(52, 2, 16, 0, NULL, NULL),
(53, 2, 17, 0, NULL, NULL),
(70, 3, 1, 0, NULL, NULL),
(62, 3, 2, 0, NULL, NULL),
(65, 3, 3, 0, NULL, NULL),
(64, 3, 4, 0, NULL, NULL),
(72, 3, 5, 0, NULL, NULL),
(60, 3, 6, 0, NULL, NULL),
(63, 3, 7, 0, NULL, NULL),
(68, 3, 8, 0, NULL, NULL),
(69, 3, 9, 0, NULL, NULL),
(71, 3, 10, 0, NULL, NULL),
(61, 3, 11, 0, NULL, NULL),
(67, 3, 13, 0, NULL, NULL),
(66, 3, 14, 0, NULL, NULL),
(58, 3, 15, 0, NULL, NULL),
(59, 3, 16, 0, NULL, NULL),
(78, 5, 2, 0, NULL, NULL),
(81, 5, 3, 0, NULL, NULL),
(80, 5, 4, 0, NULL, NULL),
(76, 5, 6, 0, NULL, NULL),
(79, 5, 7, 0, NULL, NULL),
(77, 5, 11, 0, NULL, NULL),
(73, 5, 15, 0, NULL, NULL),
(74, 5, 16, 0, NULL, NULL),
(75, 5, 17, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `name`, `code`, `description`) VALUES
(1, 'Mathematics', 'MTC', 'Core science subject'),
(2, 'English', 'ENG', 'Language'),
(3, 'History and Pol Ed', 'HIST', 'Humanity'),
(4, 'Geography', 'GEO', 'Humanity'),
(5, 'Physics', 'PHY', 'Science'),
(6, 'Chemistry', 'CHEM', 'Science'),
(7, 'Entreprenuership', 'ENT', 'Business'),
(8, 'Kiswahil', 'KIS', 'Language'),
(9, 'Literature', 'LIT', 'Language'),
(10, 'Physical Education', 'PE', 'Vocational'),
(11, 'Christian Religious Education', 'CRE', 'Religious studies'),
(13, 'Islamic Religious Education', 'IRE', 'Religious studies'),
(14, 'Information Commnunication Techn', 'ICT', 'Vocational'),
(15, 'Agriculture', 'AGR', 'Vocational'),
(16, 'Fine Art', 'ART', 'Vocational'),
(17, 'Biology', 'BIO', 'Science');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `type` enum('income','expense') DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `recorded_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activitylog`
--
ALTER TABLE `activitylog`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`exam_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `exam_class`
--
ALTER TABLE `exam_class`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exam_id` (`exam_id`,`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`fee_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `guardians`
--
ALTER TABLE `guardians`
  ADD PRIMARY KEY (`guardian_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `fee_id` (`fee_id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`salary_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `guardian_id` (`guardian_id`),
  ADD KEY `fk_class` (`class_id`);

--
-- Indexes for table `student_marks`
--
ALTER TABLE `student_marks`
  ADD PRIMARY KEY (`mark_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`student_id`,`subject_id`,`class_id`,`term`,`year`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `recorded_by` (`recorded_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activitylog`
--
ALTER TABLE `activitylog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `exam_class`
--
ALTER TABLE `exam_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `fee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `guardians`
--
ALTER TABLE `guardians`
  MODIFY `guardian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `student_marks`
--
ALTER TABLE `student_marks`
  MODIFY `mark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activitylog`
--
ALTER TABLE `activitylog`
  ADD CONSTRAINT `activitylog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `exam_class`
--
ALTER TABLE `exam_class`
  ADD CONSTRAINT `exam_class_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exam_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exam_id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`fee_id`) REFERENCES `fees` (`fee_id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`);

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `salaries_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`guardian_id`) REFERENCES `guardians` (`guardian_id`);

--
-- Constraints for table `student_marks`
--
ALTER TABLE `student_marks`
  ADD CONSTRAINT `student_marks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `student_marks_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `student_marks_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`recorded_by`) REFERENCES `staff` (`staff_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
