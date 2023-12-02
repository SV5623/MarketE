-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Час створення: Гру 02 2023 р., 19:21
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
CREATE DATABASE `MarketTryPlace`;

-- --------------------------------------------------------

--
-- Структура таблиці `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `opis` text DEFAULT NULL,
  `kategoria` varchar(255) DEFAULT 'supermarket',
  `liczba_sztuk` int(11) DEFAULT 12,
  `kraj` varchar(255) DEFAULT 'Polska',
  `kod_pocztowy` varchar(10) DEFAULT '66-400',
  `stan` varchar(50) DEFAULT 'nowy',
  `seller_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `goods`
--

INSERT INTO `goods` (`id`, `user_id`, `name`, `price`, `image`, `opis`, `kategoria`, `liczba_sztuk`, `kraj`, `kod_pocztowy`, `stan`, `seller_email`) VALUES
(5, 1, 'Słodka skrzyneczka', 60.00, 'static/img/product-4.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 11, 'Polska', '66-400', 'Nowy', NULL),
(6, 1, 'Figurka Świętego Mikołaja', 200.00, 'static/img/product-5.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(7, 1, 'Bal noworoczny', 30.00, 'static/img/product-6.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(8, 1, 'Piłka choinkowa', 30.00, 'static/img/product-7.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(9, 1, 'Miszura', 12.00, 'static/img/product-8.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(10, 1, 'Girlanda', 12.00, 'static/img/product-9.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(11, 1, 'Szampan noworoczny', 24.00, 'static/img/product-10.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(12, 1, 'Pudełko cukierków', 25.00, 'static/img/product-11.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(13, 1, 'Prezent', 90.00, 'static/img/product-12.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(14, 1, 'Noworoczna czapka', 60.00, 'static/img/product-14.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(15, 1, 'Sparklery', 10.00, 'static/img/product-15.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 12, 'Polska', '66-400', 'Nowy', NULL),
(16, 1, 'Petarda', 80.00, 'static/img/product-16.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque.', 'supermarket', 11, 'Polska', '66-400', 'Nowy', NULL),
(28, 2, 'Ручка', 1.50, 'static/img/Знімок екрана з 2023-11-22 22-56-05.png', 'Ручка синього кольору, кулькова ', 'supermarket', 94, 'Polska', '66-400', 'nowy', NULL);

--
-- Тригери `goods`
--
DELIMITER $$
CREATE TRIGGER `before_delete_zero_quantity_goods` BEFORE DELETE ON `goods` FOR EACH ROW BEGIN
    IF OLD.liczba_sztuk = 0 THEN
        DELETE FROM purchases WHERE goods_id = OLD.id;
    END IF;
END
$$
DELIMITER ;

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
-- Структура таблиці `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `liczba_sztuk` int(11) DEFAULT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', '$2y$10$3m12Qh0X8AllMWXQgWNM/ehMZZvAQTWCUGh/iSa3AH.Cy5O2ZDtuK', 'ksv05623@gmail.com'),
(2, '5623', '$2y$10$Ik38Kc2aZFsKmbzhXnWgZ.XT1Hx.qWamHK7pykYQcJMC84NVYCx.q', 'maestro5623@gmail.com'),
(3, 'ad', '$2y$10$bccIUyksPvYgIQ0N3rx9SefQaJIbyVuYGWgyOV/3f2GRULbmf8J/6', ''),
(5, 'admin3', '$2y$10$57eQeQHwfvPiAHBsZZqsMuTq3uuqO5xemqoflM.nZ35ymVNg2kEra', 'admin@gmail.com');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_seller_email` (`seller_email`);

--
-- Індекси таблиці `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `goods_id` (`goods_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблиці `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблиці `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `fk_seller_email` FOREIGN KEY (`seller_email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `goods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Обмеження зовнішнього ключа таблиці `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`goods_id`) REFERENCES `goods` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;