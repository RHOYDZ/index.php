-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2024 at 07:01 AM
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
-- Database: `your_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificate_indigency`
--

CREATE TABLE `certificate_indigency` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `zone` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `house_number` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `payment_method` enum('gcash','barangay_hall') NOT NULL,
  `reference_code` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `paid` enum('yes','no') DEFAULT 'no',
  `is_reference_code_green` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_indigency`
--

INSERT INTO `certificate_indigency` (`id`, `name`, `zone`, `street`, `house_number`, `age`, `purpose`, `payment_method`, `reference_code`, `created_at`, `user_id`, `email`, `paid`, `is_reference_code_green`) VALUES
(11, 'FACUN JANNA MAE L.', '4', 'PROVINCIAL ROAD', '3', 18, 'fuck fuck ', 'gcash', '29C82E9FCF', '2024-12-29 10:29:49', 0, 'facunjannamae@gmail.com', 'yes', 0),
(12, 'FACUN JANNA MAE L.', '4', 'PROVINCIAL ROAD', '3', 18, 'iyot kami ', 'gcash', 'DB154BC484', '2024-12-29 10:30:28', 0, 'facunjannamae@gmail.com', 'no', 0),
(13, 'FACUN JANNA MAE L.', '4', 'PROVINCIAL ROAD', '3', 18, 'duwadawidnawodnaojns', 'gcash', 'F448B3AEFE', '2024-12-29 10:32:04', 0, 'facunjannamae@gmail.com', 'no', 0),
(14, 'FACUN JANNA MAE L.', '4', 'PROVINCIAL ROAD', '3', 18, '2e1e12e1e21e1', 'barangay_hall', 'DF5B1A3A8D', '2024-12-29 10:39:02', 0, 'facunjannamae@gmail.com', 'yes', 0),
(15, 'FACUN JANNA MAE L.', '4', 'PROVINCIAL ROAD', '3', 18, 'para sa pag aaral', 'barangay_hall', '8D3261C6DD', '2024-12-29 10:50:45', 0, 'facunjannamae@gmail.com', 'yes', 0),
(16, 'PABLO RHOY JUDIEL DE LEON', '7', 'PROVINCIAL ROAD', '3', 22, 'fafAFVRfv warv rw', 'barangay_hall', '54DB81C0AD', '2024-12-29 12:17:10', 0, 'rhoydeleonpablo01@gmail.com', 'yes', 0),
(17, 'TESTING ACCOUNT', '6', 'PROVINCIAL ROAD', '3', 22, 'work ', 'gcash', '11208FC430', '2024-12-29 14:29:42', 0, 'testing@gmail.com', 'no', 0),
(18, 'PABLO RHOY JUDIEL DE LEON', '7', 'PROVINCIAL ROAD', '3', 22, 'mag lalabas nang motor', 'gcash', '9B3BBA154D', '2024-12-30 00:45:22', 0, 'rhoydeleonpablo01@gmail.com', 'no', 0);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `message_status` varchar(50) NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_staff`
--

