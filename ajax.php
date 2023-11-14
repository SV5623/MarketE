<?php

require 'PdoConnect.php';

if (
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {
    $requestData = $_POST;

    $errors = array();

    if (!$requestData['id'])
        $errors[] = 'Product ID is missing';

    if (!$requestData['fio'])
        $errors[] = 'The "Your Name" field is required';

    if (!$requestData['phone'] && !$requestData['email'])
        $errors[] = 'You must fill in at least one of the "Phone" or "Email" fields';

    $response = array();

    if ($errors) {
        $response['errors'] = $errors;
    } else {
        $PDO = PdoConnect::getInstance();

        // Отримання інформації про товар
        $sqlProduct = "SELECT * FROM goods WHERE id = ?";
        $stmtProduct = $PDO->PDO->prepare($sqlProduct);
        $stmtProduct->execute([$requestData['id']]);
        $product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            $response['errors'][] = 'Product not found';
        } else {
            // Збереження інформації про замовлення
            $sqlOrder = "INSERT INTO `orders` SET 
                `fio` = :fio, 
                `phone` = :phone, 
                `email` = :email, 
                `comment` = :comment, 
                `product_id` = :id,
                `product_name` = :product_name,
                `product_price` = :product_price";

            $set = $PDO->PDO->prepare($sqlOrder);
            $set->bindValue(':fio', $requestData['fio']);
            $set->bindValue(':phone', $requestData['phone']);
            $set->bindValue(':email', $requestData['email']);
            $set->bindValue(':comment', $requestData['comment']);
            $set->bindValue(':id', $requestData['id']);
            $set->bindValue(':product_name', $product['name']);
            $set->bindValue(':product_price', $product['price']);

            $response['res'] = $set->execute();

            if ($response['res']) {
                $message = "
                    New order has been placed.
                    Product with ID:" . $requestData['id'] . " has been ordered by " . $requestData['fio'];

                mail('ksv05623@gmail.com', 'New order has been placed', $message, 'FROM: admin@happynewyear.mydev');
            }
        }
    }

    echo json_encode($response);
}
?>
