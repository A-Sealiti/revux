<?php
function updatePassword($user_id, $password, $pdo)
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE user SET password = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $user_id]);
}

function resetUserPassword($token, $newPassword, $confirmPassword, $pdo)
{
    // Controleren of beide wachtwoorden hetzelfde zijn
    if ($newPassword !== $confirmPassword) {
        return "Wachtwoorden komen niet overeen.";
    }

    $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resetRequest) {
        $user_id = $resetRequest['user_id'];
        updatePassword($user_id, $newPassword, $pdo);

        // Verwijder de token
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);

        $_SESSION['message'] = "Je wachtwoord is succesvol gewijzigd.";

        // Redirect naar inlogpagina
        header("Location: inlog.php");
        exit(); // Zorg dat je hier stopt met verdere executie
    } else {
        return "Ongeldige of verlopen token.";
    }
}