CREATE TABLE `tbl_staff` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_staff`
--

INSERT INTO `tbl_staff` (`id`, `name`, `image_name`, `position`) VALUES
(11, 'anne jelica pablo', '6772b716ecb9b.jpg', 'Chief Tanod'),
(12, 'anne jelica pablo', '6772b730d7cd8.jpg', 'Kapitan'),
(13, 'anne jelica pablo', '6772b73d1f295.jpg', 'Secretary'),
(14, 'anne jelica pablo', '6772b74f1ca44.jpg', 'Treasurer'),
(15, 'anne jelica pablo', '6772b754ad3b9.jpg', 'Clinic'),
(16, 'anne jelica pablo', '6772b75b7d964.jpg', 'Daycare'),
(17, 'anne jelica pablo', '6772b7677147d.jpg', 'Tanod'),
(18, 'jolina', '6772b94cac04a.jpg', 'Kagawad'),
(19, 'jolina', '6772ba1670c30.png', 'Kagawad');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `zone` int(11) NOT NULL,
  `mothers_name` varchar(100) NOT NULL,
  `fathers_name` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Others') NOT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `citizenship` enum('Filipino','Dual','Others') NOT NULL,
  `house_number` varchar(50) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `work` varchar(255) DEFAULT NULL,
  `is_student` enum('Yes','No') NOT NULL,
  `school` varchar(255) DEFAULT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `married` enum('Yes','No') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `contact_number`, `password`, `zone`, `mothers_name`, `fathers_name`, `birthday`, `profile_picture`, `gender`, `spouse_name`, `citizenship`, `house_number`, `street`, `work`, `is_student`, `school`, `degree`, `married`) VALUES
(1, 'RHOYPABLO1', 'mama@gmail.com', '09911838151', '$2y$10$HGjK7F07Rhy9303624zzG.ww2XBuw2gCNHlr3ciGgRqEWfa5P7eIi', 7, 'maria', 'rogelio', '2002-12-13', NULL, 'Male', NULL, 'Filipino', NULL, NULL, NULL, 'Yes', NULL, NULL, 'Yes'),
(2, 'anne jelica pablo', 'anne@gmail.com', '09911838151', '$2y$10$pMs9it0zQrcs3NP1XVbvz.H5A4PWJdmHKV2s8ZC6n5GwUR0MXVtsu', 7, 'MARIA ANGELA PABLO', 'ROGELIO PABLO', '2004-03-20', 'uploads/438669863_1099536717920062_7386222566725298874_n.jpg', 'Female', '', 'Filipino', '3', 'PROVINCIAL ROAD', '', 'Yes', 'UCV', 'BS CRIM', 'No'),
(3, 'rhoydz', 'rhoy', '09911838151', '$2y$10$y1ZE52YutSEQOk6zg4/jK.LNCS6VK8y5NzjHuNYpsxY7ISEjcBSt.', 7, 'maria angela pablo', 'rogelio pablo', '2024-11-11', NULL, 'Male', NULL, 'Filipino', NULL, NULL, NULL, 'Yes', NULL, NULL, 'Yes'),
(6, 'JANNA MAE FACUN', 'rhoydeleonpablo001@gmail.com', '09911838151', '$2y$10$qagUMZ3JRnEDoOuCrrr49u5O2qqAhhNdTgEctx84Oj1.gMUIq2fy2', 5, 'MARIA ANGELA PABLO', 'ROGELIO PABLO', '2006-10-20', NULL, 'Male', NULL, 'Filipino', NULL, NULL, NULL, 'Yes', NULL, NULL, 'Yes'),
(7, 'PABLO RHOY JUDIEL DE LEON', 'rhoydeleonpablo01@gmail.com', '09991183815', '$2y$10$NwrbCjUBuOh8pCb2u3qjFear/vDcC8FY540mfOMWD2ednSkMHTa4.', 7, 'MARIA ANGELA PABLO', 'ROGELIO PABLO', '2002-02-13', 'uploads/366320471_1391833904727651_687903784772343290_n.jpg', '', 'janna', '', '3', 'PROVINCIAL ROAD', '', 'Yes', 'AMA UNIVERSITY', 'BS IT ', ''),
(8, 'FACUN JANNA MAE L.', 'facunjannamae@gmail.com', '09911838151', '$2y$10$ng0h5VgAJ6TrhO0/JBS1ZuR8xZP/4Inl5CYoazG8xAUi3weNv56By', 4, 'AWDADWADA', 'ADAWDDADA', '2006-10-20', 'uploads/004.webp', '', 'rhoy', '', '3', 'PROVINCIAL ROAD', '', 'Yes', 'CSU CARIG', 'BS IT ', ''),
(9, '', 'user1', '', '$2y$10$cwy8jdglBgSjiJppfC0Oe.K30F3zEsSNMUxJhMDO8zSNwYb/AslqC', 0, '', '', '2020-02-20', NULL, 'Male', NULL, 'Filipino', NULL, NULL, NULL, 'Yes', NULL, NULL, 'Yes'),
(11, 'TESTING ACCOUNT', 'testing@gmail.com', '09911838151', '$2y$10$1ptQZT5mfHiCunEH6EUHZ.DcMEJpTHnBlb947gAoSRHoMkwTcUz4q', 6, 'TESTING MOTHERS', 'TESTING FATHER', '2002-02-20', 'uploads/profilepicture-2-portrait-head.jpg', '', 'testing ', '', '3', 'PROVINCIAL ROAD', '', 'Yes', 'AMA UNIVERSITY', 'BS IT ', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificate_indigency`
--
ALTER TABLE `certificate_indigency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_staff`
--
ALTER TABLE `tbl_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificate_indigency`
--
ALTER TABLE `certificate_indigency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_staff`
--
ALTER TABLE `tbl_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
