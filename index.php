<?php

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

// Отримуємо поточну дату
$currentDate = date('m.d', time());
$currentDateArray = explode('.', $currentDate);

$currentMounth = $currentDateArray[0];
$currentDay = $currentDateArray[1];

// Ваша попередня умова перевірки на таймер
// if ($currentMounth == 12 && $currentDay >= 24) {
//     $PDO = PdoConnect::getInstance();
//     $result = $PDO->PDO->query("SELECT * FROM `goods`");
//     $products = array();
//     while ($productInfo = $result->fetch()) {
//         $products[] = $productInfo;
//     }
//     include 'online_store.php';
// } else {
    // Якщо умова не виконується, завжди відображаємо онлайн магазин
    $PDO = PdoConnect::getInstance();
    $result = $PDO->PDO->query("SELECT * FROM `goods`");
    $products = array();
    while ($productInfo = $result->fetch()) {
        $products[] = $productInfo;
    }
    include 'online_store.php';
// }
