<?php
session_start(); // Ініціалізація сесії

// Підключення до бази даних
require 'classes/PdoConnect.php';
$pdo = PdoConnect::getInstance()->PDO;

// Запит для отримання всіх товарів та їх характеристик
$sql = "SELECT * FROM goods";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="/MarketTry/static/css/product.css">
</head>
<body>
<?php include 'includes/navbar.html'; ?>
<div class="container">
    <h2>Product List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>User_ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <!-- Додайте інші характеристики товарів -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo $product['user_id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['opis']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                
                    <!-- Додайте інші комірки для інших характеристик товарів -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
