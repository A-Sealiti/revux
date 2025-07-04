<?php
session_start();
include_once 'modules/database.php';
include_once "modules/password_functions.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check of de waarden bestaan in de POST-array
    $token = isset($_POST['token']) ? $_POST['token'] : '';
    $newPassword = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    $message = resetUserPassword($token, $newPassword, $confirmPassword, $pdo);
    $_SESSION['message'] = $message;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord opnieuw instellen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/main.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .profile-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="profile-container mb-4">
                <h2 class="h5 text-center mb-4">Gegevens en Wachtwoord Opnieuw Instellen</h2>
                <p class="text-center">Vul je nieuwe wachtwoord in om je account te beveiligen.</p>
                <form action="" method="post" onsubmit="return validatePasswords()">
                    <div class="mb-3">
                        <label for="password" class="form-label">Nieuw wachtwoord:</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Bevestig wachtwoord:</label>
                        <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                    </div>

                    <div id="passwordError" class="text-danger" style="display:none;"></div>

                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

                    <button type="submit" class="btn btn-primary w-100">Wachtwoord bijwerken</button>
                    <div class="form-group text-center mt-2">
                        <a href="inlog.php" class="btn btn-secondary">Annuleren</a>
                    </div>
                </form>

                <?php if ($message): ?>
                    <div class="alert alert-warning mt-3"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
            </div>

            <div class="text-center">
                <h4 class="h6">Volg ons voor updates!</h4>
                <a href="#" class="btn btn-secondary">Volg ons op Facebook</a>
                <a href="#" class="btn btn-secondary">Volg ons op Twitter</a>
                <a href="#" class="btn btn-secondary">Volg ons op Instagram</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script>
    // check functie
    function validatePasswords() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;
        const message = document.getElementById("passwordError");

        // Vergelijk wachtwoorden als beide velden zijn ingevuld
        if (password !== "" && confirmPassword !== "") {
            // Vergelijk wachtwoorden
            if (password !== confirmPassword) {
                message.style.display = "block";
                message.innerHTML = "Wachtwoorden komen niet overeen.";
                return false;
            } else {
                message.style.display = "none";
            }
        } else {
            // Als een van de velden leeg is, verberg de foutmelding
            message.style.display = "none";
        }

        return true;
    }
</script>
</body>
</html>