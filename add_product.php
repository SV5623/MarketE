<?php
session_start(); // Ініціалізація сесії

// Перевірка чи користувач увійшов в систему
if (!isset($_SESSION['user_id'])) {
    // Якщо не увійшов, перенаправляємо його на сторінку входу
    header('Location: classes/login.php');
    exit;
}

// Перевірка методу запиту
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обробка додавання товару

    // Перевірка наявності необхідних даних
    if (!isset($_POST['name'], $_POST['price']) || empty($_FILES['image'])) {
        echo 'Missing data';
        exit;
    }

    // Отримання даних з POST-запиту
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Отримання id користувача з сесії
    $userId = $_SESSION['user_id'];

    // Підключення до бази даних
    require 'classes/PdoConnect.php';
    $pdo = PdoConnect::getInstance()->PDO;

    // Обробка завантаження файлу
    $uploadDirectory = 'static/img/'; // Папка для завантажених файлів
    $uploadedFile = $uploadDirectory . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
        // Файл успішно завантажено, тепер додаємо товар до бази даних
        $sql = 'INSERT INTO goods (name, price, image, user_id) VALUES (?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $price, $uploadedFile, $userId]);

        // Перенаправлення на головну сторінку після додавання товару
        header('Location: index.php');
        exit;
    } else {
        echo 'File upload failed';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="/MarketTry/static/css/login_style.css">
</head>
<body>
    <div class="login-container">
        <h2>Add Product</h2>
        
        <!-- Форма додавання товару -->
        <form action="/MarketTry/add_product.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="input-group">
                <label for="price">Product Price:</label>
                <input type="text" id="price" name="price" required>
            </div>
            
            <div class="input-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" required accept="image/*">
            </div>
            <!-- Додайте сюди інші поля, якщо потрібно -->

            <button class="login-button" type="submit">Add Product</button>
        </form>

        <?php
        // Виведення повідомлення про помилку, якщо таке є
        if (isset($error_message)) {
            echo '<p class="error-message">' . $error_message . '</p>';
        }
        ?>
    </div>
</body>
</html>
