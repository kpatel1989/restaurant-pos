-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2013 at 02:30 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `restaurantpos`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `parent_id` varchar(3) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `title`, `parent_id`, `image`) VALUES
(1, 'Salad', 'new salads', '2', 'upload/category/1.jpg'),
(2, 'Starters', 'task2title', '0', 'upload/category/2.jpg'),
(3, 'South Indian', 'Best South Indian dishes here...', '0', 'upload/category/3.jpg'),
(4, 'Uttapam', 'task2title4', '3', 'upload/category/4.jpg'),
(5, 'Dosa', 'task1title4', '3', 'upload/category/5.jpg'),
(6, 'Beverages', 'fasdasd', '0', 'upload/category/6.jpg'),
(7, 'Papad', 'new papads', '2', 'upload/category/7.jpg'),
(9, 'Soups', 'all soups available', '2', 'upload/category/9.jpg'),
(10, 'Idli', 'asdasd', '3', 'upload/category/10.jpg'),
(11, 'Meals', 'asdasd', '3', 'upload/category/11.jpg'),
(12, 'Ice Cream', 'all soups available', '6', 'upload/category/12.jpg'),
(13, 'Chinese', 'new chinese', '0', 'upload/category/13.jpg'),
(14, 'new cat', 'new chinese', '8', 'upload/category/14.jpg'),
(15, 'noodles', 'tasty noodles', '13', 'upload/category/15.jpg'),
(16, 'manchurian', 'spicy manchurians', '13', 'upload/category/16.jpg'),
(17, 'punjabi', 'asd', '0', 'upload/category/17.jpg'),
(18, 'kajd', 'asd', '8', 'upload/category/18.jpg'),
(20, 'pulav', 'asd', '13', 'upload/category/20.jpg'),
(21, 'Fast Food', 'new fast foods', '0', 'upload/category/21'),
(22, 'Sandwiches', 'all new types of sandwiches', '21', 'upload/category/22'),
(23, 'Burgers', 'new tasty burgers', '21', 'upload/category/23');

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `DeviceID` int(3) NOT NULL,
  `DeviceName` varchar(50) NOT NULL,
  `WaiterId` int(3) DEFAULT NULL,
  `TableId` int(3) DEFAULT NULL,
  `Notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`DeviceID`),
  UNIQUE KEY `WaiterId_2` (`WaiterId`,`TableId`),
  UNIQUE KEY `WaiterId_3` (`WaiterId`,`TableId`),
  KEY `WaiterId` (`WaiterId`),
  KEY `TableId` (`TableId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`DeviceID`, `DeviceName`, `WaiterId`, `TableId`, `Notes`) VALUES
(1, 'device03', 2, NULL, 'sukrut is very lazy'),
(2, 'device02', 1, NULL, 'akshat is good at waitering services'),
(3, 'device05', NULL, 3, ''),
(4, 'device04', NULL, NULL, 'dasdasdasd'),
(5, 'device01', NULL, 1, 'asdasd'),
(6, 'device06', NULL, 6, '');

-- --------------------------------------------------------

--
-- Table structure for table `devicelog`
--

