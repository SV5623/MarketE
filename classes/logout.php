<?php
session_start();

// Destroy all data in the session
session_destroy();

// Unset and destroy the loggedin session variable
unset($_SESSION['loggedin']);

// Redirect the user to the home page or another page as desired
header("Location: /MarketTry/index.php");
exit();
?>
