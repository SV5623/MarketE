-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Час створення: Лис 18 2023 р., 23:49
-- Версія сервера: 10.4.28-MariaDB
-- Версія PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `MarketTryPlace`
--

-- --------------------------------------------------------

--
-- Структура таблиці `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `goods`
--

INSERT INTO `goods` (`id`, `name`, `price`, `image`, `user_id`, `description`) VALUES
(1, 'Chocolate Santa Claus', 1.50, 'static/img/product-2.png', 1, ''),
(2, 'Party Popper', 0.80, 'static/img/product-16.png', 1, ''),
(3, 'Chocolate Santa Claus', 1.50, 'static/img/product-2.png', 1, ''),
(4, 'New Year Tree', 99.00, 'static/img/product-3.jpg', 1, ''),
(5, 'Sweet Box', 6.00, 'static/img/product-4.jpg', 1, ''),
(6, 'Santa Claus Figurine', 20.00, 'static/img/product-5.jpg', 1, ''),
(7, 'New Year Ball', 30.00, 'static/img/product-6.jpg', 1, ''),
(8, 'Tree Ornament Ball', 3.00, 'static/img/product-7.jpg', 1, ''),
(9, 'Tinsel', 1.20, 'static/img/product-8.jpg', 1, ''),
(10, 'Garland \"Bulbs\"', 12.00, 'static/img/product-9.jpg', 1, ''),
(11, 'New Year Champagne', 2.40, 'static/img/product-10.jpg', 1, ''),
(12, 'Candy Box', 2.50, 'static/img/product-11.jpg', 1, ''),
(13, 'Surprise Gift', 9.00, 'static/img/product-12.jpg', 1, ''),
(14, 'Star for the Tree', 4.00, 'static/img/product-13.jpg', 1, ''),
(15, 'New Year Hat', 6.00, 'static/img/product-14.jpg', 1, ''),
(16, 'Bengal Lights', 1.20, 'static/img/product-15.jpg', 1, ''),
(17, 'Party Popper', 0.80, 'static/img/product-16.png', 1, ''),
(18, 'try one', 1.00, 'static/img/Знімок екрана з 2023-11-13 19-07-00.png', 1, ''),
(19, 'Second Try', 5623.00, 'static/img/pxfuel.jpg', 1, ''),
(20, 'Товар', 1500.00, 'static/img/131-1316749_clash-of-clans-hack-get-unlimited-gems-in.jpg', 1, 'Опис товару , по приколу');

-- --------------------------------------------------------

--
-- Структура таблиці `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `fio` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$3m12Qh0X8AllMWXQgWNM/ehMZZvAQTWCUGh/iSa3AH.Cy5O2ZDtuK'),
(2, '5623', '$2y$10$Ik38Kc2aZFsKmbzhXnWgZ.XT1Hx.qWamHK7pykYQcJMC84NVYCx.q');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Індекси таблиці `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблиці `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `goods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
