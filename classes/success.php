<?php
session_start();
require 'PdoConnect';

// Check if the user is logged in or if the purchase was successful
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['purchase_success'])) {
    // If not logged in or purchase not successful, redirect to login page
    header('Location: login.php');
    exit;
}

// Retrieve additional information about the purchase from the database
// You might have a purchase history table or similar to fetch details

// Example: Fetching the purchased item details
$purchaseItemId = $_SESSION['purchase_item_id'];
$purchaseItemName = ''; // Fetch item name from the database based on $purchaseItemId

// Example: Fetching the seller's email
$sellerEmail = ''; // Fetch seller's email from the database based on $purchaseItemId

// Clear the purchase success flag to prevent showing this page on a refresh
unset($_SESSION['purchase_success']);

// Include any additional logic you need for displaying purchase details

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Success</title>
    <!-- Include your CSS stylesheets if needed -->
</head>
<body class="dark-mode">
    <?php include 'navbar.html'; ?>

    <div class="body-container">
        <div class="page">
            <div class="success-container">
                <h2>Purchase Successful</h2>
                <p>Thank you for your purchase! Here are the details:</p>
                <p><strong>Item Name:</strong> <?php echo $purchaseItemName; ?></p>
                <p><strong>Seller's Email:</strong> <?php echo $sellerEmail; ?></p>
                <!-- Add more details as needed -->
                <p><a href="index.php">Go to Home</a></p>
            </div>
        </div>
    </div>

    <!-- Include your JavaScript files if needed -->
</body>
</html>
