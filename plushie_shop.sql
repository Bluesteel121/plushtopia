-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 12:18 PM
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
-- Database: `plushie_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customerID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `item_id`, `quantity`, `created_at`, `customerID`) VALUES
(6, NULL, 1, 1, '2024-12-17 08:30:16', 1),
(7, NULL, 1, 1, '2024-12-17 08:37:33', 1),
(8, NULL, 1, 1, '2024-12-17 09:56:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(1, 'Animals', 'Cute, soft and fluffy stuffed animal toys.', 0, 1, 0, 0, 0),
(2, 'Characters', 'Favorite fictional characters from your favorite shows.', 0, 1, 0, 0, 0),
(3, 'Foods', 'Cute and adorable food plushies.', 0, 1, 0, 0, 0),
(4, 'Seasonal', 'Seasonal plushies for different holiday seasons', 0, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerID` int(11) UNSIGNED NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerID`, `Username`, `Password`, `Email`, `FullName`) VALUES
(1, 'cezar', '06e9b16c74f676285758b7d137ba61e14fb0a6f6', 'cezar@gmail.com', 'Cezar Bernandino'),
(2, 'TestOnly', '8574c57e78b6f27d1195a79cc259a32d36348393', 'testonly@gmail.com', 'test only'),
(4, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(5, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(6, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(7, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(8, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(9, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(10, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(11, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(12, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(13, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(14, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(15, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(16, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(17, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(18, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User'),
(19, 'Guest', 'guestpassword', 'guest@example.com', 'Guest User');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Stock_quantity` int(10) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `Cat_ID` int(11) DEFAULT NULL,
  `Member_ID` int(11) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Stock_quantity`, `Add_Date`, `Country_Made`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `picture`, `contact`) VALUES
(1, 'Fluffy Owl', 'A plushie made out of high quality materials. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '350', 91, '2020-08-28', 'China', '1', 0, 1, 1, 2, 'owl.png', ''),
(2, 'Humpty Donkey', 'A plushie made out of high quality materials. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '30', 98, '2020-08-28', 'USA', '1', 0, 1, 1, 2, 'donkey.png', ''),
(4, 'SpongeBob in a cute bunny suit.', 'Squishy stuffed toy designed for Spongebob lovers. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '800', 90, '2020-08-28', 'Philippines', '2', 0, 1, 2, 2, 'spongebob.png', '09304071594'),
(5, 'Soft and Bouncy Penguin', 'A plushie made out of high quality materials. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '152', 99, '2020-08-28', 'Taiwan', '4', 0, 1, 1, 2, 'penguin.png', '09304071594'),
(16, 'Cute & Squishy Pikachu', 'Squishy stuffed toy specially made for Pokemon fans. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '870', 100, '2020-08-28', 'Japan', '2', 0, 1, 2, 2, 'pokemon.png', '09304071594'),
(17, 'Tom and Jerry ', 'Squishy stuffed toy specially made for Tom&Jerry lovers. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '580', 100, '2020-08-28', 'Japan', '2', 0, 1, 2, 2, 'tomandjerry.png', '09304071594'),
(18, ' Tomato Plushie', 'Cute and squishy tomato stuffed toy specially created for all the tomato-heads out there. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '300', 100, '2020-08-28', 'Philippines', '2', 0, 1, 3, 2, 'tomato.png', '09763846558'),
(19, 'Peas in a Pod Cute Stuffed-toy', 'Three cute and squishy little stuffed peas put together in  pod specially created for all the pea-heads out there. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '645', 100, '2020-08-28', 'USA', '2', 0, 1, 3, 2, 'peas.png', '09763846558'),
(20, 'Watermelon Stuffed-toy', 'A cute and squishy watermelon specially created for all watermelon lovers. It is made with high quality materials which makes it durable and strong. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '500', 100, '2020-08-28', 'USA', '2', 0, 1, 3, 2, 'watermelon.png', '09763846558'),
(21, 'Santa Claus cute and soft plushie', 'A perfect toy for Christmas seasons- A cute and squishy Santa Claus stuffed toy. It is made with high quality materials which makes it durable and strong. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '1000', 100, '2020-08-28', 'Taiwan', '2', 0, 1, 4, 2, 'santa.png', '09763846558'),
(22, 'Spider Plushie for Holloween ', 'A perfect toy for Holloween seasons- A cute and squishy spider stuffed toy. It is made with high quality materials which makes it durable and strong. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '1000', 100, '2020-08-28', 'Taiwan', '2', 0, 1, 4, 2, 'holloween.png', '09763846558'),
(23, 'Teddy Bear for Valentine\'s Day', 'A perfect gift for Valentine\'s seasons- A cute and squishy bear stuffed toy. It is made with high quality materials which makes it durable and strong. Crafted with ultra-soft, hypoallergenic polyester fabric, it features a velvety exterior that\'s gentle on sensitive skin. The inner filling is made from 100% recycled polyester fibers, providing a plush yet durable feel. Embroidered facial details ensure safety and a charming, high-quality finish.', '1500', 100, '2020-08-28', 'USA', '2', 0, 1, 4, 2, 'valentines.png', '09763846558');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `Order_Item_ID` int(11) NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 1,
  `Price` decimal(10,2) NOT NULL,
  `shipping` varchar(100) NOT NULL,
  `delivery` varchar(100) NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contactNumber` varchar(20) NOT NULL,
  `customerID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`Order_Item_ID`, `Item_ID`, `Quantity`, `Price`, `shipping`, `delivery`, `recipient_name`, `address`, `contactNumber`, `customerID`) VALUES
(3, 1, 1, 350.00, 'standard', 'home', 'cezar bernandino', '123 barangay 2', '1223456', 0),
(4, 16, 1, 870.00, 'standard', 'home', 'cezar bernandino', '123 barangay 2', '1223456', 0),
(12, 1, 1, 350.00, 'standard', 'home', 'xxx', 'xxx', 'xxxx', 4),
(13, 2, 1, 30.00, 'standard', 'home', 'ccc', 'ccc', 'cccc', 5),
(14, 2, 1, 30.00, 'standard', 'home', 'ooo', 'ooo', 'ooo', 6),
(15, 5, 1, 152.00, 'standard', 'home', 'cc', 'cc', 'cc', 7),
(16, 4, 1, 800.00, 'standard', 'home', 'vv', 'vv', 'vv', 8),
(17, 5, 1, 152.00, 'standard', 'home', 'qq', 'qq', 'qq', 9),
(18, 2, 1, 30.00, 'standard', 'home', 'jjj', 'jjjj', 'jjj', 10),
(19, 2, 1, 30.00, 'standard', 'home', 'mm', 'mm', 'mm', 11),
(20, 4, 4, 800.00, 'standard', 'home', 'pppp', 'pppp', 'pppp', 12),
(21, 5, 1, 152.00, 'standard', 'home', 'mm', 'mmm', 'mm', 13),
(22, 19, 1, 645.00, 'standard', 'home', 'bb', 'bb', 'bb', 14),
(23, 1, 5, 350.00, 'standard', 'home', 'cezar bernandino', '123 barangay 2', '1223456', 15),
(24, 1, 1, 350.00, 'standard', 'home', 'jjj', 'jjj', 'jj', 16),
(25, 5, 1, 152.00, 'standard', 'home', 'mmm', 'mmm', 'mmm', 17),
(26, 1, 1, 350.00, 'standard', 'home', 'vvv', 'vvvvv', 'vvv', 18),
(27, 1, 2, 350.00, 'standard', 'home', 'vv', 'vv', 'vv', 19),
(28, 4, 10, 800.00, 'standard', 'home', 'mm', 'mm', 'mmm', 1),
(29, 2, 1, 30.00, 'standard', 'home', 'mm', 'mm', 'mmm', 1),
(30, 2, 1, 30.00, 'standard', 'home', 'mm', 'mm', 'mmm', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewID` int(11) NOT NULL,
  `stars` tinyint(4) NOT NULL CHECK (`stars` between 1 and 5),
  `comment` text DEFAULT NULL,
  `comment_date` datetime DEFAULT current_timestamp(),
  `item_id` int(11) NOT NULL,
  `customerID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewID`, `stars`, `comment`, `comment_date`, `item_id`, `customerID`) VALUES
(1, 5, 'nice', '2024-12-17 16:56:46', 1, 1),
(2, 5, 'nice', '2024-12-17 16:58:23', 1, 1),
(3, 2, 'so fluffy', '2024-12-17 17:11:23', 1, 1),
(4, 5, 'NICE', '2024-12-17 17:18:00', 2, 1),
(5, 3, 'ccc', '2024-12-17 18:56:15', 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To Identify User',
  `Username` varchar(255) NOT NULL COMMENT 'Username To Login',
  `Password` varchar(255) NOT NULL COMMENT 'Password To Login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT 0 COMMENT 'Identify User Group',
  `TrustStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'Seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'User Approval',
  `Date` date NOT NULL,
  `avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `customerID` (`customerID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `Cat_ID` (`Cat_ID`),
  ADD KEY `Member_ID` (`Member_ID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`Order_Item_ID`),
  ADD KEY `Item_ID` (`Item_ID`),
  ADD KEY `fk_order_items` (`customerID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `customerID` (`customerID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `Order_Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `categories` (`ID`) ON DELETE SET NULL;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`Item_ID`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
