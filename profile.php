<?php
session_start(); // Ініціалізація сесії

// Підключення до бази даних
require 'classes/PdoConnect.php';
$pdo = PdoConnect::getInstance()->PDO;

$userIsAuthenticated = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Визначення імені користувача
$username = '';

if ($userIsAuthenticated) {
    // Якщо користувач авторизований, отримати ім'я з бази даних
    $userId = $_SESSION['user_id']; // Припускається, що user_id зберігається в сесії при авторизації

    // Запит до бази даних для отримання імені
    $sql = "SELECT username FROM users WHERE id = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData) {
        $username = $userData['username'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="/MarketTry/static/css/profile_style.css">
</head>
<body>

<div class="profile-container">
    <?php if ($userIsAuthenticated): ?>
        <h2>Hello, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>This is your profile page.</p>
        <p><a href="logout.php">Logout</a></p>
    <?php else: ?>
        <p>You are not logged in. <a href="login.php">Log in</a> or <a href="register.php">register</a>.</p>
    <?php endif; ?>
</div>

<!-- Вставте інші блоки HTML або код сторінки тут -->

</body>
</html>
