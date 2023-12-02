<?php
session_start(); // Ініціалізація сесії

// Перевірка, чи користувач увійшов в систему
$userIsAuthenticated = isset($_SESSION['user_id']);

// Якщо не увійшов в систему, перенаправте на сторінку входу
if (!$userIsAuthenticated) {
    header('Location: /MarketTry/classes/login.php');
    exit;
}

// Перевірка методу запиту
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обробка додавання товару

    // Перевірка наявності обов'язкових даних
    if (!isset($_POST['name'], $_POST['price'], $_FILES['image'])) {
        echo 'Missing data';
        exit;
    }

    // Отримання даних з POST-запиту
    $name = $_POST['name'];
    $price = $_POST['price'];
    $opis = isset($_POST['opis']) ? $_POST['opis'] : ''; // Додано поле 'opis'
    $liczba_sztuk = isset($_POST['liczba_sztuk']) ? intval($_POST['liczba_sztuk']) : 1; // Значення за замовчуванням - 1

    // Отримання ідентифікатора користувача з сесії
    $userId = $_SESSION['user_id'];

    // Підключення до бази даних
    require 'classes/PdoConnect.php';
    $pdo = PdoConnect::getInstance()->PDO;

    // Перевірка завантаження файлу
    if ($_FILES['image']['error'] === 0) {
        // Валідуємо тип файлу, якщо потрібно
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileType = finfo_file($fileInfo, $_FILES['image']['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($fileType, $allowedTypes)) {
            echo 'Invalid file type. Allowed types: jpg, png, gif';
            exit;
        }

        // Розміщення файлу у відповідну папку
        $uploadDirectory = 'static/img/';
        $uploadedFile = $uploadDirectory . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
            try {
                // Використання підготовлених запитів для запобігання SQL-ін'єкції
                $sql = 'INSERT INTO goods (name, price, image, user_id, opis, liczba_sztuk) 
                        VALUES (?, ?, ?, ?, ?, ?)';
                $stmt = $pdo->prepare($sql);

                // Значення за замовчуванням для інших полів
                $opis = $opis ?: '';
                $stmt->execute([$name, $price, $uploadedFile, $userId, $opis, $liczba_sztuk]);

                // Перенаправлення на головну сторінку після додавання товару
                header('Location: index.php');
                exit;
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            echo 'File upload failed';
            exit;
        }
    } else {
        echo 'Error uploading file';
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
    <link rel="stylesheet" type="text/css" href="/MarketTry/static/css/add_style.css">
</head>
<body>
    <?php include 'includes/navbar.html'; ?>
    <div class="body-container">
        <div class="page">
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
                        <input type="text" id="price" name="price" required pattern="\d+(\.\d{2})?" title="Enter a valid price (e.g., 10.99)">
                    </div>

                    <div class="input-group">
                        <label for="liczba_sztuk">liczba_sztuk:</label>
                        <input type="number" id="liczba_sztuk" name="liczba_sztuk" value="1" min="1" required>
                    </div>

                    <div class="input-group">
                        <label for="opis">Product Description:</label>
                        <textarea id="opis" name="opis"></textarea>
                    </div>

                    <div class="input-group">
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image" required accept="image/*">
                    </div>

                    <!-- Додавайте інші поля за необхідності -->

                    <button class="login-button" type="submit">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
