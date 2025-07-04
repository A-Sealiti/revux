<?php
include_once 'classes/user.php';
session_start();
include_once 'modules/database.php';
include_once "modules/password_functions.php";
include_once "modules/functions.php";

$isLoggedIn = isset($_SESSION['user']);
$user = $_SESSION['user'];

// Haal de user_id op uit het User-object
$user_id = $user->id;

// Controleer of de gebruiker is ingelogd
if (!$user_id) {
    $_SESSION['message'] = "Je bent niet ingelogd.";
    header("Location: login.php");
    exit();
}

// Haal de gebruikersgegevens op
$stmt = $pdo->prepare("
    SELECT u.first_name, u.last_name, u.email, p.address, p.zipcode, p.city 
    FROM user u 
    LEFT JOIN purchase p ON u.id = p.user_id 
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);


// Vul de waarden in de velden
$firstName = ($userData['first_name'] ?? '');
$lastName = ($userData['last_name'] ?? '');
$email = ($userData['email'] ?? '');
$address = ($userData['address'] ?? '');
$zipcode = ($userData['zipcode'] ?? '');
$city = ($userData['city'] ?? '');

// Verwerk formulierdata bij een POST-verzoek
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $zipcode = trim($_POST['zipcode']);
    $city = trim($_POST['city']);
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Controleer of het huidige wachtwoord correct is
    $stmt = $pdo->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($currentPassword, $user['password'])) {
        // Update persoonlijke gegevens
        $stmt = $pdo->prepare("UPDATE user SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
        $stmt->execute([$firstName, $lastName, $email, $user_id]);

        // Update adresgegevens
        $stmt = $pdo->prepare("UPDATE purchase SET address = ?, zipcode = ?, city = ? WHERE user_id = ?");
        $stmt->execute([$address, $zipcode, $city, $user_id]);

        // Update wachtwoord indien ingevoerd en bevestigd
        if (!empty($newPassword) && $newPassword === $confirmPassword) {
            updatePassword($user_id, $newPassword, $pdo);
        }

        $_SESSION['message'] = "Gegevens succesvol bijgewerkt.";
        header("Location: edit_data.php");
        exit();
    } else {
        $_SESSION['message'] = "Huidig wachtwoord is onjuist.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gegevens Wijzigen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 text-center mb-4">Gegevens Wijzigen</h1>

                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-warning mt-3">
                            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Voornaam:</label>
                            <input type="text" name="first_name" class="form-control" id="first_name"
                                   value="<?= $firstName ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Achternaam:</label>
                            <input type="text" name="last_name" class="form-control" id="last_name"
                                   value="<?= $lastName ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adres:</label>
                            <input type="text" name="address" class="form-control" id="address"
                                   value="<?= $address ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="zipcode" class="form-label">Postcode:</label>
                            <input type="text" name="zipcode" class="form-control" id="zipcode"
                                   value="<?= $zipcode ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">Stad:</label>
                            <input type="text" name="city" class="form-control" id="city"
                                   value="<?= $city ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mailadres:</label>
                            <input type="email" name="email" class="form-control" id="email"
                                   value="<?= $email ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Huidig Wachtwoord:</label>
                            <input type="password" name="current_password" class="form-control" id="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nieuw Wachtwoord:</label>
                            <input type="password" name="new_password" class="form-control" id="new_password">
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Bevestig Wachtwoord:</label>
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Wijzig Gegevens</button>
                        <div class="form-group text-center mt-2">
                            <a href="profile.php" class="btn btn-secondary">Terug naar profielpagina</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
</body>
</html>
