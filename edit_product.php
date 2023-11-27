<?php
session_start();

require 'classes/PdoConnect.php';

$userIsAuthenticated = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Перевірка, чи користувач увійшов в систему
if (!$userIsAuthenticated) {
    header('Location: login.php');
    exit;
}

// Отримання ідентифікатора продукту з параметрів запиту
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header('Location: index.php');
    exit;
}

$pdo = PdoConnect::getInstance()->PDO;

// Отримання інформації про продукт
$sql = 'SELECT * FROM goods WHERE id = ? AND user_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id, $_SESSION['user_id']]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Перевірка, чи продукт існує та чи користувач є його власником
if (!$product) {
    header('Location: index.php');
    exit;
}

// Логіка обробки форми редагування
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримання нових даних з форми
    $newName = $_POST['name'];
    $newOpis = $_POST['opis'];
    $newPrice = $_POST['price'];

    // Оновлення інформації про продукт у базі даних
    $updateSql = "UPDATE goods SET name = :name, opis = :opis, price = :price WHERE id = :id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $updateStmt->bindParam(':name', $newName, PDO::PARAM_STR);
    $updateStmt->bindParam(':opis', $newOpis, PDO::PARAM_STR);
    $updateStmt->bindParam(':price', $newPrice, PDO::PARAM_STR);

    if ($updateStmt->execute()) {
        // Вдале оновлення, можна вивести повідомлення або перенаправити на іншу сторінку
        header('Location: index.php');
        exit;
    } else {
        // Помилка оновлення
        echo "Error updating product";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="/MarketTry/static/css/edit.css">
</head>
<body>

<!-- Підключення navbar -->
<?php include 'includes/navbar.html'; ?>

<div class="container">
    <h2>Edit Product</h2>
    <!-- Форма для редагування продукту -->
    <form method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>

        <label for="opis">Opis:</label>
        <textarea id="opis" name="opis" required><?= htmlspecialchars($product['opis']); ?></textarea>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>

        <button type="submit">Save Changes</button>
    </form>
</div>
</body>
</html>
