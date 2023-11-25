<?php
session_start(); // Ініціалізація сесії

// Підключення до бази даних
require 'classes/PdoConnect.php';
$pdo = PdoConnect::getInstance()->PDO;

// Отримання товарів з бази даних
$sql = "SELECT * FROM goods"; // Замість "products" вставте назву вашої таблиці
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Визначення змінної для перевірки стану сесійної змінної
$userIsAuthenticated = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Book_Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link href="/MarketTry/static/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/MarketTry/static/css/slick.css" rel="stylesheet" type="text/css">

    <script src="/MarketTry/static/js/script.js"></script>
   <link href="/MarketTry/static/css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    
    <div class="main container">
        <header>
            <div class="slider-block">
                <div class="nav-left"><i class="fas fa-chevron-left"></i></div>
                <div class="slider">
                    <div style="background: url('static/img/slide-1.jpg') no-repeat; background-size: auto 100%; background-position: center; background-position-y: 0;">
                        <span class="text-box">New Year decorations with a 30% discount</span>
                    </div>
                    <div style="background: url('static/img/slide-2.jpg') no-repeat; background-size: auto 100%; background-position: center; background-position-y: 0;">
                        <span class="text-box">Wide selection of Christmas wreaths</span>
                    </div>
                    <div style="background: url('static/img/slide-3.jpg') no-repeat; background-size: auto 100%; background-position: center; background-position-y: 0;">
                        <span class="text-box">Give your child a holiday, invite Santa Claus!</span>
                    </div>
                </div>
                <div class="nav-right"><i class="fas fa-chevron-right"></i></div>
            </div>
        </header>

        <section class="product-box">
            <h2>Catalog</h2>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3 product-parent" data-id="<?= isset($product['id']) ? htmlspecialchars($product['id']) : '' ?>">
                        <div class="product">
                            <div class="product-pic" style="background: url('<?= isset($product['image']) ? htmlspecialchars($product['image']) : '' ?>') no-repeat; background-size: auto 100%; background-position: center"></div>
                            <span class="product-name"><?= isset($product['name']) ? htmlspecialchars($product['name']) : '' ?></span>
                            <span class="product_price"><?= isset($product['price']) ? htmlspecialchars($product['price']) : '' ?> USD</span>

                            <!-- Додана властивість data-id для ідентифікації товару -->
                            <?php if ($userIsAuthenticated): ?>
                                <button class="product-button js_buy" data-id="<?= isset($product['id']) ? htmlspecialchars($product['id']) : '' ?>">Buy</button>
                            <?php else: ?>
                                <p>Please <a href="classes/login.php">log in</a>, to buy it.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <footer>
            Have a nice day!
        </footer>

        <script src="/MarketTry/static/js/jquery-3.4.1.min.js"></script>
        <script src="/MarketTry/static/js/slick.js"></script>
        <script src="/MarketTry/static/js/script.js"></script>
        <script>
            // Додайте скрипт для обробки натискання кнопок "Buy"
            $(document).ready(function() {
                $('.js_buy').click(function() {
                    var productId = $(this).data('id');
                    window.location.href = 'product_details.php?id=' + productId;
                });
            });
        </script>
    </div>

</body>

</html>
