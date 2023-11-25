<?php
session_start(); // Initialize the session

// Check if the user is logged in
$userIsAuthenticated = isset($_SESSION['user_id']);

// If not logged in, redirect to the login page
if (!$userIsAuthenticated) {
    header('Location: /MarketTry/classes/login.php');
    exit;
}


// Check the request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle adding a product

    // Check for the presence of required data
    if (!isset($_POST['name'], $_POST['price']) || empty($_FILES['image'])) {
        echo 'Missing data';
        exit;
    }

    // Get data from the POST request
    $name = $_POST['name'];
    $price = $_POST['price'];
    $opis = isset($_POST['opis']) ? $_POST['opis'] : ''; // Added 'opis' field

    // Get the user id from the session
    $userId = $_SESSION['user_id'];

    // Connect to the database
    require 'classes/PdoConnect.php';
    $pdo = PdoConnect::getInstance()->PDO;

    // Process file upload
    $uploadDirectory = 'static/img/';
    $uploadedFile = $uploadDirectory . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
        try {
            // Use prepared statements to prevent SQL injection
            $sql = 'INSERT INTO goods (name, price, image, user_id, opis, kategoria, liczba_sztuk, kraj, kod_pocztowy, stan) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = $pdo->prepare($sql);

            // Default values for other fields
            $kategoria = 'supermarket';
            $liczba_sztuk = 12;
            $kraj = 'Polska';
            $kod_pocztowy = '66-400';
            $stan = 'nowy';

            $stmt->execute([$name, $price, $uploadedFile, $userId, $opis, $kategoria, $liczba_sztuk, $kraj, $kod_pocztowy, $stan]);

            // Redirect to the main page after adding the product
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'File upload failed';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <!-- Посилання на бібліотеку Bootstrap -->
    <link rel="stylesheet" type="text/css" href="/MarketTry/static/css/add_style.css">
</head>
<body>
    <?php include 'includes/navbar.html'; ?>
<div class="body-container">
    <div class="page">
        <div class="login-container">
            <h2>Add Product</h2>
            
            <!-- Add product form -->
            <form action="/MarketTry/add_product.php" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="input-group">
                    <label for="price">Product Price:</label>
                    <input type="text" id="price" name="price" required pattern="\d+(\.\d{2})?" title="Enter a valid price (e.g., 10.99)">
                </div>


                <div class="input-group">
                    <label for="opis">Product Description:</label>
                    <textarea id="opis" name="opis"></textarea>
                </div>

                <div class="input-group">
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" required accept="image/*">
                </div>

                <!-- Add other fields here if needed -->

                <button class="login-button" type="submit">Add Product</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
