<?php
include_once 'classes/user.php';
session_start();
require_once 'modules/database.php';

if (isset($_POST['scooter_id']) && isset($_SESSION['user'])) {
    $scooter_id = (int)$_POST['scooter_id'];
    $user_id = $_SESSION['user']->id;

    // Controleer of de scooter al is geliket
    $query = $pdo->prepare("SELECT * FROM favorite WHERE user_id = :user_id AND scooter_id = :scooter_id");
    $query->execute(['user_id' => $user_id, 'scooter_id' => $scooter_id]);

    if ($query->rowCount() > 0) {
        // Verwijder uit favorieten
        $delete = $pdo->prepare("DELETE FROM favorite WHERE user_id = :user_id AND scooter_id = :scooter_id");
        $delete->execute(['user_id' => $user_id, 'scooter_id' => $scooter_id]);
        echo "removed";
    } else {
        // Voeg toe aan favorieten
        $insert = $pdo->prepare("INSERT INTO favorite (user_id, scooter_id) VALUES (:user_id, :scooter_id)");
        $insert->execute(['user_id' => $user_id, 'scooter_id' => $scooter_id]);
        echo "added";
    }
} else {
    echo "error: user not logged in or scooter_id not set";
}
?>
