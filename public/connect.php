<?php
//$dayOfWeek = date('N');
//$currentTime = date('H:i');
//$cutoffTime = '16:00';
//
//if ($dayOfWeek == 5 && $currentTime > $cutoffTime) {
//    $deliveryText = "Bestel nu, dinsdag thuis!";
//} elseif ($dayOfWeek == 6 || $dayOfWeek == 7) {
//    $deliveryText = "Bestel vandaag, dinsdag thuis!";
//} elseif ($dayOfWeek == 5 && $currentTime <= $cutoffTime) {
//    $deliveryText = "Bestel vandaag, zaterdag thuis!";
//} else {
//    $deliveryText = "Vandaag besteld voor 16:00, morgen thuis!";
//}
//
//// Verbinding met de database
//try {
//    $db = new PDO("mysql:host=localhost;dbname=scooters", "root", "");
//    // Haal alle reviews op
//    $query = $db->prepare("SELECT * FROM reviews");
//    $query->execute();
//    $reviews = $query->fetchAll(PDO::FETCH_ASSOC);
//    // Haal de recentste reviews op
//    $reviewQuery = $db->query("SELECT * FROM reviews ORDER BY datum DESC LIMIT 6");
//    $recentReviews = $reviewQuery->fetchAll(PDO::FETCH_ASSOC);
//
//// Haal de scooters op
//    $scooterQuery = $db->query("SELECT * FROM scooter_brands");
//    $scooters = $scooterQuery->fetchAll(PDO::FETCH_ASSOC);
//
//    // Haal gegevens op van een specifieke scooter
//    $scooterId = isset($_GET['id']) ? intval($_GET['id']) : 0;
//    if ($scooterId > 0) {
//        $stmt = $db->prepare("SELECT * FROM reviews WHERE Scooter_id = :id");
//        $stmt->bindParam(':id', $scooterId, PDO::PARAM_INT);
//        $stmt->execute();
//        $data = $stmt->fetch(PDO::FETCH_ASSOC);
//    }
//
//
//    // Review toevoegen
//    if (isset($_POST['submit'])) {
//        $scooterId = $_POST['scooter_id'];
//        $naam = $_POST['naam'];
//        $sterren = $_POST['sterren'];
//        $review_tekst = $_POST['review_tekst'];
//        $datum = date('Y-m-d H:i:s'); // Huidige tijd
//
//        // Voorbereiden en uitvoeren van de SQL-insert
//        $stmt = $db->prepare("INSERT INTO reviews (scooter_id, naam, sterren, review_tekst, datum) VALUES (?, ?, ?, ?, ?)");
//        if ($stmt->execute([$scooterId, $naam, $sterren, $review_tekst, $datum])) {
//            echo "Review succesvol opgeslagen!";
//        } else {
//            echo "Er is een fout opgetreden bij het opslaan van de review.";
//        }
//    }
////$reviews tonnen per product
//    $query = "SELECT * FROM reviews WHERE scooter_id = :scooter_id ORDER BY datum DESC LIMIT 10";
//    $stmt = $db->prepare($query);
//    $stmt->execute(['scooter_id' => $scooterId]);
//    $recentReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//
//    // Haal merk_id uit
//    $merk_id = isset($_GET['merk_id']) ? intval($_GET['merk_id']) : 0;
//
//// Als er een merk_id is, haal dan alleen scooters van dat merk op
//    if ($merk_id > 0) {
//        // Haal de naam van het merk op
//        $query = $db->prepare("SELECT naam FROM merk WHERE id = :merk_id");
//        $query->bindParam(':merk_id', $merk_id, PDO::PARAM_INT);
//        $query->execute();
//        $merk = $query->fetch(PDO::FETCH_ASSOC);
//
//        // Haal de scooters op voor het geselecteerde merk
//        $query = $db->prepare("SELECT * FROM scooter_brands WHERE merk_id = :merk_id");
//        $query->bindParam(':merk_id', $merk_id, PDO::PARAM_INT);
//        $query->execute();
//        $scooters = $query->fetchAll(PDO::FETCH_ASSOC);
//    }
//
//} catch (PDOException $e) {
//    echo "Fout bij verbinding: " . $e->getMessage();
//}
//?>
<!---->
<!---->
