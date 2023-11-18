<?php
session_start();

// Підключення до бази даних
require 'classes/PdoConnect.php';
$pdo = PdoConnect::getInstance()->PDO;

$userIsAuthenticated = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Визначення імені користувача
$username = '';

if ($userIsAuthenticated) {
    $userId = $_SESSION['user_id'];

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
    <link rel="stylesheet" href="/MarketTry/static/css/profile.css">
</head>

<body>
    <?php include 'includes/navbar.html'; ?>

    <div class="profile-container">
        <?php if ($userIsAuthenticated): ?>
            <h2>Hello, <?php echo htmlspecialchars($username); ?>!</h2>
            <p>This is your profile page.</p>
            <p><a href="classes/logout.php" class="logout-link">Logout</a></p>
        <?php else: ?>
            <p class="profile-message">You are not logged in. <a href="classes/login.php" class="link-to-styles">Log in</a> or <a href="classes/signup.php" class="link-to-styles">register</a>.</p>
        <?php endif; ?>
    </div>
    
</body>

</html>
