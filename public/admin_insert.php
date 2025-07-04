<?php
require 'modules/database.php';
require 'modules/functions.php';
session_start();

global $pdo;

// $error variabelen
$errorNaam = "";
$errorModel = "";
$errorJaar = "";
$errorKleur = "";
$errorBeschrijving = "";
$errorPrijs = "";
$errorMerkID = "";
$errorAfbeelding = "";

if (isset($_POST["insert"])) {
    $naam = filter_input(INPUT_POST, 'naam', FILTER_SANITIZE_SPECIAL_CHARS);
    $model = filter_input(INPUT_POST, 'model', FILTER_SANITIZE_SPECIAL_CHARS);
    $jaar = filter_input(INPUT_POST, 'jaar', FILTER_VALIDATE_INT);
    $kleur = filter_input(INPUT_POST, 'kleur', FILTER_SANITIZE_SPECIAL_CHARS);
    $beschrijving = filter_input(INPUT_POST, 'beschrijving', FILTER_SANITIZE_SPECIAL_CHARS);
    $prijs = filter_input(INPUT_POST, 'prijs', FILTER_VALIDATE_FLOAT);
    $merk_id = filter_input(INPUT_POST, 'merk_id', FILTER_VALIDATE_INT);
    $afbeelding = null;

    if (empty($naam)) {
        $errorNaam = "Naam invullen";
    }
    if (empty($model)) {
        $errorModel = "Model invullen";
    }
    if ($jaar === false) {
        $errorJaar = "bouwjaar invullen";
    }
    if (empty($kleur)) {
        $errorKleur = "Kleur invullen";
    }
    if (empty($beschrijving)) {
        $errorBeschrijving = "Beschrijving invullen";
    }
    if ($prijs === false) {
        $errorPrijs = "Prijs invullen";
    }
    if ($merk_id === false) {
        $errorMerkID = "Merk selecteren";
    }

    // Controleer afbeelding
    if (isset($_FILES['afbeelding']) && $_FILES['afbeelding']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['afbeelding']['tmp_name'];
        $fileName = $_FILES['afbeelding']['name'];
        $fileSize = $_FILES['afbeelding']['size'];
        $fileType = $_FILES['afbeelding']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = './img/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $afbeelding = $fileName;
            } else {
                $errorAfbeelding = "Er was een fout bij het uploaden van de afbeelding.";
            }
        } else {
            $errorAfbeelding = "Ongeldige bestandsindeling. Alleen .jpg, .gif, .png en .jpeg zijn toegestaan.";
        }
    } else {
        $errorAfbeelding = "Afbeelding uploaden is mislukt.";
    }

    if ($errorNaam == "" && $errorModel == "" && $errorJaar == "" && $errorKleur == "" && $errorBeschrijving == "" && $errorPrijs == "" && $errorMerkID == "" && $errorAfbeelding == "") {
        $query = $pdo->prepare('INSERT INTO scooter_brands (naam, model, jaar, kleur, beschrijving, prijs, merk_id, afbeelding) VALUES (:naam, :model, :jaar, :kleur, :beschrijving, :prijs, :merk_id, :afbeelding)');
        $query->bindParam(':naam', $naam);
        $query->bindParam(':model', $model);
        $query->bindParam(':jaar', $jaar);
        $query->bindParam(':kleur', $kleur);
        $query->bindParam(':beschrijving', $beschrijving);
        $query->bindParam(':prijs', $prijs);
        $query->bindParam(':merk_id', $merk_id);
        $query->bindParam(':afbeelding', $afbeelding);
        $query->execute();
        $_SESSION['message'] = "De scooter is succesvol toegevoegd.";
        header("location:admin.php");
        exit;
    }
}
$zoekterm = isset($_GET['zoekterm']) ? $_GET['zoekterm'] : '';

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Your Experience</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- First Navbar -->
<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container-fluid">
        <div class="row w-100">
            <div class="col-lg-2"></div>
            <div class="col-lg-8">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav1"
                        aria-controls="navbarNav1" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav1">
                    <ul class="navbar-nav me-auto mb-2 mx-2 mb-lg-2">
                        <li class="nav-item stars-container">
                            <a class="nav-link" href="review.php">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                                Trustscore
                            </a>
                        </li>
                        <li class="nav-item bestel-vandaag mx-5">
                            <a class="nav-link" href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                    <path d="M10 .5C4.8.5.5 4.8.5 10s4.3 9.5 9.5 9.5 9.5-4.3 9.5-9.5S15.2.5 10 .5zM10 18c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8z"
                                          fill="#fff"></path>
                                    <path d="M10.8 9.7V6.9c0-.4-.3-.8-.8-.8s-.8.4-.8.8V10c0 .1 0 .2.1.3 0 .1.1.2.2.2l3.9 3.9c.1.1.3.2.5.2s.4-.1.5-.2c.3-.3.3-.8 0-1.1l-3.6-3.6z"
                                          fill="#fff"></path>
                                </svg>
                                <?php echo $deliveryText; ?>
                            </a>
                        </li>
                        <li class="nav-item klantenservice mx-5">
                            <a class="nav-link" href="contact.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21">
                                    <path fill="#fff"
                                          d="M20.3 8.9c-.4-.4-1-.7-1.6-.7H18v-.7a7.5 7.5 0 0 0-15 0v.8h-.8A2 2 0 0 0 .6 9c-.4.3-.6.9-.6 1.5v2.2c0 .6.2 1.2.7 1.6.4.4 1 .7 1.6.7h1.5c.4 0 .8-.2 1.1-.4.3-.3.4-.7.4-1.1V9.8c0-.4-.2-.8-.4-1.1l-.4-.2v-1c0-1.6.6-3.1 1.8-4.2 1.1-1.1 2.6-1.8 4.2-1.8s3.1.6 4.2 1.8a6 6 0 0 1 1.8 4.2v1l-.3.2c-.3.3-.4.7-.4 1.1v3.8c0 .4.2.8.4 1.1l.3.2v.6a2 2 0 0 1-.7 1.6 2 2 0 0 1-1 .6 2 2 0 0 0-.6-1c-.5-.5-1.2-.8-1.9-.8h-2.2a2.7 2.7 0 0 0-2.7 2.7 2.7 2.7 0 0 0 2.7 2.7h2.2c.7 0 1.4-.3 1.9-.8.3-.3.5-.7.7-1.2a3.7 3.7 0 0 0 3.1-3.7V15h.8a2 2 0 0 0 1.6-.7c.4-.4.7-1 .7-1.6v-2.2c-.1-.6-.3-1.2-.8-1.6zM3.8 13.5H2.2a.8.8 0 0 1-.5-.2.8.8 0 0 1-.2-.5v-2.2l.2-.5.5-.2h1.5v3.6zm9.4 5.7c-.2.2-.5.3-.8.3h-2.2c-.3 0-.6-.1-.8-.3s-.4-.5-.4-.8.1-.6.3-.8c.2-.2.5-.3.8-.3h2.2c.3 0 .6.1.8.3s.3.5.3.8 0 .6-.2.8zm6.3-6.4-.2.5-.5.2h-1.5V9.8h1.5l.5.2.2.5v2.3z"></path>
                                </svg>
                                Klantenservice
                            </a>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-light ms-auto me-2 mx-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 14.1 16.4">
                            <path fill="#3ebdae"
                                  d="M1.9 12.2c-2-4.8-1.2-9.3-.5-11 4-.8 9.3 3.7 11.5 6l-.5 7c-2.7 1.3-8.5 2.8-10.5-2z"
                                  opacity=".5"></path>
                            <path d="M12.8 5.9C10.6 2.7 5.5.9 2.7.1L1.8 0c-.3 0-.6.2-.8.3L.3 1a3 3 0 0 0-.2.8c-.2 2.9-.3 8.2 2 11.4 1.8 2.6 4.5 3.1 6.6 3.1l2.1-.2 1-.4.7-.7c1-1.5 2.8-5.5.3-9.1zM11.4 14c-1.9-3.3-4-6.4-6.4-9.2-.3-.4-.7-.4-1-.1-.4.2-.4.7-.1 1 2.4 2.8 4.5 5.9 6.3 9.1-1.7.2-5 .3-6.8-2.4-2.1-2.9-2-8.3-1.8-10.5l.1-.2.1-.2.2-.1h.2c2.1.6 7.3 2.3 9.3 5.2 1.9 2.8.7 5.9-.1 7.4z"></path>
                        </svg>
                        Spaar het milieu & je portemonnee
                    </a>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</nav>

