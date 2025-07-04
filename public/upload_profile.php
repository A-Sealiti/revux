<?php
session_start();
include_once 'modules/database.php'; // Zorg ervoor dat de databaseverbinding goed is
include_once 'modules/functions.php'; // Indien je functies nodig hebt zoals getUserData()


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['picture'])) {
    $userId = $_POST['user_id'];
    $targetDir = "uploads/";
    $fileName = basename($_FILES['picture']['name']);
    $targetFilePath = $targetDir . uniqid() . "_" . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Validatie van bestandstypen
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array(strtolower($fileType), $allowedTypes)) {
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetFilePath)) {
            // Update de profielfoto in de database
            $query = $pdo->prepare("UPDATE user SET picture = :picture WHERE id = :id");
            $query->bindParam(':picture', $targetFilePath);
            $query->bindParam(':id', $userId);
            if ($query->execute()) {
                header("Location: profile.php?status=success");
                exit;
            } else {
                echo "Er is een fout opgetreden bij het opslaan in de database.";
            }
        } else {
            echo "Bestand uploaden is mislukt.";
        }
    } else {
        echo "Ongeldig bestandstype. Upload alleen JPG, JPEG, PNG of GIF.";
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Controle voor het verwijderen van de profielfoto
    if (isset($_POST['delete_picture']) && isset($_POST['user_id'])) {
        $userId = $_POST['user_id'];

        // Zoek de huidige afbeelding in de database
        $query = "SELECT picture FROM user WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$userId]);
        $currentImage = $stmt->fetchColumn();

        // Verwijder het bestand van de server
        if ($currentImage && file_exists($currentImage)) {
            unlink($currentImage);

            // Update de database om de afbeelding te verwijderen
            $query = "UPDATE user SET picture = NULL WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$userId]);

            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Geen afbeelding gevonden."]);
        }
        exit; // Zorg dat er geen verdere uitvoer is
    }
}
?>