CREATE TABLE IF NOT EXISTS `devicelog` (
  `DeviceLogId` int(3) NOT NULL,
  `DeviceId` int(3) DEFAULT NULL,
  `TimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `WaiterId` int(3) DEFAULT NULL,
  `TableId` int(3) DEFAULT NULL,
  PRIMARY KEY (`DeviceLogId`),
  KEY `DeviceLogId` (`DeviceLogId`),
  KEY `DeviceId` (`DeviceId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `devicelog`
--

INSERT INTO `devicelog` (`DeviceLogId`, `DeviceId`, `TimeStamp`, `WaiterId`, `TableId`) VALUES
(1, 2, '2013-04-25 20:11:02', 1, 3),
(2, 2, '2013-04-26 05:33:06', 1, 4),
(3, 2, '2013-04-26 05:34:18', 1, 6),
(4, 2, '2013-04-26 11:30:46', 1, 1),
(5, 2, '2013-04-26 17:21:27', 1, 2),
(6, 2, '2013-04-26 17:21:38', 1, 4),
(7, 2, '2013-04-26 17:21:54', 1, 8),
(8, 2, '2013-04-27 13:54:02', 1, 1),
(9, 2, '2013-04-27 13:54:18', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE IF NOT EXISTS `invoice` (
  `InvoiceId` int(3) NOT NULL,
  `OrderId` int(3) DEFAULT NULL,
  `CustomerName` varchar(50) DEFAULT NULL,
  `CustomerAddress` varchar(50) DEFAULT NULL,
  `CreatedTime` datetime DEFAULT NULL,
  `AdminUserDetailId` int(3) DEFAULT NULL,
  PRIMARY KEY (`InvoiceId`),
  KEY `OrderId` (`OrderId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`InvoiceId`, `OrderId`, `CustomerName`, `CustomerAddress`, `CreatedTime`, `AdminUserDetailId`) VALUES
(1, 2, 'asd', 'asd', '2013-03-31 07:33:52', 0),
(2, 3, 'asdas', 'asdasd', '2013-03-31 08:03:57', 0),
(3, 4, 'asdasdasd', 'asd', '2013-04-04 06:35:11', 0),
(4, 5, 'Kartik Patel', 'Anand', '2013-04-05 19:32:56', 0),
(5, 1, 'asd', 'asd', '2013-04-09 15:31:45', 0),
(6, 15, 'sukrut', 'baroda', '2013-04-23 18:25:30', 1),
(7, 16, 'dalpat', 'porbandar', '2013-04-23 18:26:24', 1),
(8, 17, 'asd', 'asd', '2013-04-24 06:18:55', 14),
(9, 19, 'sukrut', 'baroda', '2013-04-24 07:43:48', 14),
(10, 20, 'asd', 'asd', '2013-04-26 07:28:31', 14),
(11, 21, 'asd', 'asd', '2013-04-26 11:54:11', 14),
(12, 22, 'asd', 'asd', '2013-04-26 12:16:32', 14),
(13, 23, 'asd', 'asd', '2013-04-26 12:17:48', 14),
(14, 24, 'asd', 'asd', '2013-04-26 12:29:43', 14),
(15, 25, 'asd', 'asd', '2013-04-26 12:32:16', 14),
(19, 18, 'asd', 'sad', '2013-04-26 18:22:03', 14),
(20, 33, 'asd', 'asd', '2013-04-26 19:27:32', 14),
(21, 39, 'asd', 'asd', '2013-04-27 13:39:17', 14),
(22, 40, 'as', 'dasd', '2013-04-27 13:49:02', 14),
(23, 41, 'asd', 'asd', '2013-04-27 13:50:39', 14),
(24, 42, 'asd', 'asd', '2013-04-27 14:05:50', 14),
(25, 44, 'asd', 'asd', '2013-04-27 14:17:55', 14);

-- --------------------------------------------------------

--
-- Table structure for table `invoiceitem`
--

CREATE TABLE IF NOT EXISTS `invoiceitem` (
  `InvoiceItemId` int(3) NOT NULL,
  `InvoiceId` int(3) DEFAULT NULL,
  `ItemName` varchar(50) DEFAULT NULL,
  `Quantity` int(3) DEFAULT NULL,
  `Amount` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`InvoiceItemId`),
  KEY `InvoiceId` (`InvoiceId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoiceitem`
--

INSERT INTO `invoiceitem` (`InvoiceItemId`, `InvoiceId`, `ItemName`, `Quantity`, `Amount`) VALUES
(1, 1, 'grilled sandich', 3, '20.00'),
(2, 1, 'Samosa Sandwich', 11, '30.00'),
(3, 1, 'asd', 2, '23.00'),
(4, 2, 'asd', 2, '23.00'),
(5, 2, 'aasd', 2, '123.00'),
(6, 3, 'asd', 2, '23.00'),
(7, 4, 'aasd', 1, '123.00'),
(8, 5, 'casata', 3, '30.00'),
(9, 5, 'Samosa Sandwich', 5, '110.00'),
(10, 6, 'aasd', 1, '123.00'),
(11, 7, 'grilled sandich', 3, '20.00'),
(12, 8, 'Mysore Masala Dosa', 3, '23.00'),
(13, 9, 'tricone', 2, '10.00'),
(14, 10, 'Fire-Grilled Southwest Cobb', 3, '40.00'),
(15, 10, 'Pasta', 2, '13.00'),
(16, 11, 'Fire-Grilled Southwest Cobb', 2, '40.00'),
(17, 12, 'Fire-Grilled Southwest Cobb', 1, '40.00'),
(18, 13, 'Fire-Grilled Southwest Cobb', 1, '40.00'),
(19, 14, 'Fire-Grilled Southwest Cobb', 2, '40.00'),
(20, 15, 'Fire-Grilled Southwest Cobb', 2, '40.00'),
(21, 15, 'Pasta', 1, '13.00'),
(26, 19, 'choco bar', 3, '10.00'),
(27, 20, 'Fire-Grilled Southwest Cobb', 2, '40.00'),
(28, 21, 'Fire-Grilled Southwest Cobb', 1, '40.00'),
(29, 22, 'Fire-Grilled Southwest Cobb', 1, '40.00'),
(30, 22, 'Pasta', 1, '13.00'),
(31, 22, 'Chesse papad', 2, '12.00'),
(32, 22, 'Blueberry Chicken Salad', 1, '45.00'),
(33, 23, 'Fire-Grilled Southwest Cobb', 1, '40.00'),
(34, 24, 'Fire-Grilled Southwest Cobb', 2, '40.00'),
(35, 24, 'Pasta', 2, '13.00'),
(36, 24, 'Hot n Sour', 1, '30.00'),
(37, 24, 'Blueberry Chicken Salad', 2, '45.00'),
(38, 25, 'Fire-Grilled Southwest Cobb', 1, '40.00');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(3) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `Category_id` int(3) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `ingredients` varchar(1100) DEFAULT NULL,
  `preperation` varchar(2000) DEFAULT NULL,
  `price` decimal(6,2) DEFAULT NULL,
  `images` varchar(1000) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `approx_duration` varchar(100) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `Category_id` (`Category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `Category_id`, `description`, `ingredients`, `preperation`, `price`, `images`, `is_active`, `approx_duration`, `notes`) VALUES
(1, 'sandwich', 4, 'new sand', 'bread', 'asd', '10.00', ',upload/items/1_1.jpg,upload/items/1_2.jpg', 0, '10', 'asdasd'),
(2, 'grilled sandich', 4, 'asd', 'asd', 'asd', '20.00', ',upload/items/2_1.jpg,upload/items/2_2.jpg', 1, '20 min', 'awesome sandwich'),
(3, 'casata', 12, 'wow', 'cashew', 'asd', '35.00', '', 0, '45', 'asd'),
(4, 'tricone', 12, 'new tricone', 'choc', 'just do it', '10.00', ',upload/items/4_1.jpg,upload/items/4_2.jpg', 1, '2', 'wow'),
(6, 'choco bar', 12, 'chocolaty choco bar', 'chocolate and cream', 'i d k', '10.00', ',upload/items/6_1.jpg,upload/items/6_2.jpg', 1, '5', 'nice taste'),
(7, 'Samosa Sandwich', 4, 'samosa in sandwich', 'samosa and sandwich', 'put samosa in sandwich', '30.00', ',upload/items/7_1.jpg,upload/items/7_2.jpg', 1, '10 min', 'nice taste'),
(8, 'Fire-Grilled Southwest Cobb', 1, 'Crisp romaine lettuce topped with the bold flavors of roasted sweet corn, crumbled bleu cheese, smoked bacon, chopped egg, fire-grilled plum tomatoes, asparagus and grilled chicken. Served with chipotle honey mustard dressing.', 'Crisp romaine lettuce topped with the bold flavors of roasted sweet corn, crumbled bleu cheese, smoked bacon, chopped egg, fire-grilled plum tomatoes, asparagus and grilled chicken. Served with chipotle honey mustard dressing.', 'Crisp romaine lettuce topped with the bold flavors of roasted sweet corn, crumbled bleu cheese, smoked bacon, chopped egg, fire-grilled plum tomatoes, asparagus and grilled chicken. Served with chipotle honey mustard dressing.', '40.00', ',upload/items/8_1.jpg', 1, '20:min', 'very nice'),
(10, 'aasd', 4, 'asd', 'asd', 'asd', '13.00', '', 1, 'asd', 'asd'),
(12, 'Pasta', 1, 'new pasta', 'vacx', 'adc', '13.00', '', 1, '23', 'basd'),
(13, 'Chesse papad', 7, 'asdasd', 'asdasd', 'asdasd', '12.00', ',upload/items/13_4.jpg,upload/items/13_3.jpg,upload/items/13_1.jpg,upload/items/13_2.jpg', 1, '3.00 min', 'asd'),
(14, 'Sada dosa', 5, 'A dosa with a chatni and sambhar', 'dosa, chatni , sambhat', 'pour dosa liquid on tava. let it fry of some time the turn it and its ready', '25.00', '', 1, '2.00', ''),
(15, 'Paper Dosa', 5, 'asdasd', 'asdasd', 'asdasd', '40.00', '', 1, '23', ''),
(16, 'Rawa Dosa', 5, 'asdasd', 'asdasd', 'asdasd', '23.00', '', 1, '23', ''),
(17, 'Mysore Masala Dosa', 5, 'asdasd', 'asdasd', 'asdasd', '23.00', '', 1, '23', ''),
(18, 'Hakka noodles', 15, 'very spicy hakka noodles', 'noodles and masala', 'fry noodles in oil and add masala', '30.00', ',upload/items/18_1.jpg', 1, '15-20 minutes', 'very tasty and spicy'),
(19, 'dry manchurian', 16, 'new dry manchurian', 'manchurian pieces, and masala', 'fry manchurian pieces and add some masala', '50.00', ',upload/items/19_1.jpg', 1, '30', 'very spicy'),
(20, 'Hot n Sour', 9, 'asd', 'asd', 'asd', '30.00', ',upload/items/20_1.jpg,upload/items/20_2.jpg', 1, '10 min', 'asd'),
(21, 'Blueberry Chicken Salad', 1, 'A refreshing summertime favorite! Fresh greens and crisp celery are tossed in a poppyseed dressing and topped with fresh ripe strawberries and blueberries, grilled chicken, glazed pecans and bleu cheese crumbles.', 'Fresh greens and crisp celery are tossed in a poppyseed dressing and topped with fresh ripe strawberries and blueberries, grilled chicken, glazed pecans and bleu cheese crumbles.', 'A refreshing summertime favorite! Fresh greens and crisp celery are tossed in a poppyseed dressing and topped with fresh ripe strawberries and blueberries, grilled chicken, glazed pecans and bleu cheese crumbles.', '45.00', ',upload/items/21_1.jpg', 1, '13:00 min', 'Very Tasty...'),
(22, 'Creamy Vegetable Soup', 9, 'A wonderful combination of vegetables and white sauce.', 'French beans,finely chopped,Carrots,peeled and chopped,Green peas,boiled,Butter	,Refined flour (maida),Milk,Salt, Crushed black peppercorns', 'Heat butter in a non-stick pan, add refined flour and sautÃ© for a minute. Add French beans, carrots, green peas and sautÃ© for three to four minutes. Add three cups of water and cook till the vegetables are soft.\r\n\r\nAdd milk, salt and cook till the soup is thick. Add crushed peppercorns and mix well. Serve piping hot.', '30.00', ',upload/items/22_1.jpg,upload/items/22_2.jpg,upload/items/22_3.jpg,upload/items/22_4.jpg', 1, '15-20 minutes', ' '),
(23, 'Macaroni Soup With Tomatoes', 9, 'Tomatoes cooked with macaroni into a soup.', 'Macaroni,boiled,Tomatoes,chopped,Black pepper powder,Basil leaves,shredded,Tomato puree', 'Heat the butter in a deep non-stick pan. Add garlic and sautÃ© till soft. Add the onion and tomatoes, mix well and cook for five to eight minutes. Add tomato puree and mix well.\r\n\r\nAdd the vegetable stock, salt, black pepper powder, mix well and cook for ten to fifteen minutes. Add the macaroni and basil leaves, mix well and serve hot. ', '35.00', ',upload/items/23_1.jpg', 1, '12:00 min', ' '),
(24, 'Basil Almond Soup', 9, 'A rich almond soup with basil flavour.', 'Fresh basil leaves,chopped, Almond paste,Butter,Ginger-garlic paste,White pepper powder,Salt,Fresh cream', 'Heat butter in a non-stick pan, add ginger-garlic paste and sautÃ© till fragrant. Add almond paste and saute for half a minute.\r\n\r\nAdd white pepper powder, salt and five cups of water. Mix well and bring the mixture to a boil. Reduce heat and cook for two to three minutes. Add cream and basil leaves, mix well and simmer for two minutes. Serve hot with bread sticks. ', '40.00', ',upload/items/24_1.jpg', 1, '10-15 minutes', ' '),
(25, 'Sada Papad', 7, 'tasty papads', 'papad,', ' ', '10.00', ',upload/items/25_1.jpg,upload/items/25_2.jpg', 1, '1 min', ' '),
(26, 'Masala Papad', 7, 'Papad with some fresh vegetables cut on it.', 'papad, and vegetables', 'placing vegetable cuts on papad', '15.00', ',upload/items/26_1.jpg,upload/items/26_2.jpg', 1, '2 min', 'nice '),
(27, 'Masala Sandwich', 22, 'Tasty sandwich with masala', 'bread, potatoes masala, chatni', 'toast the sandwich in toaster with masala in it.', '25.00', ',upload/items/27_1.jpg,upload/items/27_2.jpg', 1, '10 min', 'very tasty'),
(28, 'Samosa sandwich', 22, 'new sandwich with samosa in it', 'bread, samosa, and chatni', 'toast the sandwich with samosa in it.', '35.00', ',upload/items/28_1.jpg,upload/items/28_2.jpg', 1, '10 min', 'very tasty..'),
(29, 'Allo tikki', 23, 'new allo tikki burger', 'burger, allo tikki', 'placing allo tikki in burger', '35.00', ',upload/items/29_1.jpg', 1, '2 min', 'nice');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `OrderId` int(3) NOT NULL,
  `UserDetailId` int(3) DEFAULT NULL,
  `TableId` int(3) DEFAULT NULL,
  `WaiterId` int(3) DEFAULT NULL,
  `CreatedTime` datetime DEFAULT NULL,
  `AcceptedTime` datetime DEFAULT NULL,
  `ServedTime` datetime DEFAULT NULL,
  `BilledTime` datetime DEFAULT NULL,
  `Status` int(3) DEFAULT NULL,
  `AdminUserDetailId` int(3) DEFAULT NULL,
  `Notes` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`OrderId`),
  KEY `UserDetailId` (`UserDetailId`),
  KEY `TableId` (`TableId`),
  KEY `WaiterId` (`WaiterId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`OrderId`, `UserDetailId`, `TableId`, `WaiterId`, `CreatedTime`, `AcceptedTime`, `ServedTime`, `BilledTime`, `Status`, `AdminUserDetailId`, `Notes`) VALUES
(1, 1, 1, NULL, '2013-03-20 11:25:46', '2013-04-05 19:31:25', '2013-03-20 11:25:46', '2013-04-09 15:34:05', 5, 0, '2-1*7-2*9-1*'),
(2, 1, 2, 1, '2013-03-20 11:26:44', '2013-03-20 11:26:44', '2013-03-20 11:26:44', '2013-03-20 11:26:44', 5, 0, '8-2*10-1*2-2*7-2*9-1*'),
(3, 1, 3, NULL, '2013-03-20 11:27:20', '2013-03-20 11:27:20', '2013-03-20 11:27:20', '2013-03-20 11:27:20', 5, 0, '8-2*10-3*'),
(4, 1, 8, 1, '2013-03-20 11:30:44', '2013-03-20 11:30:44', '2013-03-20 11:30:44', '2013-03-20 11:30:44', 5, 0, '8-2*10-3*'),
(5, 1, 5, 1, '2013-03-20 11:30:44', '2013-03-20 11:30:44', '2013-03-20 11:30:44', '2013-03-20 11:30:44', 5, 0, '8-2*10-3*'),
(6, 1, 5, 1, '2013-03-20 11:30:44', '2013-03-20 11:30:44', '2013-03-20 11:30:44', '2013-03-20 11:30:44', 5, 0, '8-2*10-3*'),
(7, 1, 3, NULL, '2013-03-22 11:37:43', '2013-03-22 11:37:43', '2013-03-22 11:37:43', '2013-03-22 11:37:43', 5, 0, '8-2*2-1*7-1*6-4*'),
(8, 1, 5, NULL, '2013-03-22 11:44:33', '2013-03-22 11:44:33', '2013-03-22 11:44:33', '2013-03-22 11:44:33', 5, 0, '8-2*10-2*'),
(9, 1, 3, NULL, '2013-03-23 07:53:23', '2013-03-23 07:53:23', '2013-03-23 07:53:23', '2013-03-23 07:53:23', 5, 0, '2-2*7-1*'),
(10, 1, 3, NULL, '2013-03-23 09:06:25', '2013-03-23 09:06:25', '2013-03-23 09:06:25', '2013-03-23 09:06:25', 5, 0, '8-2*2-3*7-3*'),
(11, 1, 1, NULL, '2013-03-31 08:31:12', '2013-03-31 08:31:12', '2013-03-31 08:31:12', '2013-03-31 08:31:12', 5, 0, '8-2*10-1*7-1*'),
(12, 1, 3, NULL, '2013-03-31 08:32:51', '2013-03-31 08:32:51', '2013-03-31 08:32:51', '2013-03-31 08:32:51', 5, 0, '8-2*10-1*'),
(13, 1, 1, 1, '2013-03-31 18:09:11', '2013-03-31 18:09:11', '2013-03-31 18:09:11', '2013-03-31 18:09:11', 5, 0, '8-2*'),
(14, 1, 1, 1, '2013-03-31 18:14:20', '2013-03-31 18:15:11', '2013-03-31 18:14:20', '2013-04-04 06:35:11', 5, 0, '8-2*'),
(15, 1, 2, 1, '2013-03-31 18:14:36', '2013-03-31 18:15:41', '2013-03-31 18:14:36', '2013-04-23 18:25:30', 5, 0, '10-1*'),
(16, 1, 3, 1, '2013-03-31 18:14:59', '2013-03-31 18:15:27', '2013-03-31 18:14:59', '2013-04-23 18:26:24', 5, 0, '2-3*'),
(17, NULL, NULL, NULL, '2013-04-23 18:19:36', '2013-04-23 18:19:36', NULL, '2013-04-24 06:18:55', 5, NULL, NULL),
(18, NULL, NULL, NULL, '2013-04-23 18:31:33', '2013-04-23 18:31:33', NULL, '2013-04-26 18:22:03', 5, 1, NULL),
(19, 1, 1, NULL, '2013-04-24 07:35:27', '2013-04-24 07:41:15', '2013-04-24 07:35:27', '2013-04-24 07:43:48', 5, 0, '4-2*'),
(20, 1, 1, NULL, '2013-04-26 07:00:11', '2013-04-26 07:00:11', '2013-04-26 07:00:11', '2013-04-26 07:28:31', 5, 0, '8-2*12-2*'),
(21, 1, 1, 1, '2013-04-26 11:30:46', '2013-04-26 11:33:33', '2013-04-26 11:30:46', '2013-04-26 11:54:11', 5, NULL, ''),
(22, 1, 1, NULL, '2013-04-26 11:58:18', '2013-04-26 11:58:40', '2013-04-26 11:58:18', '2013-04-26 12:16:32', 5, 0, '8-1*'),
(23, 1, 1, NULL, '2013-04-26 12:16:51', '2013-04-26 12:16:59', '2013-04-26 12:16:51', '2013-04-26 12:17:48', 5, 0, '8-1*'),
(24, 1, 1, NULL, '2013-04-26 12:20:30', '2013-04-26 12:24:32', '2013-04-26 12:20:30', '2013-04-26 12:29:43', 5, 0, '8-2*'),
(25, 1, 1, NULL, '2013-04-26 12:28:53', '2013-04-26 12:28:53', '2013-04-26 12:28:53', '2013-04-26 12:32:16', 5, 0, '8-2*12-1*'),
(32, 1, 1, NULL, '2013-04-26 19:00:00', '2013-04-26 19:00:00', '2013-04-26 19:00:00', '2013-04-26 19:00:00', 5, 0, '8-1*'),
(33, 1, 1, NULL, '2013-04-26 19:23:05', '2013-04-26 19:24:33', '2013-04-26 19:23:05', '2013-04-26 19:27:32', 5, 14, '8-2*'),
(34, 1, 1, NULL, '2013-04-26 19:30:34', '2013-04-26 19:30:34', '2013-04-26 19:30:34', '2013-04-26 19:30:34', 5, 0, '8-2*'),
(35, 1, 1, NULL, '2013-04-26 19:33:35', '2013-04-26 19:33:35', '2013-04-26 19:33:35', '2013-04-26 19:33:35', 5, 0, '8-2*'),
(36, 1, 1, NULL, '2013-04-26 19:35:41', '2013-04-26 19:35:41', '2013-04-26 19:35:41', '2013-04-26 19:35:41', 5, 0, '8-2*'),
(37, NULL, NULL, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, 3, 14, NULL),
(38, NULL, NULL, NULL, '2013-04-27 05:39:13', '2013-04-27 05:39:13', NULL, NULL, 3, 14, NULL),
(39, NULL, 1, NULL, '2013-04-27 13:37:22', '2013-04-27 13:38:54', '2013-04-27 13:37:22', '2013-04-27 13:39:17', 5, 14, '8-1*12-1*'),
(40, NULL, 1, NULL, '2013-04-27 13:40:23', '2013-04-27 13:41:00', '2013-04-27 13:40:23', '2013-04-27 13:49:02', 5, 14, '13-2*'),
(41, NULL, 1, NULL, '2013-04-27 13:48:33', '2013-04-27 13:48:33', '2013-04-27 13:48:33', '2013-04-27 13:50:39', 5, 0, '8-1*'),
(42, 1, 1, 1, '2013-04-27 13:54:02', '2013-04-27 13:54:02', '2013-04-27 13:54:02', '2013-04-27 14:05:50', 5, NULL, ''),
(43, 1, 2, 1, '2013-04-27 13:54:18', '2013-04-27 13:54:18', '2013-04-27 13:54:18', '2013-04-27 13:54:18', 4, NULL, ''),
(44, NULL, 1, NULL, '2013-04-27 14:06:33', '2013-04-27 14:06:33', '2013-04-27 14:06:33', '2013-04-27 14:17:55', 5, 0, '8-1*12-1*18-1*'),
(45, NULL, 1, NULL, '2013-04-27 14:17:35', '2013-04-27 14:17:35', '2013-04-27 14:17:35', '2013-04-27 14:17:35', 3, 0, '27-2*');

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE IF NOT EXISTS `orderitem` (
  `OrderItemId` int(3) NOT NULL,
  `OrderId` int(3) DEFAULT NULL,
  `Item_Id` int(3) DEFAULT NULL,
  `Quantity` int(3) DEFAULT NULL,
  `Price` decimal(6,2) DEFAULT NULL,
  `isServed` bit(1) DEFAULT NULL,
  PRIMARY KEY (`OrderItemId`),
  KEY `OrderId` (`OrderId`),
  KEY `Item_Id` (`Item_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderitem`
--

INSERT INTO `orderitem` (`OrderItemId`, `OrderId`, `Item_Id`, `Quantity`, `Price`, `isServed`) VALUES
(3, 2, 3, 12, '42.00', b'1'),
(4, 2, 2, 3, '23.00', b'1'),
(5, 2, 6, 2, '65.00', b'1'),
(6, 2, 8, 1, '34.00', b'1'),
(7, 3, 3, 6, '59.00', b'1'),
(8, 3, 4, 20, '37.00', b'1'),
(9, 3, 6, 1, '94.00', b'1'),
(11, 3, 2, 3, '73.00', b'1'),
(12, 4, 1, 9, '35.00', b'1'),
(13, 4, 10, 7, '16.00', b'1'),
(14, 4, 7, 1, '27.00', b'1'),
(16, 5, 3, 2, '58.00', b'1'),
(17, 5, 2, 4, '36.00', b'1'),
(18, 5, 1, 5, '26.00', b'1'),
(19, 7, 8, 2, '23.00', b'1'),
(20, 7, 2, 1, '20.00', b'1'),
(21, 7, 7, 1, '30.00', b'1'),
(22, 7, 6, 4, '10.00', b'1'),
(23, 8, 8, 2, '23.00', b'1'),
(24, 8, 10, 2, '123.00', b'1'),
(25, 9, 2, 2, '20.00', b'0'),
(26, 9, 7, 1, '30.00', b'0'),
(27, 10, 8, 2, '23.00', b'1'),
(28, 10, 2, 3, '20.00', b'1'),
(29, 10, 7, 3, '30.00', b'1'),
(30, 10, 7, 8, '30.00', b'1'),
(31, 11, 8, 2, '23.00', b'1'),
(32, 11, 10, 1, '123.00', b'1'),
(33, 11, 7, 1, '30.00', b'1'),
(34, 11, 7, 2, '30.00', b'1'),
(35, 12, 8, 2, '23.00', b'1'),
(36, 12, 10, 1, '123.00', b'1'),
(37, 12, 10, 1, '123.00', b'0'),
(38, 12, 10, 1, '123.00', b'0'),
(40, 11, 6, 2, '10.00', b'1'),
(41, 11, 6, 1, '10.00', b'1'),
(42, 13, 8, 2, '23.00', b'1'),
(43, 13, 10, 1, '123.00', b'1'),
(44, 13, 8, 2, '23.00', b'0'),
(45, 13, 2, 1, '20.00', b'0'),
(46, 14, 8, 2, '23.00', b'1'),
(48, 16, 2, 3, '20.00', b'1'),
(52, 5, 4, 1, '10.00', NULL),
(53, 5, 1, 2, '10.00', NULL),
(54, 1, 3, 9, '35.00', NULL),
(55, 1, 7, 5, '110.00', NULL),
(56, 1, 14, 3, '25.00', NULL),
(57, 17, 17, 3, '23.00', NULL),
(58, 18, 6, 3, '10.00', NULL),
(59, 15, 10, 4, '13.00', NULL),
(60, 19, 4, 2, '10.00', b'1'),
(64, 21, 8, 2, '40.00', b'1'),
(65, 22, 8, 1, '40.00', b'1'),
(66, 23, 8, 1, '40.00', b'1'),
(67, 24, 8, 2, '40.00', b'1'),
(68, 25, 8, 2, '40.00', b'1'),
(69, 25, 12, 1, '13.00', b'1'),
(88, 4, 8, 1, '40.00', b'0'),
(89, 5, 8, 1, '40.00', b'0'),
(90, 6, 21, 2, '45.00', b'0'),
(91, 33, 8, 2, '40.00', b'1'),
(92, 37, 23, 2, '35.00', NULL),
(93, 38, 23, 3, '35.00', NULL),
(94, 20, 8, 4, '40.00', NULL),
(95, 20, 12, 2, '13.00', NULL),
(96, 20, 8, 1, '40.00', NULL),
(97, 20, 18, 3, '30.00', NULL),
(98, 39, 8, 1, '40.00', b'1'),
(99, 40, 13, 2, '12.00', b'1'),
(100, 40, 8, 1, '40.00', b'1'),
(101, 40, 12, 1, '13.00', b'1'),
(102, 40, 21, 1, '45.00', b'1'),
(103, 41, 8, 1, '40.00', b'1'),
(104, 42, 8, 2, '40.00', b'0'),
(105, 42, 12, 2, '13.00', b'0'),
(106, 42, 21, 2, '45.00', b'0'),
(107, 43, 8, 1, '40.00', b'0'),
(108, 42, 20, 1, '30.00', b'0'),
(109, 44, 8, 1, '40.00', b'1'),
(110, 45, 27, 2, '25.00', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `restable`
--

CREATE TABLE IF NOT EXISTS `restable` (
  `TableId` int(3) NOT NULL,
  `TableName` varchar(50) DEFAULT NULL,
  `PosX` int(3) DEFAULT NULL,
  `PosY` int(3) DEFAULT NULL,
  `Width` int(3) DEFAULT NULL,
  `Height` int(3) DEFAULT NULL,
  `SectionId` int(3) DEFAULT NULL,
  PRIMARY KEY (`TableId`),
  KEY `SectionId` (`SectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restable`
--

INSERT INTO `restable` (`TableId`, `TableName`, `PosX`, `PosY`, `Width`, `Height`, `SectionId`) VALUES
(1, 'front table 1 ', 77, 56, 40, 40, 1),
(2, 'dads ', 386, 250, 40, 40, 1),
(3, 'awdasd ', 79, 118, 40, 40, 1),
(4, 'awasdfrs     ', 299, 150, 40, 40, 1),
(5, 'awdsd  ', 389, 126, 40, 40, 1),
(6, 'new table ', 239, 61, 40, 40, 1),
(7, 'new 2 ', 310, 250, 40, 40, 1),
(8, 'acwc  ', 239, 247, 40, 40, 1),
(9, 'asdgtad  ', 80, 178, 40, 40, 1),
(11, 'miydfg  ', 317, 61, 40, 40, 1),
(12, ' hngh       ', 394, 185, 40, 40, 1),
(13, 'dadsdf     ', 160, 60, 40, 40, 1),
(14, 'vsdfx ', 384, 55, 40, 40, 1),
(15, 'asdas ', 132, 44, 40, 40, 2),
(16, 'bddaw ', 235, 43, 40, 40, 2),
(17, 'asdasdw ', 328, 44, 40, 40, 2),
(19, 'vas', 240, 156, 40, 40, 2),
(20, 'xar', 145, 154, 40, 40, 2),
(21, 'rasg', 77, 102, 40, 40, 2),
(22, 'aws  ', 539, 101, 40, 40, 2),
(23, 'tgsed', 459, 165, 40, 40, 2),
(24, 'rsdf', 476, 36, 40, 40, 2),
(25, 'hryh', 398, 40, 40, 40, 2),
(26, 'uty', 362, 160, 40, 40, 2),
(27, 'dwasd ', 329, 138, 40, 40, 3),
(28, 'vrg', 232, 137, 40, 40, 3),
(29, 'nser', 357, 47, 40, 40, 3),
(30, 'caw ', 468, 51, 40, 40, 3),
(31, 'jard', 271, 47, 40, 40, 3),
(32, 'vtjy', 174, 47, 40, 40, 3),
(33, 'aew', 427, 137, 40, 40, 3),
(34, 'asdasd  ', 158, 246, 40, 40, 1),
(35, 'new 2 ', 83, 240, 40, 40, 1),
(36, 'new3 ', 189, 145, 40, 40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `restaurantdetail`
--

CREATE TABLE IF NOT EXISTS `restaurantdetail` (
  `RestaurantDetailId` int(3) NOT NULL,
  `RestaurantName` varchar(50) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `PhoneNo` decimal(12,0) DEFAULT NULL,
  `FaxNo` decimal(12,0) DEFAULT NULL,
  PRIMARY KEY (`RestaurantDetailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restaurantdetail`
--

INSERT INTO `restaurantdetail` (`RestaurantDetailId`, `RestaurantName`, `Address`, `PhoneNo`, `FaxNo`) VALUES
(1, 'Chocolate Room', '  Fatehgunj, Vadodara,\r\n300254 ', '2652123233', '2652123233');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
  `SectionId` int(3) NOT NULL,
  `SectionName` varchar(50) DEFAULT NULL,
  `PosX` int(3) DEFAULT NULL,
  `PosY` int(3) DEFAULT NULL,
  `Width` int(3) DEFAULT NULL,
  `Height` int(3) DEFAULT NULL,
  PRIMARY KEY (`SectionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`SectionId`, `SectionName`, `PosX`, `PosY`, `Width`, `Height`) VALUES
(1, 'main hall   ', 254, 67, 100, 100),
(2, 'ground floor ', 408, 67, 100, 100),
(3, 'first floor ', 103, 65, 100, 100),
(4, 'Second Floor', 184, 206, 100, 100),
(5, 'Third Floor ', 340, 204, 100, 100);

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE IF NOT EXISTS `theme` (
  `themeid` int(2) NOT NULL,
  `themename` varchar(20) DEFAULT NULL,
  `isset` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `theme`
--

INSERT INTO `theme` (`themeid`, `themename`, `isset`) VALUES
(1, 'black', b'0'),
(2, 'orange', b'0'),
(3, 'purple', b'1'),
(4, 'blue', b'0'),
(5, 'green', b'0'),
(6, 'yellow', b'0'),
(7, 'red', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `userdetail`
--

CREATE TABLE IF NOT EXISTS `userdetail` (
  `UserDetailId` int(3) NOT NULL,
  `Fname` varchar(50) DEFAULT NULL,
  `Lname` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Password` varchar(15) DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `Phone` bigint(10) DEFAULT NULL,
  `IdentityType` varchar(50) DEFAULT NULL,
  `IdentityCode` varchar(50) DEFAULT NULL,
  `Documents` varchar(50) DEFAULT NULL,
  `IsAdmin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`UserDetailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userdetail`
--

INSERT INTO `userdetail` (`UserDetailId`, `Fname`, `Lname`, `Email`, `Password`, `Address`, `Phone`, `IdentityType`, `IdentityCode`, `Documents`, `IsAdmin`) VALUES
(1, 'Sukrut', 'Modak', 'modak.7@gmail.com', 'sukrut', 'hari nagar', 9998657899, 'license card', '23kjf94d', 'license', 1),
(6, 'Shyam', 'Solanki', 'syamsolanki@gmail.com', 'shyamsolanki', 'limbdi', 919998728339, 'license', 's9jd9', 'license', 0),
(10, 'akshat', 'gandhi', 'akshat@gmail.com', 'akshat', 'samta', 9044867632, 'voter id', 'sj0mdi2', 'voter id', 0),
(11, 'dalpat', 'dodiya', 'dalpat@gmail.com', 'dalpatdodiya', 'porbandar', 9876543210, 'voter id', '2378', 'sdkjhf', 0),
(14, 'kartik', 'patel', 'kpatel.2010@live.com', 'kpatel', 'anand', 919876543210, 'voter card', '2d3q14', '', 1),
(15, 'Manan', 'Vohra', 'manan.vohra@gmail.com', 'mananvohra', 'karelibaug, vadodara,', 919876509832, 'license', '30sej3', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE IF NOT EXISTS `userlog` (
  `UserLogId` int(3) NOT NULL,
  `UserDetailId` int(3) DEFAULT NULL,
  `Action` varchar(20) DEFAULT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserLogId`),
  KEY `UserDetailId` (`UserDetailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`UserLogId`, `UserDetailId`, `Action`, `TimeStamp`) VALUES
(1, 10, 'Login', '2013-04-26 18:39:33'),
(2, 10, 'Login', '2013-04-26 18:54:32'),
(3, 10, 'Login', '2013-04-26 18:56:19'),
(4, 10, 'Placed order', '2013-04-26 19:00:00'),
(5, 10, 'Placed order', '2013-04-26 19:01:23'),
(6, 10, 'Placed order', '2013-04-26 19:03:27'),
(7, 10, 'Login', '2013-04-27 14:12:26');

-- --------------------------------------------------------

--
-- Table structure for table `waiter`
--

CREATE TABLE IF NOT EXISTS `waiter` (
  `WaiterId` int(3) NOT NULL,
  `WaiterName` varchar(50) DEFAULT NULL,
  `Age` int(3) DEFAULT NULL,
  `Notes` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`WaiterId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `waiter`
--

INSERT INTO `waiter` (`WaiterId`, `WaiterName`, `Age`, `Notes`) VALUES
(1, 'akshat', 23, 'sucking waiter!!'),
(2, 'sukrut', 23, 'lazy waiter'),
(9, 'manan', 30, ''),
(10, 'zaffar', 23, 'new recruited'),
(11, 'dalpat', 23, ''),
(12, 'kp', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `waiterrequests`
--

CREATE TABLE IF NOT EXISTS `waiterrequests` (
  `tableid` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `device`
--
ALTER TABLE `device`
  ADD CONSTRAINT `device_ibfk_1` FOREIGN KEY (`WaiterId`) REFERENCES `waiter` (`WaiterId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `device_ibfk_2` FOREIGN KEY (`TableId`) REFERENCES `restable` (`TableId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `devicelog`
--
ALTER TABLE `devicelog`
  ADD CONSTRAINT `devicelog_ibfk_1` FOREIGN KEY (`DeviceId`) REFERENCES `device` (`DeviceID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`OrderId`) REFERENCES `order` (`OrderId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoiceitem`
--
ALTER TABLE `invoiceitem`
  ADD CONSTRAINT `invoiceitem_ibfk_1` FOREIGN KEY (`InvoiceId`) REFERENCES `invoice` (`InvoiceId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`Category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`UserDetailId`) REFERENCES `userdetail` (`UserDetailId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`TableId`) REFERENCES `restable` (`TableId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`WaiterId`) REFERENCES `waiter` (`WaiterId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`OrderId`) REFERENCES `order` (`OrderId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`Item_Id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restable`
--
ALTER TABLE `restable`
  ADD CONSTRAINT `restable_ibfk_1` FOREIGN KEY (`SectionId`) REFERENCES `section` (`SectionId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userlog`
--
ALTER TABLE `userlog`
  ADD CONSTRAINT `userlog_ibfk_1` FOREIGN KEY (`UserDetailId`) REFERENCES `userdetail` (`UserDetailId`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
