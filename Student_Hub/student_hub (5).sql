-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Jan 15, 2025 at 07:55 PM
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
-- Database: `student_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CommentID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `CommentText` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`CommentID`, `UserID`, `PostID`, `CommentText`, `CreatedAt`) VALUES
(1, 10, 10, 'good', '2025-01-14 12:13:34'),
(7, 10, 5, 'good', '2025-01-14 13:08:22'),
(8, 10, 10, '', '2025-01-15 16:20:04'),
(9, 10, 10, '', '2025-01-15 16:20:05'),
(10, 10, 10, '', '2025-01-15 16:20:10'),
(11, 10, 8, 'nice', '2025-01-15 16:23:48'),
(12, 10, 8, '', '2025-01-15 16:23:51'),
(13, 10, 8, '', '2025-01-15 16:24:05'),
(14, 10, 8, 'hbdjfsd', '2025-01-15 16:24:47'),
(15, 10, 8, '1234', '2025-01-15 16:28:11'),
(16, 10, 10, 'jfjbakdj', '2025-01-15 18:09:19');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `ComplaintID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Subject` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `Status` enum('Pending','Resolved') DEFAULT 'Pending',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `EventID` int(11) NOT NULL,
  `EventName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `EventDate` date NOT NULL,
  `OrganizerID` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `OrganizerContact` varchar(100) DEFAULT NULL,
  `EventImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`EventID`, `EventName`, `Description`, `EventDate`, `OrganizerID`, `CreatedAt`, `OrganizerContact`, `EventImage`) VALUES
(1, 'CSE FEST', 'hdgbfskdfhbwds', '2025-10-10', 11, '2025-01-14 14:40:24', '01752427858', 'uploads/Screenshot 2025-01-10 040019.png'),
(2, 'EEE FEST', 'ueufhsdkjfbsoudkfbw', '2025-12-10', 10, '2025-01-14 14:44:35', '01752427858', 'uploads/Screenshot (85).png');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `LikeID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`LikeID`, `UserID`, `PostID`, `CreatedAt`) VALUES
(2, 10, 4, '2025-01-14 12:13:18'),
(3, 10, 4, '2025-01-14 12:21:43'),
(4, 10, 4, '2025-01-14 12:31:26'),
(5, 10, 4, '2025-01-14 12:32:20'),
(12, 10, 5, '2025-01-14 13:09:39'),
(18, 11, 10, '2025-01-14 14:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `lunchbox`
--

CREATE TABLE `lunchbox` (
  `LunchID` int(11) NOT NULL,
  `MenuName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `seller_id` int(100) NOT NULL,
  `buyer_id` int(100) NOT NULL,
  `VendorName` varchar(100) DEFAULT NULL,
  `Availability` enum('Available','Out of Stock') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketplace`
--

CREATE TABLE `marketplace` (
  `ItemID` int(11) NOT NULL,
  `ItemName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `SellerID` int(11) DEFAULT NULL,
  `BuyerID` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `ImagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marketplace`
--

INSERT INTO `marketplace` (`ItemID`, `ItemName`, `Description`, `Price`, `SellerID`, `BuyerID`, `CreatedAt`, `ImagePath`) VALUES
(1, 'Arduino Kit', 'Basic electronics hardware for projects', 1200.00, 1, NULL, '2025-01-09 13:21:55', NULL),
(2, 'Lemon', 'good', 20.00, 10, NULL, '2025-01-12 19:00:00', NULL),
(3, 'Camera', 'DSLR', 10000.00, 10, NULL, '2025-01-12 23:23:29', NULL),
(4, 'Camera', 'dslr', 20000.00, 1, NULL, '2025-01-13 20:55:32', 'Screenshot 2025-01-14 025134.png'),
(5, 'Camera', '45gy', 3.00, 1, NULL, '2025-01-13 20:59:00', 'Screenshot 2025-01-14 011356.png'),
(6, 'Camera', '3rsgn134', 4542.00, 1, NULL, '2025-01-13 21:11:56', 'Screenshot 2025-01-14 025134.png'),
(7, 'Lemon', '131f', 34.00, 1, NULL, '2025-01-13 21:16:58', 'Screenshot 2025-01-14 025134.png'),
(8, 'Camera', 'dfcwfsvcf', 12.00, 1, NULL, '2025-01-13 21:18:04', 'Screenshot 2025-01-14 025134.png'),
(10, 'Camera', 'DSLR', 50000.00, 10, NULL, '2025-01-13 21:30:46', 'Screenshot 2025-01-14 025134.png');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `resource_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `questions` text DEFAULT NULL,
  `question_solution` text DEFAULT NULL,
  `books` text DEFAULT NULL,
  `books_solution` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roomreservations`
--

CREATE TABLE `roomreservations` (
  `ReservationID` int(11) NOT NULL,
  `RoomType` enum('Study','Program') NOT NULL,
  `RoomNumber` varchar(10) DEFAULT NULL,
  `ReservedBy` int(11) DEFAULT NULL,
  `ReservationDate` date NOT NULL,
  `Status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `SectionID` int(11) NOT NULL,
  `CourseCode` varchar(20) NOT NULL,
  `CourseName` varchar(100) NOT NULL,
  `Instructor` varchar(100) DEFAULT NULL,
  `Status` enum('Open','Closed') NOT NULL,
  `RequestedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`SectionID`, `CourseCode`, `CourseName`, `Instructor`, `Status`, `RequestedBy`) VALUES
(1, 'CSE101', 'Introduction to Programming', 'Dr. Smith', 'Closed', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` enum('Student','Faculty','Admin') NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `ProfilePicture` varchar(255) DEFAULT NULL,
  `Mobile` varchar(20) DEFAULT NULL,
  `Department` varchar(100) DEFAULT NULL,
  `Work` varchar(100) DEFAULT NULL,
  `University` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FullName`, `Email`, `PasswordHash`, `Role`, `CreatedAt`, `ProfilePicture`, `Mobile`, `Department`, `Work`, `University`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'hashed_password', 'Student', '2025-01-09 13:21:54', NULL, NULL, NULL, NULL, NULL),
(2, 'bijoy', 'b@gmail.com', '$2y$10$uX4.2XxDJizx/1D9hLnA2eYREXPNt3.Z41hI3iPlviYoCl1kbgj2e', 'Student', '2025-01-10 07:18:00', NULL, NULL, NULL, NULL, NULL),
(3, 'bijoy', 'bb@gmail.com', '$2y$10$lnCM.Rvxz7FBMKKSC0ArN.UBSH6PBN9357CxcFnUc5/T7WFpSow/q', 'Student', '2025-01-10 11:25:29', NULL, NULL, NULL, NULL, NULL),
(6, 'oyon', 'oyon@gmail.com', '$2y$10$sFFcNkpg8.i59qZNiCZmc.AzNRZpbFBXU090/ldaq.QheUt6/s7vy', 'Student', '2025-01-10 11:56:56', NULL, NULL, NULL, NULL, NULL),
(7, 'oyon', 'riad@gmail.com', '$2y$10$eHommThsbuCIzEIQv1/6VuPR4nc1DFzuPSBYDDkWcskWZuPzwtk/O', 'Student', '2025-01-10 11:57:08', NULL, NULL, NULL, NULL, NULL),
(9, 'sharier', 's@gmail.com', '$2y$10$H8Er3/3Jk78feDzsFz9JFuRnADtJB0dCIjhMGdDGsJvDldONiS15.', 'Student', '2025-01-10 14:38:22', NULL, NULL, NULL, NULL, NULL),
(10, 'Md Bijoy Hossain', 'bijoy@gmail.com', '$2y$10$N1VECnWE7DjAE3XNjF.UB.DikCooDuj2C/OuXTUcqrV73.UfEesGC', 'Student', '2025-01-11 08:47:41', 'profile_pic/67856ba1f1e40_Screenshot (32).png', '01753111897', 'CSE', 'Student', 'United International University'),
(11, 'tamim', 'tamim@gmail.com', '$2y$10$y7Nt8qVGVKSc/K9w82Z.lOXGhqki1aWq1CIYmn8RX4a3uHevwWQlq', 'Student', '2025-01-14 14:09:20', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PostID` (`PostID`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`ComplaintID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`EventID`),
  ADD KEY `OrganizerID` (`OrganizerID`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`LikeID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `PostID` (`PostID`);

--
-- Indexes for table `lunchbox`
--
ALTER TABLE `lunchbox`
  ADD PRIMARY KEY (`LunchID`),
  ADD KEY `lunchbox_ibfk_1` (`seller_id`),
  ADD KEY `lunchbox_ibfk_2` (`buyer_id`);

--
-- Indexes for table `marketplace`
--
ALTER TABLE `marketplace`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `SellerID` (`SellerID`),
  ADD KEY `BuyerID` (`BuyerID`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roomreservations`
--
ALTER TABLE `roomreservations`
  ADD PRIMARY KEY (`ReservationID`),
  ADD KEY `ReservedBy` (`ReservedBy`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`SectionID`),
  ADD KEY `RequestedBy` (`RequestedBy`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `ComplaintID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `lunchbox`
--
ALTER TABLE `lunchbox`
  MODIFY `LunchID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketplace`
--
ALTER TABLE `marketplace`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roomreservations`
--
ALTER TABLE `roomreservations`
  MODIFY `ReservationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `SectionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`PostID`) REFERENCES `marketplace` (`ItemID`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`OrganizerID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`PostID`) REFERENCES `marketplace` (`ItemID`);

--
-- Constraints for table `lunchbox`
--
ALTER TABLE `lunchbox`
  ADD CONSTRAINT `lunchbox_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `lunchbox_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `marketplace`
--
ALTER TABLE `marketplace`
  ADD CONSTRAINT `marketplace_ibfk_1` FOREIGN KEY (`SellerID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `marketplace_ibfk_2` FOREIGN KEY (`BuyerID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `roomreservations`
--
ALTER TABLE `roomreservations`
  ADD CONSTRAINT `roomreservations_ibfk_1` FOREIGN KEY (`ReservedBy`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`RequestedBy`) REFERENCES `users` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
