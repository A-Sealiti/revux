<?php
session_start();
require 'modules/database.php';
include_once 'modules/functions.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = $pdo->prepare("DELETE FROM merk WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $_SESSION['message'] = "Categorie succesvol verwijderd.";
    header("Location: categorie.php");
    exit();
}
?>
