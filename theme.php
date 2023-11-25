<?php

// Перевірка, чи прийшов запит на зміну теми
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Перевірка, чи передано параметр toggleTheme
    if (isset($_POST['toggleTheme'])) {
        // Отримання значення toggleTheme
        $toggleTheme = $_POST['toggleTheme'];

        // Збереження значення toggleTheme в сесії та localStorage
        session_start();
        $_SESSION['darkMode'] = $toggleTheme;

        // Повернення відповіді у форматі JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'darkMode' => $toggleTheme]);
        exit;
    }
}

// Якщо це GET-запит, повернемо інформацію про тему
session_start();
$darkMode = isset($_SESSION['darkMode']) ? $_SESSION['darkMode'] : false;

// Повернення інформації у форматі JSON
header('Content-Type: application/json');
echo json_encode(['darkMode' => $darkMode]);
