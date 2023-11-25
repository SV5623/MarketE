<?php
session_start();
require 'PdoConnect.php';

$pdo = PdoConnect::getInstance()->PDO;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Пряме взяття ID (потрібно обробити додатково для безпеки)
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    // Визначення шляху до файлу зображення
    $sqlImagePath = "SELECT image FROM goods WHERE id = :id";
    $stmtImagePath = $pdo->prepare($sqlImagePath);
    $stmtImagePath->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmtImagePath->execute();
    $imagePathRelative = $stmtImagePath->fetchColumn();

    // Повний шлях до файлу зображення в системі
    $imagePath = __DIR__ . '/' . $imagePathRelative;

    // Використовуйте підготовлені заявки для запобігання SQL ін'єкцій
    $sqlDelete = "DELETE FROM goods WHERE id = :id";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->bindParam(':id', $product_id, PDO::PARAM_INT);

    // Виконання SQL-запиту для видалення товару
    $stmtDelete->execute();

    // Видалення зображення, якщо файл існує
    if (isset($imagePath) && file_exists($imagePath)) {
        if (unlink($imagePath)) {
            echo "Image File Deleted Successfully";
        } else {
            echo "Error Deleting Image File";
        }
    } else {
        echo "Image File Not Found";
    }

    echo "Товар успішно видалено";
} catch (PDOException $e) {
    echo "Помилка видалення товару: " . $e->getMessage();
}

// Add a simple redirect to MarketTry/index.php
header('Location: /MarketTry/index.php');
exit;
?>
