<!-- product_detail.php -->
<?php
session_start();

require 'classes/PdoConnect.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header('Location: index.php');
    exit;
}

$pdo = PdoConnect::getInstance()->PDO;

$sql = 'SELECT * FROM goods WHERE id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: index.php');
    exit;
}

$userIsAuthenticated = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($product['name']) ? htmlspecialchars($product['name']) : 'Product Details' ?></title>
    <link rel="stylesheet" type="text/css" href="/MarketTry/static/css/styles.css">
    <link rel="stylesheet" type="text/css" href="/MarketTry/static/css/product-details.css">
    <!-- Додайте додаткові стилі за необхідності -->
    <!-- Підключення Bootstrap CSS -->

</head>
<body>

<!-- Підключення navbar -->
<?php include 'includes/navbar.html'; ?>

<div class="container">
    <div class="product-content">
        <h2 class="product-title"><?= isset($product['name']) ? htmlspecialchars($product['name']) : 'Product Details' ?></h2>

        <div class="product-details">
            <img src="<?= isset($product['image']) ? htmlspecialchars($product['image']) : '' ?>" alt="Product Image" class="product-image">

            <?php if (isset($product['opis']) && !empty($product['opis'])): ?>
                <div class="description">
                    <p><?= htmlspecialchars($product['opis']) ?></p>
                </div>
            <?php else: ?>
                <div class="description">
                    <p>No description available.</p>
                </div>
            <?php endif; ?>

            <div class="price-button-container">
                <p class="price">Price: <?= isset($product['price']) ? htmlspecialchars($product['price']) . ' USD' : '' ?></p>

                <?php if ($userIsAuthenticated): ?>
                    <?php if ($_SESSION['user_id'] === $product['user_id']): ?>
                        <div class="edit-delete-buttons">
                            <a href="edit_product.php?id=<?= $product['id'] ?>" class="edit-button">Edit</a>
                            <a href="classes/delete_item.php?id=<?= $product['id'] ?>" class="delete-button">Delete</a>
                         </div>
                    <?php else: ?>
                        <a href="#" class="buy-button">Buy</a>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Please <a href="classes/login.php">log in</a> to buy this product.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
