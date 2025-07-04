<?php
session_start();
include_once 'modules/database.php';

function getUserByEmail($email, $pdo)
{
    $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertPasswordReset($user_id, $token, $pdo)
{
    $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, created_at, expires_at) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR))");
    $stmt->execute([$user_id, $token]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>Ongeldig e-mailadres.</div>";
        exit;
    }

    $user = getUserByEmail($email, $pdo);
    if ($user) {
        $user_id = $user['id'];
        // Genereer unieke token
        $token = bin2hex(random_bytes(50));

        insertPasswordReset($user_id, $token, $pdo);

        // Link naar resetpagina
        $resetLink = "http://localhost/sd23-p01-reviewyourexperience-etf/public/reset_request.php?token=$token";

        // Redirect naar een bevestigingspagina met de link
        header("Location: confirmation_page.php?resetLink=" . urlencode($resetLink));
        exit; //  script stopt na de redirect
    } else {
        echo "<div class='alert alert-danger'>E-mailadres niet gevonden.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord reset aanvragen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light full-height">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="text-center mb-4 bg-secendary">Wachtwoord vergeten?<br></h1>

                    <p class="text-center">Vergeten is menselijk. Wat is je e-mailadres? Dan zenden we je binnen enkele minuten een linkje om een nieuw wachtwoord in te stellen.</p>
                    <form action="request_password_reset.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mailadres:</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                        <a href="inlog.php">Terug naar inloggen</a><br>
                        <br>
                        <button type="submit" class="btn btn-primary w-100">Verzenden</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    body {
        background-color: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 50vh;
    }

    .card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 30px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .form-label {
        font-weight: bold;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>