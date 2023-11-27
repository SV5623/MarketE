<?php
session_start();

require 'classes/PdoConnect.php';
$pdo = PdoConnect::getInstance()->PDO;

// Отримання всіх товарів для каталогу
$sql = "SELECT * FROM goods";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Отримання останніх трьох товарів для слайдера
$sqlLatest = "SELECT * FROM goods ORDER BY id DESC LIMIT 3";
$stmtLatest = $pdo->query($sqlLatest);
$latestProducts = $stmtLatest->fetchAll(PDO::FETCH_ASSOC);

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
    <link href="/MarketTry/static/css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php include 'includes/navbar.html'; ?>
    <div class="main-header">
        <div class="slider-block">
            <div class="nav-left"><i class="fas fa-chevron-left"></i></div>
            <div class="slider">
                <?php foreach ($latestProducts as $product): ?>
                    <div class="slide" style="background: url('<?= isset($product['image']) ? htmlspecialchars($product['image']) : '' ?>') no-repeat; background-size: cover; background-position: center;">
                        <span class="text-box"><?= isset($product['name']) ? htmlspecialchars($product['name']) : '' ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="nav-right"><i class="fas fa-chevron-right"></i></div>
        </div>
    </div>

    <div class="main container">
        <section class="product-box">
            <h2>Catalog</h2>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 product-parent" data-id="<?= isset($product['id']) ? htmlspecialchars($product['id']) : '' ?>">
                        <div class="product">
                            <img class="product-pic" src="<?= isset($product['image']) ? htmlspecialchars($product['image']) : '' ?>" alt="<?= isset($product['name']) ? htmlspecialchars($product['name']) : 'Product Image' ?>">
                            <span class="product-name"><?= isset($product['name']) ? htmlspecialchars($product['name']) : '' ?></span>
                            <span class="product_price"><?= isset($product['price']) ? htmlspecialchars($product['price']) : '' ?> USD</span>

                            <?php if ($userIsAuthenticated): ?>
                                <button class="js_buy" data-id="<?= isset($product['id']) ? htmlspecialchars($product['id']) : '' ?>">Buy</button>
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

    </div>
</body>

</html>