<?php

require 'PdoConnect.php';

if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
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

        $sql = "INSERT INTO `orders` SET `fio` = :fio, `phone` = :phone, `email` = :email, `comment` = :comment, `product_id` = :id";

        $set = $PDO->PDO->prepare($sql);
        $response['res'] = $set->execute($requestData);

        if ($response['res']) {
            $message = "
                New order has been placed.
                Product with ID:" . $requestData['id'] . " has been ordered by " . $requestData['fio'];

            mail('ksv05623@gmail.com', 'New order has been placed', $message, 'FROM: admin@happynewyear.mydev');
        }
    }

    echo json_encode($response);
}
?>
