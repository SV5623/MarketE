<?php
session_start();
require 'PdoConnect.php'; // Правильно підключіть файл PdoConnect
echo "Debug Info:<br>";
echo "loggedin: " . $_SESSION['loggedin'] . "<br>";
echo "purchase_success: " . $_SESSION['purchase_success'] . "<br>";
// Додайте інші вивідні дані за потреби
// Перевірка, чи користувач увійшов в систему, чи чи покупка була успішною
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['purchase_success'])) {
    // Якщо не увійшов в систему або покупка не була успішною, перенаправте на сторінку входу
    header('Location: login.php');
    exit;
}

// Отримання додаткової інформації про покупку з бази даних
// Можливо, у вас є таблиця історії покупок або подібна для отримання деталей

// Приклад: Отримання деталей придбаного товару
$purchaseItemId = $_SESSION['purchase_item_id'];
$purchaseItemName = ''; // Отримайте назву товару з бази даних на основі $purchaseItemId

// Приклад: Отримання електронної адреси продавця
$sellerEmail = ''; // Отримайте електронну адресу продавця з бази даних на основі $purchaseItemId

// Очистіть прапорець успіху покупки, щоб уникнути відображення цієї сторінки при оновленні
unset($_SESSION['purchase_success']);

// Включіть будь-яку додаткову логіку, яку ви потребуєте для відображення деталей покупки
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Success</title>
    <!-- Включіть ваші таблиці стилів CSS, якщо потрібно -->
</head>
<body class="dark-mode">
    <?php include 'navbar.html'; ?>

    <div class="body-container">
        <div class="page">
            <div class="success-container">
                <h2>Purchase Successful</h2>
                <p>Thank you for your purchase! Here are the details:</p>
                <p><strong>Item Name:</strong> <?php echo $purchaseItemName; ?></p>
                <p><strong>Seller's Email:</strong> <?php echo $sellerEmail; ?></p>
                <!-- Додайте більше деталей за потреби -->
                <p><a href="index.php">Go to Home</a></p>
            </div>
        </div>
    </div>

    <!-- Включіть ваші файли JavaScript, якщо потрібно -->
</body>
</html>
