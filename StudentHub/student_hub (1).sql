-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Jan 10, 2025 at 03:58 PM
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
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lunchbox`
--

CREATE TABLE `lunchbox` (
  `LunchID` int(11) NOT NULL,
  `MenuName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
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
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marketplace`
--

INSERT INTO `marketplace` (`ItemID`, `ItemName`, `Description`, `Price`, `SellerID`, `BuyerID`, `CreatedAt`) VALUES
(1, 'Arduino Kit', 'Basic electronics hardware for projects', 1200.00, 1, NULL, '2025-01-09 13:21:55');

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
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
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
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FullName`, `Email`, `PasswordHash`, `Role`, `CreatedAt`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'hashed_password', 'Student', '2025-01-09 13:21:54'),
(2, 'bijoy', 'b@gmail.com', '$2y$10$uX4.2XxDJizx/1D9hLnA2eYREXPNt3.Z41hI3iPlviYoCl1kbgj2e', 'Student', '2025-01-10 07:18:00'),
(3, 'bijoy', 'bb@gmail.com', '$2y$10$lnCM.Rvxz7FBMKKSC0ArN.UBSH6PBN9357CxcFnUc5/T7WFpSow/q', 'Student', '2025-01-10 11:25:29'),
(6, 'oyon', 'oyon@gmail.com', '$2y$10$sFFcNkpg8.i59qZNiCZmc.AzNRZpbFBXU090/ldaq.QheUt6/s7vy', 'Student', '2025-01-10 11:56:56'),
(7, 'oyon', 'riad@gmail.com', '$2y$10$eHommThsbuCIzEIQv1/6VuPR4nc1DFzuPSBYDDkWcskWZuPzwtk/O', 'Student', '2025-01-10 11:57:08'),
(9, 'sharier', 's@gmail.com', '$2y$10$H8Er3/3Jk78feDzsFz9JFuRnADtJB0dCIjhMGdDGsJvDldONiS15.', 'Student', '2025-01-10 14:38:22');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `lunchbox`
--
ALTER TABLE `lunchbox`
  ADD PRIMARY KEY (`LunchID`);

--
-- Indexes for table `marketplace`
--
ALTER TABLE `marketplace`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `SellerID` (`SellerID`),
  ADD KEY `BuyerID` (`BuyerID`);

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
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `ComplaintID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lunchbox`
--
ALTER TABLE `lunchbox`
  MODIFY `LunchID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketplace`
--
ALTER TABLE `marketplace`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `marketplace`
--
ALTER TABLE `marketplace`
  ADD CONSTRAINT `marketplace_ibfk_1` FOREIGN KEY (`SellerID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `marketplace_ibfk_2` FOREIGN KEY (`BuyerID`) REFERENCES `users` (`UserID`);

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
