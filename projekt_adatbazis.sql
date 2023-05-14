-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Máj 14. 11:13
-- Kiszolgáló verziója: 10.4.27-MariaDB
-- PHP verzió: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `projekt_adatbazis`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `orders`
--

CREATE TABLE `orders` (
  `order_id` int(10) NOT NULL,
  `ordered_products` varchar(200) NOT NULL,
  `ordered_quantities` varchar(20) NOT NULL,
  `overall_price` int(10) NOT NULL,
  `delivery_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- A tábla adatainak kiíratása `orders`
--

INSERT INTO `orders` (`order_id`, `ordered_products`, `ordered_quantities`, `overall_price`, `delivery_status`) VALUES
(5, 'Playstation 4,Playstation 5,Xbox 360', '1,1,4', 610000, 'Delivered'),
(6, 'Playstation 4,Playstation 5,Xbox 360,Xbox One', '2,3,1,4', 1350000, 'Delivered'),
(7, 'Playstation 4,Playstation 5,Xbox 360,Xbox One', '1,1,1,1', 500000, 'Delivered'),
(8, 'Playstation 4', '1', 120000, 'Delivered'),
(9, 'Xbox 360', '1', 80000, 'Delivered'),
(10, 'Xbox One', '1', 130000, 'Delivered'),
(11, 'Playstation 5', '1', 170000, 'Delivered'),
(12, 'Playstation 4', '1', 120000, 'Delivered'),
(13, 'Playstation 5', '2', 340000, 'Delivered'),
(14, 'Playstation 5,Xbox 360,Xbox One', '3,4,3', 1220000, 'Delivered'),
(15, 'Playstation 4,Playstation 5,Xbox 360,Xbox One', '1,1,1,1', 500000, 'Delivered'),
(16, 'Playstation 4', '1', 120000, 'Delivered'),
(17, 'Playstation 5', '1', 170000, 'Delivered'),
(18, 'Playstation 4', '28', 3360000, 'Delivered'),
(19, 'Playstation 5,Xbox 360,Xbox One', '1,1,1', 380000, 'Delivered'),
(20, 'Xbox 360', '2', 160000, 'Delivered'),
(21, 'Playstation 5', '3', 510000, 'Delivered'),
(22, 'Playstation 5,Xbox 360,Xbox One', '1,2,1', 460000, 'Delivered');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `products`
--

CREATE TABLE `products` (
  `id` int(5) UNSIGNED NOT NULL,
  `product_name` varchar(25) NOT NULL,
  `product_category` varchar(15) NOT NULL,
  `product_quantity` int(5) NOT NULL,
  `product_image` varchar(100) NOT NULL,
  `product_description` varchar(500) NOT NULL,
  `product_price` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_category`, `product_quantity`, `product_image`, `product_description`, `product_price`) VALUES
(1, 'Playstation 4', 'Console', 30, '..\\imgs\\playstation4_thumb.jpg', 'The PlayStation 4 (PS4) is a home video game console developed by Sony Interactive Entertainment. Announced as the successor to the PlayStation 3 in February 2013, it was launched on November 15, 2013, in North America, November 29, 2013 in Europe, South America and Australia, and on February 22, 2014 in Japan.', 120000),
(2, 'Playstation 5', 'Console', 20, '../imgs/playstation5_thumb.jpg', 'The latest Sony PlayStation introduced in November 2020. Powered by an eight-core AMD Zen 2 CPU and custom AMD Radeon GPU, the PS5 is offered in two models: with and without a 4K Blu-ray drive. Supporting a 120Hz video refresh, the PS5 is considerably more powerful than the PS4 and PS4 Pro.', 170000),
(3, 'Xbox 360', 'Console', 20, '../imgs/xbox360_thumb.jpg', 'The Xbox 360 is a home video game console developed by Microsoft. As the successor to the original Xbox, it is the second console in the Xbox series. It competed with Sony\'s PlayStation 3 and Nintendo\'s Wii as part of the seventh generation of video game consoles.', 80000),
(4, 'Xbox One', 'Console', 24, '../imgs/xboxOne_thumb.jpg', 'The Xbox One is a home video game console developed by Microsoft. Announced in May 2013, it is the successor to Xbox 360 and the third console in the Xbox series.', 130000),
(5, 'AMD Rx 6700 xt', 'GPU', 30, '../imgs/amdRx6700xt_thumb.jpg', '', 230000),
(6, 'AMD Threadripper 3970X', 'CPU', 30, '../imgs/amdThreadripper3970X_thumb.jpg', '', 250000),
(7, 'Tom Clancy\'s The Division', 'PC Game', 30, '../imgs/division2_thumb.jpg', '', 15000),
(8, 'HTC Vive Cosmos', 'VR Gear', 30, '../imgs/htcViveCosmos_thumb.jpg', '', 170000),
(9, 'Intel I9 10900K', 'CPU', 12, '../imgs/inteli9-10900k_thumb.jpg', '', 170000),
(10, 'Nvidia RTX 4080', 'GPU', 8, '../imgs/nvidiaRtx4080_thumb.jpg', '', 150000),
(11, 'Tom Clancy\'s Rainbow Six ', 'PC Game', 10, '../imgs/rainbow6_thumb.jpg', '', 20000),
(12, 'Redragon Draconic K530', 'Keyboard', 10, '../imgs/Redragon60_thumb.jpg', '', 25000),
(13, 'Titanfall 2', 'PC Game', 10, '../imgs/titanfall2_thumb.jpg', '', 15000);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int(5) UNSIGNED NOT NULL,
  `emailaddress` varchar(30) NOT NULL,
  `password` char(60) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `date_of_birth` date NOT NULL,
  `postalcode` int(6) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `street` varchar(40) DEFAULT NULL,
  `street_number` varchar(15) DEFAULT NULL,
  `telephone_number` varchar(20) DEFAULT NULL,
  `other_information` varchar(30) DEFAULT NULL,
  `adminlevel` tinyint(1) NOT NULL,
  `privacy_policy` tinyint(1) NOT NULL,
  `terms_of_service` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `emailaddress`, `password`, `reg_date`, `firstname`, `lastname`, `date_of_birth`, `postalcode`, `city`, `street`, `street_number`, `telephone_number`, `other_information`, `adminlevel`, `privacy_policy`, `terms_of_service`) VALUES
(1, 'vigh.moric@gmail.com', '$2y$12$Byqzyl3va/iGz2XcItjfMeApSP91VIPTh8rnkhHyqq/yAJ2hIfE/C', '2023-05-08 16:52:21', 'Vigh', 'Móric', '1998-03-23', 1144, 'Budapest', 'Szentmihályi út', '27. 1 em. 25 a.', '+36204628313', '14-es csengő', 1, 1, 1),
(16, 'admin@admin.hu', '$2y$12$Qvw.9YGIwdC2yD3lAJpG4eQE9eXyv/.5FKZWk6WyqJoq9cAhfu0Zm', '2023-05-11 17:19:10', 'Admin', 'Admin', '2000-12-31', 654, '123', '123', '123', '+3692', NULL, 1, 1, 1),
(17, 'teszt@teszt.hu', '$2y$12$SkgsVFHmN6rhDRrwLQiEFuKYNXHYTCnmfdpFmcG8mFFLaeMWltkYS', '2023-05-11 16:39:09', 'Teszt', 'Teszt', '2000-12-31', 1444, 'Budapest', 'Váci ', '16', '+36905242825', NULL, 0, 1, 1);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- A tábla indexei `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT a táblához `products`
--
ALTER TABLE `products`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
