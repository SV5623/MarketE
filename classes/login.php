<?php
// Ensure that the session is started
session_start();


// Include the PdoConnect class
require '../classes/PdoConnect.php';




// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user inputs
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $login_token = $_POST['login_token'] ?? '';

    // Validate inputs
    if (empty($username) || empty($password) || empty($login_token) || $login_token !== $_SESSION['login_token']) {
        // Invalid token, possibly an attack or error, handle accordingly
        $error_message = 'Invalid token. Please try again.';
    } else {
        // Remove the token after usage (password is already verified)
        unset($_SESSION['login_token']);

        // Your existing logic to validate and insert into the database goes here

        // For demonstration purposes, let's assume the data is valid
        // Check if the username exists in the database
        $pdo = PdoConnect::getInstance()->PDO;
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Username and password are correct, set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];

            // Redirect the user to the online store or another page
            header('Location: ../index.php');
            exit;
        } else {
            // Display an error message if login fails
            $error_message = 'Invalid username or password. Please try again.';
        }
    }
}

// Generate a login token for form submission
$login_token = bin2hex(random_bytes(32));
$_SESSION['login_token'] = $login_token;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="/MarketTry/static/css/login_style.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit-id.js" crossorigin="anonymous"></script>
</head>

<body>
    
    <div class="login-container" id="login-container">
    <label class="theme-switch" for="theme-toggle">
        <input type="checkbox" id="theme-toggle" onclick="toggleTheme()">
        <div class="slider round"></div>
    </label>


        <h2>Login</h2>
        <form action="/MarketTry/classes/login.php" method="post">

            <div class="input-group">
                <label for="username">Your username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="input-group">
                <label for="password">Password:</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" required>
                    <button type="button" class="show-password" onclick="togglePassword('password')">👁</button>
                </div>
            </div>

            <!-- Add the login token to the form -->
            <input type="hidden" name="login_token" value="<?= $login_token ?>">

            <button class="login-button" type="submit">Login</button>
        </form>
        <p class="signup-link">No account? <a href="singup.php">Create new one</a></p>
        <?php
        // Display error message if any
        if (isset($error_message)) {
            echo '<p class="error-message">' . $error_message . '</p>';
        }
        ?>
    </div>

    <script>
    function togglePassword(inputId) {
        var passwordInput = document.getElementById(inputId);
        passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
    }

    function toggleTheme() {
        var body = document.body;
        body.classList.toggle('dark-mode');

        // Додайте код для зміни стилів в темному режимі, якщо потрібно
    }
</script>


</body>
</html>
