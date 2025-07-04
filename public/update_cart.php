<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scooter_id = $_POST['scooter_id'];
    $amount = $_POST['amount'];

    // Werk de hoeveelheid bij in de sessie
    if (isset($_SESSION['cart'][$scooter_id])) {
        $_SESSION['cart'][$scooter_id]['aantal'] = (int)$amount;

        // Bereken de nieuwe totaalprijs
        $updatedTotal = 0;
        foreach ($_SESSION['cart'] as $scooter) {
            $updatedTotal += $scooter['prijs'] * $scooter['aantal'];
        }

        // Stuur een JSON-respons terug naar de browser
        echo json_encode([
            'updatedAmount' => $_SESSION['cart'][$scooter_id]['aantal'],
            'updatedTotal' => number_format($updatedTotal, 2, ',', '.')
        ]);
        exit;
    }
}