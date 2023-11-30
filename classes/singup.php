<?php
session_start();
require '../classes/PdoConnect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : ''; // Додано отримання значення email
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $repeatPassword = isset($_POST['repeatPassword']) ? $_POST['repeatPassword'] : '';
    $singup_token = isset($_POST['singup_token']) ? $_POST['singup_token'] : ''; // Виправлено орфографічну помилку

    if (empty($username) || empty($email) || empty($password) || empty($repeatPassword)) {
        $error_message = 'All fields are required.';
    } elseif ($password !== $repeatPassword) {
        $error_message = 'Passwords do not match.';
    } elseif (empty($singup_token) || $singup_token !== $_SESSION['singup_token']) {
        $error_message = 'Invalid token. Please try again.';
    } else {
        unset($_SESSION['singup_token']);

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $pdo = PdoConnect::getInstance()->PDO;
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);

        if ($stmt->rowCount() > 0) {
            header('Location: login.php');
            exit;
        } else {
            $error_message = 'Failed to create user. Please try again.';
        }
    }
}


$singup_token = bin2hex(random_bytes(32));
$_SESSION['singup_token'] = $singup_token;

try {
    // SQL query execution
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sing Up</title>
    <link rel="stylesheet" type="text/css" href="/MarketTry/static/css/login_style.css">

    <script src="https://kit.fontawesome.com/your-fontawesome-kit-id.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include '../includes/navbar.html'; ?>

    <div class="body-container">
        <div class="page">
            <div class="login-container">
                <h2>sing Up</h2>
                <form action="/MarketTry/classes/singup.php" method="post">
                    <div class="input-group">
                        <label for="username">Your username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="input-group">
                        <label for="email">Your email address:</label>
                        <input type="text" id="email" name="email" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Password:</label>
                        <div class="password-input-container">
                            <input type="password" id="password" name="password" required>
                            <button type="button" class="show-password" onclick="togglePassword('password')">👁</button>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="repeatPassword">Repeat Password:</label>
                        <div class="password-input-container">
                            <input type="password" id="repeatPassword" name="repeatPassword" required>
                            <button type="button" class="show-password" onclick="togglePassword('repeatPassword')">👁</button>
                        </div>
                    </div>

                    <!-- Add the singup token to the form -->
                    <input type="hidden" name="singup_token" value="<?= $singup_token ?>">

                    <button class="login-button" type="submit">Create</button>
                </form>
                <p class="singup-link">Already have an account? <a href="login.php">Log in to your account</a></p>
                <?php
                if (isset($error_message)) {
                    echo '<p class="error-message">' . $error_message . '</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            var passwordInput = document.getElementById(inputId);
            passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
        }
    </script>
</body>
</html>