<!--second nav -->
<nav class="navbar navbar-expand-lg second-navbar">
    <div class="container-fluid">
        <div class="row w-100">
            <div class="col-lg-2"></div>
            <div class="col-lg-8 d-flex align-items-center justify-content-between">
                <a class="navbar-brand me-3" href="admin.php">
                    <img src="img/logo.png" alt="Logo" class="img-fluid">
                </a>
                <ul class="navbar-nav me-5">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Categorie
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="?merk_id=1">Vespa</a></li>
                            <li><a class="dropdown-item" href="?merk_id=2">Piaggio</a></li>
                            <li><a class="dropdown-item" href="?merk_id=3">SYM</a></li>
                        </ul>
                    </li>
                </ul>

                <div class="container">
                    <form class="row g-2 d-flex align-items-center" action="#" method="GET">
                        <div class="col-auto">
                            <input class="form-control" name="zoekterm" type="search" placeholder="Zoeken..."
                                   value="<?= ($zoekterm) ?>"
                                   aria-label="Zoeken" style="width: 100%;">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-success" type="submit">Zoeken</button>
                        </div>
                    </form>
                </div>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle no-caret" href="#" id="accountDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                            <?php if ($isLoggedIn): ?>
                                <li>
                                    <form action="logout.php" method="post" class="d-inline">
                                        <button type="submit" class="dropdown-item">Uitloggen</button>
                                    </form>
                                </li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="inlog.php">Inloggen</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1>Nieuwe Scooter Toevoegen</h1>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="naam" class="form-label">Naam</label>
            <input type="text" class="form-control" id="naam" name="naam" value="<?= $naam ?? '' ?>" >
            <?= $errorNaam ? '<div class="text-danger">' . $errorNaam . '</div>' : '' ?>
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control" id="model" name="model" value="<?= $model ?? '' ?>" >
            <?= $errorModel ? '<div class="text-danger">' . $errorModel . '</div>' : '' ?>
        </div>
        <div class="mb-3">
            <label for="jaar" class="form-label">Jaar</label>
            <input type="number" class="form-control" id="jaar" name="jaar" value="<?= $jaar ?? '' ?>" >
            <?= $errorJaar ? '<div class="text-danger">' . $errorJaar . '</div>' : '' ?>
        </div>
        <div class="mb-3">
            <label for="kleur" class="form-label">Kleur</label>
            <input type="text" class="form-control" id="kleur" name="kleur" value="<?= $kleur ?? '' ?>" >
            <?= $errorKleur ? '<div class="text-danger">' . $errorKleur . '</div>' : '' ?>
        </div>
        <div class="mb-3">
            <label for="beschrijving" class="form-label">Beschrijving</label>
            <textarea class="form-control" id="beschrijving" name="beschrijving" rows="3" ><?= $beschrijving ?? '' ?></textarea>
            <?= $errorBeschrijving ? '<div class="text-danger">' . $errorBeschrijving . '</div>' : '' ?>
        </div>
        <div class="mb-3">
            <label for="prijs" class="form-label">Prijs</label>
            <input type="number" class="form-control" id="prijs" name="prijs" value="<?= $prijs ?? '' ?>" >
            <?= $errorPrijs ? '<div class="text-danger">' . $errorPrijs . '</div>' : '' ?>
        </div>
        <div class="mb-3">
            <label for="merk_id" class="form-label">Merk</label>
            <select class="form-control" id="merk_id" name="merk_id" >
                <option value="" disabled selected>Selecteer een merk</option>
                <option value="1" <?= (isset($merk_id) && $merk_id == 1) ? 'selected' : '' ?>>Vespa</option>
                <option value="2" <?= (isset($merk_id) && $merk_id == 2) ? 'selected' : '' ?>>Piaggio</option>
                <option value="3" <?= (isset($merk_id) && $merk_id == 3) ? 'selected' : '' ?>>SYM</option>
            </select>
            <?= $errorMerkID ? '<div class="text-danger">' . $errorMerkID . '</div>' : '' ?>
        </div>
        <div class="mb-3">
            <label for="afbeelding" class="form-label">Afbeelding</label>
            <input type="file" class="form-control" id="afbeelding" name="afbeelding">
            <?= $errorAfbeelding ? '<div class="text-danger">' . $errorAfbeelding . '</div>' : '' ?>
        </div>
        <button type="submit" name="insert" class="btn btn-success">Toevoegen</button>
        <a href="admin.php" class="btn btn-secondary">Annuleren</a>
    </form>
</div>
<br>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
