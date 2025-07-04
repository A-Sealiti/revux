<?php
// Haal de resetlink op uit de URL
if (isset($_GET['resetLink'])) {
    $resetLink = urldecode($_GET['resetLink']);
} else {
    echo "Geen resetlink gevonden.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord reset bevestigd</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Wachtwoord Reset Bevestiging</h5>
                </div>
                <div class="card-body">
                    <h1 class="h5 text-center">Wachtwoord Reset Aanvragen</h1>
                    <p class="text-center">Gebruik de volgende link om je wachtwoord opnieuw in te stellen:</p>
                    <div class="text-center">
                        <a href="<?php echo $resetLink; ?>" class="btn btn-link reset-link"><?php echo $resetLink; ?></a>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    Als je deze link niet kunt openen, kopieer dan de URL en plak deze in je browser.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<style>
    body {
        background-color: #f8f9fa;
    }
    .container {
        margin-top: 100px;
    }
    .card {
        border: 1px solid #007bff;
    }
    .reset-link {
        word-break: break-all;
    }
</style>
</body>
</html>