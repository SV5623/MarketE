<?php
session_start();
require __DIR__ . '/../classes/PdoConnect.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Отримання інформації про активного користувача
if (isset($_SESSION['user_id'])) {
    $activeUserMessage = "Активний користувач: {$_SESSION['user_id']}";
} else {
    $activeUserMessage = "Користувач не увійшов в систему";
}

echo $activeUserMessage;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $goods_id = isset($_POST['goods_id']) ? $_POST['goods_id'] : 0;
    $liczba_sztuk = isset($_POST['liczba_sztuk']) ? intval($_POST['liczba_sztuk']) : 1;

    if ($user_id && $goods_id) {
        // Перевірка, чи існує товар з вказаним ідентифікатором
        if (goodsExists($goods_id)) {
            // Отримання інформації про користувача та товар для сповіщень по електронній пошті
            $user_info = getUserInfo($user_id);
            $goods_info = getGoodsInfo($goods_id);

            // Перевірка, чи користувач не купує свій власний товар
            if ($user_id !== $goods_info['user_id']) {
                // Перевірка, чи є достатньо товару для покупки
                if ($goods_info['liczba_sztuk'] >= $liczba_sztuk) {
                    $pdo = PdoConnect::getInstance()->PDO;
                    $stmt = $pdo->prepare("INSERT INTO purchases (user_id, goods_id, liczba_sztuk) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, $goods_id, $liczba_sztuk]);

                    if ($stmt->rowCount() > 0) {
                        // Оновлення кількості товару в БД
                        $newliczba_sztuk = $goods_info['liczba_sztuk'] - $liczba_sztuk;
                        $updateStmt = $pdo->prepare("UPDATE goods SET liczba_sztuk = ? WHERE id = ?");
                        $updateStmt->execute([$newliczba_sztuk, $goods_id]);

                        // Сповіщення користувача про покупку
                        sendPurchaseConfirmationEmail($user_info['email'], $goods_info['name'], $liczba_sztuk);

                        // Сповіщення власника товару
                        sendItemSoldEmail($goods_info['seller_email'], $goods_info['name'], $user_info['username'], $liczba_sztuk);

                        // Видалення товарів з нульовою кількістю штук
                        deleteZeroQuantityGoods();

                        header('Location: success.php'); // Перенаправлення на сторінку успішної покупки
                        exit;
                    } else {
                        $error_message = 'Failed to process the purchase. Please try again.';
                    }
                } else {
                    $error_message = 'Not enough liczba_sztuk available for purchase.';
                }
            } else {
                $error_message = 'You cannot buy your own item.';
            }
        } else {
            $error_message = 'Selected goods do not exist.';
        }
    } else {
        $error_message = 'Invalid user or goods information.';
    }
}

// Функція перевірки існування товару за ідентифікатором
function goodsExists($goods_id) {
    $pdo = PdoConnect::getInstance()->PDO;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM goods WHERE id = ?");
    $stmt->execute([$goods_id]);
    $count = $stmt->fetchColumn();
    return $count > 0;
}

// Функція видалення товарів з нульовою кількістю штук
function deleteZeroQuantityGoods() {
    $pdo = PdoConnect::getInstance()->PDO;
    $zeroQuantityStmt = $pdo->prepare("DELETE FROM goods WHERE liczba_sztuk = 0");
    $zeroQuantityStmt->execute();
}


// Function to send an email confirmation of the purchase to the user
function sendPurchaseConfirmationEmail($user_email, $goods_name, $liczba_sztuk) {
    $mail = new PHPMailer(true);

    try {
        // Email server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '';
        $mail->Password   = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Set recipients and email content
        $mail->setFrom('maestro5623@gmail.com', 'Your Name');
        $mail->addAddress($user_email);
        $mail->isHTML(true);
        $mail->Subject = 'Purchase Confirmation';
        $mail->Body    = "Thank you for your purchase!<br>You have successfully purchased $liczba_sztuk unit(s) of: $goods_name";

        // Send the email
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Function to get user information based on the ID
function getUserInfo($user_id) {
    $pdo = PdoConnect::getInstance()->PDO;
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get goods information based on the ID
function getGoodsInfo($goods_id) {
    $pdo = PdoConnect::getInstance()->PDO;
    $stmt = $pdo->prepare("SELECT name, seller_email, liczba_sztuk, user_id FROM goods WHERE id = ?");
    $stmt->execute([$goods_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to send an email notification to the owner of the goods
function sendItemSoldEmail($owner_email, $goods_name, $buyer_username, $liczba_sztuk) {
    $subject = "Item Sold";
    $body = "Your item has been sold!\n";
    $body .= "Your item: $goods_name has been purchased by: $buyer_username ($liczba_sztuk unit(s))";
    mail($owner_email, $subject, $body);
}


// Get the list of goods for the form
$pdo = PdoConnect::getInstance()->PDO;
$goods_stmt = $pdo->query("SELECT id, name, liczba_sztuk FROM goods");
$goods_list = $goods_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase</title>
    <link rel="stylesheet" type="text/css" href="/MarketTry/static/css/purchase_style.css">
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
                                <option value="<?= $goods['id'] ?>" data-liczba_sztuk="<?= $goods['liczba_sztuk'] ?>"><?= $goods['name'] ?> (Available: <?= $goods['liczba_sztuk'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="liczba_sztuk">liczba_sztuk:</label>
                        <input type="number" id="liczba_sztuk" name="liczba_sztuk" value="1" min="1" max="<?= $goods['liczba_sztuk'] ?>" required>
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
