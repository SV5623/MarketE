<?php
session_start();

// Перевірка, чи користувач авторизований
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $userIsAuthenticated = true;
} else {
    $userIsAuthenticated = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Store</title>
</head>
<body>

    <?php
    // Виведення повідомлення про успішний вхід
    if ($userIsAuthenticated && isset($_SESSION['message'])) {
        echo '<div class="MessageShow">' . $_SESSION['message'] . '</div>';
        // Очистіть повідомлення після виведення
        unset($_SESSION['message']);
    }
    ?>

    <h1>Online Store</h1>

    <?php if ($userIsAuthenticated): ?>
        <p>Ви вже увійшли в систему.</p>
        <p><a href="classes/logout.php">Вийти</a></p>
    <?php else: ?>
        <p><a href="classes/login.php">Увійти в систему</a></p>
    <?php endif; ?>

</body>
</html>
