<?php
session_start();
require '../classes/PdoConnect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $goods_id = isset($_POST['goods_id']) ? $_POST['goods_id'] : 0;

    if ($user_id && $goods_id) {
        $pdo = PdoConnect::getInstance()->PDO;
        $stmt = $pdo->prepare("INSERT INTO purchases (user_id, goods_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $goods_id]);

        if ($stmt->rowCount() > 0) {
            // Get user and goods information for email notifications
            $user_info = getUserInfo($user_id);
            $goods_info = getGoodsInfo($goods_id);

            // Send email to the user
            $user_email_subject = "Purchase Confirmation";
            $user_email_body = "Thank you for your purchase!\n";
            $user_email_body .= "You have successfully purchased: " . $goods_info['name'];

            mail($user_info['email'], $user_email_subject, $user_email_body);

            // Send email to the goods owner
            $owner_email_subject = "Item Sold";
            $owner_email_body = "Your item has been sold!\n";
            $owner_email_body .= "Your item: " . $goods_info['name'] . " has been purchased by: " . $user_info['username'];

            mail($goods_info['seller_email'], $owner_email_subject, $owner_email_body);

            header('Location: success.php'); // Redirect to a success page
            exit;
        } else {
            $error_message = 'Failed to process the purchase. Please try again.';
        }
    } else {
        $error_message = 'Invalid user or goods information.';
    }
}

// Function to get user information
function getUserInfo($user_id) {
    $pdo = PdoConnect::getInstance()->PDO;
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get goods information
function getGoodsInfo($goods_id) {
    $pdo = PdoConnect::getInstance()->PDO;
    $stmt = $pdo->prepare("SELECT name, seller_email FROM goods WHERE id = ?");
    $stmt->execute([$goods_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get goods list for the form
$pdo = PdoConnect::getInstance()->PDO;
$goods_stmt = $pdo->query("SELECT id, name FROM goods");
$goods_list = $goods_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase</title>
    <link rel="stylesheet" type="text/css" href="/MarketTry/static/css/purchase_style.css"> <!-- Add your purchase page styles -->
</head>

<body class="dark-mode">
    <?php include '../includes/navbar.html'; ?>

    <div class="body-container">
        <div class="page">
            <div class="purchase-container">
                <h2>Make a Purchase</h2>
                <form action="/MarketTry/classes/purchase.php" method="post">
                    <div class="input-group">
                        <label for="goods_id">Select the item to purchase:</label>
                        <select id="goods_id" name="goods_id" required>
                            <?php foreach ($goods_list as $goods): ?>
                                <option value="<?= $goods['id'] ?>"><?= $goods['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="purchase-button" type="submit">Purchase</button>
                </form>
                <?php
                if (isset($error_message)) {
                    echo '<p class="error-message">' . $error_message . '</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
