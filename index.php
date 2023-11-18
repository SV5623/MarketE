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
<html>
<head>
    <title>Book_Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link href="/MarketTry/static/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/MarketTry/static/css/slick.css" rel="stylesheet" type="text/css">
    <script src="/MarketTry/static/js/jquery-3.4.1.min.js"></script>
    <script src="/MarketTry/static/js/slick.js"></script>
    <script src="/MarketTry/static/js/script.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    <link href="/MarketTry/static/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="vein"></div>
    <div class="main container">
        <header>
            <div class="mobile-menu-open-button js_mobile_menu_open_button"><i class="fas fa-bars"></i></div>
            <nav class="js_wide_menu">
                <i class="fas fa-times close-mobile-menu js_close_mobile_menu"></i>
                <div class="wrapper-inside">
                    <div class="visible-elements">
                    <div class="login-container">
                        <?php if ($userIsAuthenticated): ?>
                            <!-- Меню для авторизованого користувача -->
                            <span><a href="profile.php">Profile</a></span>
                            <span><a href="classes/logout.php">Log Out</a></span>
                            <span><a href="add_product.php">Add your product</a></span>
                            <span><a href="classes/export.php">Take CsV</a></span>
                        <?php else: ?>
                            <!-- Меню для неавторизованого користувача -->
                            <span><a href="classes/login.php">Login</a></span>
                            <span><a href="classes/export.php">Take CsV</a></span>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
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
                                <button class="js_buy" data-id="<?= isset($product['id']) ? htmlspecialchars($product['id']) : '' ?>">Buy</button>
                            <?php else: ?>
                                <p>Будь ласка, <a href="classes/login.php">авторизуйтесь</a>, щоб купити товар.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        </section>
        <footer>
            Have a nice day!
        </footer>
   
    <div class="admin-panel js_admin_panel">
        <!-- Here, you can add the content for the admin panel, e.g., a form for uploading products -->
    </div>

    <script>
    // Додайте скрипт для обробки натискання кнопок "Buy"
        $(document).ready(function() {
            $('.js_buy').click(function() {
                var productId = $(this).data('id');
                window.location.href = 'product_details.php?id=' + productId;
            });
        });
    </script>

</body>
</html>
