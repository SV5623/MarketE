-- Використання бази даних
USE MarketTryPlace;

-- Додавання записів до таблиці goods з вказанням user_id
INSERT INTO goods (`user_id`, `name`, `price`, `image`)
VALUES
(1, 'Chocolate Santa Claus', '1.50', 'static/img/product-2.png'),
(1, 'New Year Tree', '99.00', 'static/img/product-3.jpg'),
(1, 'Sweet Box', '6.00', 'static/img/product-4.jpg'),
(1, 'Santa Claus Figurine', '20.00', 'static/img/product-5.jpg'),
(1, 'New Year Ball', '30.00', 'static/img/product-6.jpg'),
(1, 'Tree Ornament Ball', '3.00', 'static/img/product-7.jpg'),
(1, 'Tinsel', '1.20', 'static/img/product-8.jpg'),
(1, 'Garland "Bulbs"', '12.00', 'static/img/product-9.jpg'),
(1, 'New Year Champagne', '2.40', 'static/img/product-10.jpg'),
(1, 'Candy Box', '2.50', 'static/img/product-11.jpg'),
(1, 'Surprise Gift', '9.00', 'static/img/product-12.jpg'),
(1, 'Star for the Tree', '4.00', 'static/img/product-13.jpg'),
(1, 'New Year Hat', '6.00', 'static/img/product-14.jpg'),
(1, 'Bengal Lights', '1.20', 'static/img/product-15.jpg'),
(1, 'Party Popper', '0.80', 'static/img/product-16.png');
