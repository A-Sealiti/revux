<?php

session_start();
// Controleer of de scooter_id is ingesteld en niet leeg is
if (isset($_POST['scooter_id']) && !empty($_POST['scooter_id'])) {
    $scooter_id = $_POST['scooter_id'];

    // Controleer of de scooter in de winkelwagentje zit en verwijder deze
    if (isset($_SESSION['cart'][$scooter_id])) {
        unset($_SESSION['cart'][$scooter_id]);
    }
}


// Redirect terug naar het winkelwagentje
header('Location: cart.php');
exit();