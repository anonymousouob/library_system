-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-12-11 14:27:11
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `library_system`
--

-- --------------------------------------------------------

--
-- 資料表結構 `book`
--

CREATE TABLE `book` (
  `BookID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Author` varchar(255) NOT NULL,
  `Publisher` varchar(255) NOT NULL,
  `PublicationYear` int(11) DEFAULT NULL,
  `Genre` varchar(100) NOT NULL,
  `ImagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `book`
--

INSERT INTO `book` (`BookID`, `Title`, `Author`, `Publisher`, `PublicationYear`, `Genre`, `ImagePath`) VALUES
(1, 'DataBase', 'A', 'B', 2025, 'Comp.sci', 'images/693ac616c5607.jpg'),
(2, '微積分', '許玉平', '高立圖書', 2024, 'Comp.sci', 'images/693ac66343c12.jpg'),
(3, 'capoo', 'capoo', 'capoo', 2025, 'comic', 'images/693ac678676ee.jpg');

-- --------------------------------------------------------

--
-- 資料表結構 `copy`
--

CREATE TABLE `copy` (
  `CopyID` int(11) NOT NULL,
  `BookID` int(11) NOT NULL,
  `ShelfLocation` varchar(50) NOT NULL,
  `Status` enum('Available','On Loan','Lost','Damaged') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `copy`
--

INSERT INTO `copy` (`CopyID`, `BookID`, `ShelfLocation`, `Status`) VALUES
(101, 1, 'A-1-01', 'Available'),
(102, 2, 'New-Arr', 'Available'),
(103, 3, 'New-Arr', 'Available');

-- --------------------------------------------------------

--
-- 資料表結構 `failed_attempts`
--

CREATE TABLE `failed_attempts` (
  `id` int(11) NOT NULL,
  `account` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `loan`
--

CREATE TABLE `loan` (
  `LoanID` int(11) NOT NULL,
  `CopyID` int(11) NOT NULL,
  `MemberID` int(11) NOT NULL,
  `LoanDate` date NOT NULL DEFAULT curdate(),
  `DueDate` date NOT NULL,
  `ReturnDate` date DEFAULT NULL,
  `Status` enum('On Loan','Returned','Overdue') DEFAULT 'On Loan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `account` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `result` enum('success','fail','blocked') NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `login_logs`
--

INSERT INTO `login_logs` (`id`, `account`, `ip`, `result`, `message`, `created_at`) VALUES
(1, 'admin', '::1', 'success', '登入成功', '2025-12-11 21:13:51'),
(2, 'zzz', '::1', 'success', '登入成功', '2025-12-11 21:27:00');

-- --------------------------------------------------------

--
-- 資料表結構 `member`
--

CREATE TABLE `member` (
  `MemberID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Account` varchar(255) DEFAULT NULL,
  `MemberType` enum('Reader','Master') DEFAULT 'Reader',
  `RegistrationDate` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- 傾印資料表的資料 `member`
--

INSERT INTO `member` (`MemberID`, `Username`, `Password`, `Account`, `MemberType`, `RegistrationDate`) VALUES
(2, 'admin', '$2y$10$P6B4JG3jSRTNfp/tqQGLReG64a30E9g2seFDkzMzOtddGjLrm5.DK', 'Administrator', 'Master', '2025-12-11'),
(3, 'zzz', '$2y$10$a3y9xVrqyrQGBz0SfGiJde/pKYcKEp4BLHUCwvYiOav3F7utZyWUW', 'zzz', 'Reader', '2025-12-11');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`BookID`);

--
-- 資料表索引 `copy`
--
ALTER TABLE `copy`
  ADD PRIMARY KEY (`CopyID`),
  ADD KEY `BookID` (`BookID`);

--
-- 資料表索引 `failed_attempts`
--
ALTER TABLE `failed_attempts`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`LoanID`),
  ADD KEY `CopyID` (`CopyID`),
  ADD KEY `MemberID` (`MemberID`);

--
-- 資料表索引 `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`MemberID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Account` (`Account`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `book`
--
ALTER TABLE `book`
  MODIFY `BookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `copy`
--
ALTER TABLE `copy`
  MODIFY `CopyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `failed_attempts`
--
ALTER TABLE `failed_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `loan`
--
ALTER TABLE `loan`
  MODIFY `LoanID` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `member`
--
ALTER TABLE `member`
  MODIFY `MemberID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `copy`
--
ALTER TABLE `copy`
  ADD CONSTRAINT `copy_ibfk_1` FOREIGN KEY (`BookID`) REFERENCES `book` (`BookID`) ON UPDATE CASCADE;

--
-- 資料表的限制式 `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`CopyID`) REFERENCES `copy` (`CopyID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_ibfk_2` FOREIGN KEY (`MemberID`) REFERENCES `member` (`MemberID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
