<?php
require_once 'classes/user.php';
session_start();

include_once 'modules/database.php';
include_once 'modules/functions.php';
include_once 'classes/detail.php';


//if (!isset($_SESSION['user'])) {
//    header("Location: inlog.php");
//    exit();
//}

//$user = $_SESSION['user'];
//var_dump($_SESSION['user']);
// Haal specifieke scooter ID op van de URL en de gerelateerde gegevens
$scooterId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data = []; // Initialize scooter gegevens

if ($scooterId > 0) {
    // Scooter gegevens ophalen
    $data = getScooterById($scooterId);

    // Haal reviews op voor deze specifieke scooter met een aparte functie
    $reviewsForScooter = getReviewsForScooter($scooterId);
}
$favorieten = [];
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']->id;

    // Haal de favoriete scooter_id's van de gebruiker op met de functie
    $favorieten = getFavorieten($user_id, $pdo);
}
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
    <!--    <script src="main.js"></script>-->
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
                            <a class="nav-link" href="#">
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
                            <a class="nav-link" href="#">
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
                <a class="navbar-brand me-3" href="index.php">
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
                    <form class="row g-2 d-flex align-items-center">
                        <div class="col-auto">
                            <input class="form-control" type="search" placeholder="Zoeken..." aria-label="Zoeken"
                                   style="width: 100%;">
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
                            <li><a class="dropdown-item" href="inlog.php">Inloggen</a></li>
                            <li><a class="dropdown-item" href="register.php">Registreren</a></li>
                        </ul>
                    </li>
                </ul>
                <a href="cart.php" class="position-relative">
                    <i class="fs-5 mt-1 text-black bi bi-cart3"></i>
                    <span id="cart-count"
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                         <?php echo getCartItemCount(); ?>
                         </span>
                </a>


            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</nav>


<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <form action="cart.php" method="post">

                <h1><?php echo($data['naam']); ?><?php echo($data['model']); ?></h1>

                <!-- Afbeelding van de scooter met position-relative -->
                <div class="position-relative">
                    <img src="img/<?php echo($data['afbeelding'] ?: 'default.png'); ?>" class="img-fluid rounded"
                         alt="<?php echo($data['naam'] . ' ' . $data['model']); ?>">

                    <!-- Like-knop rechtsboven op de afbeelding -->
                    <form action="like_product.php" method="POST">
                        <input type="hidden" name="scooter_id" value="<?php echo $data['id']; ?>">
                        <span class="like-button position-absolute top-0 end-0 m-2"
                              style="cursor: pointer; z-index: 1;">
        <!-- Controleer of deze scooter in de favorieten zit -->
        <i class="fs-3 <?php echo in_array($data['id'], $favorieten) ? 'bi bi-suit-heart-fill text-danger' : 'bi bi-suit-heart'; ?>"></i>
        <span class="like-count"
              style="display: <?php echo (isset($data['likes']) && $data['likes'] > 0) ? 'inline' : 'none'; ?>;">
            (<?php echo isset($data['likes']) ? $data['likes'] : 0; ?>)
        </span>
    </span>
                    </form>
                </div>
        </div>


        <div class="col-md-6">
            <div class="current me-6">€<?php echo number_format($data['prijs'], 2, ',', '.'); ?></div>
            <ul class="list-group">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Kleur:</strong> <?php echo($data['kleur']); ?></li>
                </ul>

                <li class="list-group-item"><strong>Jaar:</strong> <?php echo($data['jaar']); ?></li>
            </ul>

            <div class="mt-3">
                <strong>Beschrijving:</strong>
                <details>
                    <summary class="btn btn-link">Lees meer</summary>
                    <p><?php echo($data['beschrijving']); ?></p>
                </details>
                <div class="d-flex  mx-2 fs-5">
               <span class="badge rounded-pill
               <?php echo($data['stock'] > 10 ? 'bg-secondary text-light' : ($data['stock'] > 0 ? 'bg-warning text-dark' : 'bg-danger text-light')); ?>
                  px-3 py-2">
                   <i class="bi bi-check-circle-fill " style="color: green;"></i>
               <?php echo($data['stock'] > 0 ? "Nog {$data['stock']} op voorraad" : "Uitverkocht"); ?>
               </span>
                </div>


                <!-- Accessories Section -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="accessories-list">
                            <strong class="fs-5">Meest verkochte accessoires inclusief montage:</strong>
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="form_submitted" value="1">
                                <input type="hidden" name="scooter_id" value="<?php echo($data['id']); ?>">
                                <input type="hidden" name="scooter_prijs" value="<?php echo($data['prijs']); ?>">
                                <input type="hidden" name="scooter_kleur" value="<?php echo($data['kleur']); ?>">
                                <input type="hidden" name="scooter_afbeelding"
                                       value="<?php echo($data['afbeelding']); ?>">
                                <input type="hidden" name="scooter_naam" value="<?php echo($data['naam']); ?>">
                                <input type="hidden" name="scooter_model" value="<?php echo($data['model']); ?>">

                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory1" name="accessories[]"
                                               value="sierbeugel" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('sierbeugel', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory1">Sierbeugel voorspatbord zwart<span class="float-end">(+€89,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory2" name="accessories[]"
                                               value="voorklapdrager" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('voorklapdrager', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory2">Voorklapdrager zwart<span
                                                    class="float-end">(+€249,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory3" name="accessories[]"
                                               value="angel_eye" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('angel_eye', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory3">Angel Eye koplamp<span
                                                    class="float-end">(+€249,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory4" name="accessories[]"
                                               value="led_rand" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('led_rand', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory4">Led rand rondom de koplamp (zwart of matzwart)<span
                                                    class="float-end">(+€89,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory5" name="accessories[]"
                                               value="kentekenplaat_verlichting" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('kentekenplaat_verlichting', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory5">Kentekenplaat verlichting led<span class="float-end">(+€49,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory6" name="accessories[]"
                                               value="alarm_piaggio" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('alarm_piaggio', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory6">Alarm Piaggio origineel<span class="float-end">(+€249,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory7" name="accessories[]"
                                               value="anti_diefstal" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('anti_diefstal', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory7">Anti diefstal contactslot<span class="float-end">(+€99,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory8" name="accessories[]"
                                               value="gps_tracking" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('gps_tracking', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory8">Scootersecure tracking GPS<span class="float-end">(+€249,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory9" name="accessories[]"
                                               value="kettingslot_150" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('kettingslot_150', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory9">Kettingslot 150cm art3<span
                                                    class="float-end">(+€79,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory10" name="accessories[]"
                                               value="kettingslot_170" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('kettingslot_170', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory10">Kettingslot 170cm art4<span
                                                    class="float-end">(+€99,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory12" name="accessories[]"
                                               value="tucano_beschermhoes" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('tucano_beschermhoes', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory12">Tucano beschermhoes<span
                                                    class="float-end">(+€44,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory13" name="accessories[]"
                                               value="vespa_beschermhoes" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('vespa_beschermhoes', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory13">Vespa beschermhoes<span
                                                    class="float-end">(+€99,95)</span></label>
                                    </li>
                                    <li class="list-group-item">
                                        <input type="checkbox" id="accessory14" name="accessories[]"
                                               value="snelheid_aanpassen" <?php echo (isset($_SESSION['cart'][$data['id']]['accessories']) && in_array('snelheid_aanpassen', $_SESSION['cart'][$data['id']]['accessories'])) ? 'checked' : ''; ?>/>
                                        <label for="accessory14">Snelheid aanpassen<span
                                                    class="float-end">(+€95,00)</span></label>
                                    </li>
                                </ul>
                                <a href="cart.php">
                                    <button type="submit" class=" btn btn mt-3 button "><i class="bi bi-cart2">
                                            toevoegen aan winkelwagen </i></button>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <a href="review.php?id=<?php echo $scooterId; ?>" class="btn btn-primary mt-1">Laat een Review
                    Achter</a>
                <a href="index.php" class="btn btn-primary mt-1">Terug naar overzicht</a>
            </div>
        </div>
    </div>
</div>
<br>

<div class="container mt-1">

    <div class="row mt-1">
        <?php if (!empty($reviewsForScooter)): ?>
            <?php foreach ($reviewsForScooter as $review): ?>
                <div class="col-md-6 mb-4">
                    <div class="card d-flex flex-row">
                        <div class="col-md-3 d-flex align-items-center justify-content-center" style="padding: 20px;">
                            <span style="font-size: 90px; line-height: 90px;">
                            <span style="font-size: 90px; line-height: 90px;">
                                <svg width="90px" height="90px" viewBox="0 0 256 256"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <defs>
    <style>
        .cls-1 {
            fill: #84d0f7;
        }

        .cls-2 {
            fill: #2d2d2d;
        }

        .cls-3 {
            fill: #aa392d;
        }

        .cls-4 {
            fill: #7c211a;
        }

        .cls-5 {
            fill: #e59973;
        }

        .cls-6 {
            opacity: 0.2;
        }

        .cls-7 {
            opacity: 0.3;
        }</style>
    </defs>
    <g class="me-3" data-name="Female 2" id="Female_2">
    <path class="cls-1"
          d="M249.16,127.72a120.25,120.25,0,0,1-28.27,77.63,123.4,123.4,0,0,1-9.2,9.79h-166a101.12,101.12,0,0,1-8.95-8.86c-.24-.27-.51-.59-.81-.95C29,197.07,6.56,167.78,7.72,127.72,9.33,72.21,56,7,128.44,7A120.72,120.72,0,0,1,249.16,127.72Z"
          id="Wallpaper"/>
        <!--<path class="cls-2" d="M197.86,199.66c-4.92,15.91-37.33,24.37-69,24.54-31.66-.17-64.06-8.63-69-24.54-2.94-9.48,6.23-14,10.08-38.38,3.62-22.75-4.1-27.28-2.39-53.16,1.1-16.82,2.2-33.37,13.93-47.73,16.8-20.56,43.21-21.12,46.62-21.16,3,0,30.57.08,48.12,21.16,15.67,18.82,13.64,43.29,13.93,47.73,1.71,25.88-6,30.41-2.39,53.16C191.63,185.65,200.8,190.18,197.86,199.66Z" id="Hairs"/>-->
    <path class="cls-3"
          d="M220.89,205.35a120.67,120.67,0,0,1-92.46,43.08c-25.68,0-47.17-8.93-62.88-18.6a142.23,142.23,0,0,1-19.82-14.68,102.13,102.13,0,0,1-8.95-8.87c-.24-.26-.5-.59-.83-1A111.68,111.68,0,0,1,61,186.53a131,131,0,0,1,27.44-11.3q19.89,17.6,39.79,35.18l40.15-35.18c.29.09.59.15.87.24a1.11,1.11,0,0,0,.26.09h0a131.85,131.85,0,0,1,26.23,10.93A112.15,112.15,0,0,1,220.89,205.35Z"
          id="Sweater"/>
    <path class="cls-4"
          d="M168.39,175.23l-40.15,35.18q-19.92-17.59-39.79-35.18l1-.28.84-.24c.24-.07.53-.13.81-.22h.06c1-.26,2.22-.59,3.55-.89q16.78,14.37,33.55,28.77,17-14.42,33.94-28.77C165.13,174.3,167.44,175,168.39,175.23Z"
          id="Neckband"/>
    <path class="cls-5"
          d="M162.16,173.6q-17,14.37-33.94,28.77Q111.44,188,94.67,173.6c1.43-.35,2.93-.67,4.45-1s2.8-.54,4.13-.76a34.84,34.84,0,0,0,1.65-5.13c.24-1,.41-1.87.54-2.74a37.66,37.66,0,0,0,8.74,5.5c.5.22,1.06.48,1.74.76a31.06,31.06,0,0,0,24.29,0c.89-.37,1.67-.72,2.3-1a39,39,0,0,0,8.63-5.52c.28,1.28.63,2.63,1,4s.92,2.85,1.39,4.13a.13.13,0,0,1,.09,0c1.26.22,2.43.44,3.48.65h0C158.9,172.84,160.55,173.21,162.16,173.6Z"
          id="Neck"/>
    <path class="cls-6"
          d="M169.54,175.56c-.82.93-1.67,1.84-2.54,2.71a57.84,57.84,0,0,1-6.23,5.41,42.54,42.54,0,0,1-9.41,5.33,34.44,34.44,0,0,1-6.52,1.93,29.67,29.67,0,0,1-6,.52,31.42,31.42,0,0,1-11.49-2.39l-.66-.26a41.48,41.48,0,0,1-8.21-4.69,59.44,59.44,0,0,1-12.56-12.71c-.56-.77-1.13-1.55-1.67-2.35.11-.35.22-.7.32-1.07a36,36,0,0,0,.87-4c.59.48,1.2.94,1.79,1.37a43.45,43.45,0,0,0,5.06,3.18c.17.08.35.19.52.28l.63.32.74.35.43.2c.16.06.26.13.37.17l.46.2.46.17a.82.82,0,0,0,.19.09c.17.08.35.15.52.21l.68.24,0,0c.24.09.48.18.69.24l.18.07.52.15a7.07,7.07,0,0,0,.74.24,1.28,1.28,0,0,1,.35.09l.71.19a28.78,28.78,0,0,0,4.48.78,2.61,2.61,0,0,0,.48.05,1.77,1.77,0,0,0,.32,0,5.86,5.86,0,0,0,.61,0c.18,0,.37,0,.55.05h1.19a31.29,31.29,0,0,0,9.63-1.59l.06,0,.35-.13c.65-.2,1.28-.46,1.93-.7l.18-.08.82-.35.22-.09a44.79,44.79,0,0,0,8.19-4.82c.55-.41,1.07-.83,1.61-1.24.28,1.35.67,2.8,1.13,4.32.41,1.37.85,2.65,1.3,3.85l1.09.2,1.09.19,1.06.2c.28.06.54.1.81.17s.34.07.52.11c1.34.28,2.69.56,4,.89,2.1.5,4.19,1,6.23,1.63Z"
          id="HairsShadow"/>
    <path class="cls-5"
          d="M185.93,119.84c-.15,5.14-3.44,10.35-7.11,12.27a7.65,7.65,0,0,1-1.56.59l-.12,0a4.93,4.93,0,0,1-2,.06,5,5,0,0,1-2-.87,2.92,2.92,0,0,1-.28-.21,6.78,6.78,0,0,1-1.11-1.1c-2.65-3.19-3.73-9.18-2-14a14.46,14.46,0,0,1,3.2-5,8.77,8.77,0,0,1,4.38-2.57,5.76,5.76,0,0,1,2.34,0C184.05,109.94,186.06,115.55,185.93,119.84Z"
          id="RightEar"/>
    <path class="cls-7"
          d="M185.93,119.84c-.15,5.15-3.43,10.34-7.11,12.28a9,9,0,0,1-1.56.59l-.13,0a5.29,5.29,0,0,1-2,.06,5.09,5.09,0,0,1-2-.87l-.29-.21a6.56,6.56,0,0,1-1.08-1.09c-2.68-3.19-3.74-9.19-2-14a14.36,14.36,0,0,1,3.2-5,10.12,10.12,0,0,1,3.08-2.17,5.9,5.9,0,0,1,1.28-.39,5.64,5.64,0,0,1,2.35,0,3,3,0,0,1,.35.09C184.17,110.24,186.06,115.67,185.93,119.84Z"
          id="RightEarShadow"/>
    <path class="cls-5"
          d="M85.08,130.62a7.36,7.36,0,0,1-1.39,1.3,4.9,4.9,0,0,1-4,.81l-.12,0a7.15,7.15,0,0,1-1.56-.6c-3.67-1.91-6.95-7.12-7.11-12.26-.13-4.29,1.88-9.9,6.25-10.77,2.48-.5,4.86.7,6.73,2.58a14.51,14.51,0,0,1,3.2,5C88.81,121.43,87.72,127.42,85.08,130.62Z"
          id="LeftEar"/>
    <path class="cls-5"
          d="M177.32,109v.07c0,.39,0,.8,0,1.21a78.35,78.35,0,0,1-4.48,21.41c-.28.8-.58,1.6-.89,2.41-4.69,12-12,22.53-20.77,29.55-.54.41-1.06.83-1.61,1.24a44.79,44.79,0,0,1-8.19,4.82l-.22.09-.82.35-.18.08c-.65.24-1.28.5-1.93.7l-.35.13-.06,0a31.29,31.29,0,0,1-9.63,1.59H127c-.18,0-.37,0-.55-.05a5.86,5.86,0,0,1-.61,0,1.77,1.77,0,0,1-.32,0,2.61,2.61,0,0,1-.48-.05,28.78,28.78,0,0,1-4.48-.78l-.71-.19a1.28,1.28,0,0,0-.35-.09,7.07,7.07,0,0,1-.74-.24l-.52-.15L118,171c-.21-.06-.45-.15-.69-.24l0,0-.68-.24c-.17-.06-.35-.13-.52-.21a.82.82,0,0,1-.19-.09l-.46-.17-.46-.2c-.11,0-.21-.11-.37-.17l-.43-.2-.74-.35-.63-.32c-.17-.09-.35-.2-.52-.28a43.45,43.45,0,0,1-5.06-3.18c-.59-.43-1.2-.89-1.79-1.37-.76-.6-1.5-1.23-2.23-1.89s-1.46-1.32-2.16-2-1.43-1.43-2.08-2.17-1.37-1.52-2-2.31a80.38,80.38,0,0,1-17.28-42.91c-.11-1-.17-2-.21-3.06a68,68,0,0,1,.32-10.78c.18-1.46.37-2.89.63-4.33.33-1.73.72-3.49,1.22-5.28a72.16,72.16,0,0,1,4.61-12.47c4.19-8.8,10.25-17.08,17.81-23C111,48.4,119.11,45,128.26,45c14.75-.07,27,8.6,35.5,20.27a72.12,72.12,0,0,1,8.57,15.34c.56,1.37,1.06,2.74,1.54,4.13a69.76,69.76,0,0,1,2.54,9.75c.24,1.39.46,2.81.61,4.22A64.6,64.6,0,0,1,177.32,109Z"
          id="Head"/>
    <path class="cls-6"
          d="M177.39,109.06c-.09,1.42-.2,2.57-.33,3.79-.19,1.91-.3,2.84-.34,2.91-1.2,2-10.7.3-24.56-7.11-10.8-5.78-12-8.06-22.45-14.43-15.6-9.47-23.4-14.27-32-12.88C84.11,83.51,77,98.29,74.46,97.42c2.91.63,7.49-14.69,20-18.82s27.44,4.8,42.79,14c4.82,2.89,11.14,7.24,21.62,12,14.45,6.58,17.38,5.21,18.47,4.52C177.37,109.09,177.37,109.06,177.39,109.06Z"
          data-name="HairsShadow" id="HairsShadow-2"/>
    <path class="cls-2"
          d="M177.39,109.06s0,0,0,0c-1.09.69-4,2.06-18.47-4.52-10.48-4.78-16.8-9.13-21.62-12C121.91,83.4,107,74.49,94.47,78.6s-17.1,19.45-20,18.82l-.18-.07c-2.15-1-.76-10.28,2.42-18.27,4.78-12,16.75-29,38-34.59,4.52-1.18,21.94-5.46,38.94,4.32,16.79,9.67,21.75,26,24,33.64C178.43,85.12,184.23,104.48,177.39,109.06Z"
          data-name="Hairs" id="Hairs-2"/>
    </g>
    </svg>
                                    </span>
                                <defs>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo($review['naam']); ?></h5>
                                <div class="stars">
                                    <?php for ($i = 0; $i < (int)$review['sterren']; $i++): ?>
                                        <i class="bi bi-star-fill text-danger"></i>
                                    <?php endfor; ?>

                                    <?php for ($i = 0; $i < (5 - (int)$review['sterren']); $i++): ?>
                                        <i class="bi bi-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="like-button position-absolute top-0 end-0 m-2"
                                      data-scooter-id="<?php echo $data['id']; ?>"
                                      style="cursor: pointer; z-index: 1;">
    <i class="bi bi-suit-heart fs-3"></i>
    <span class="like-count" style="display: none;">(<?php echo $data['likes'] ?? 0; ?>)</span>
</span>
                                <p class="card-text mt-2"><?php echo($review['review_tekst']); ?></p>
                                <p class="card-text"><small class="text-muted">Gepubliceerd
                                        op: <?php echo date("d-m-Y", strtotime($review['datum'])); ?></small></p>
                            </div>
                        </div>
                    </div> <!-- End of card -->
                </div> <!-- End of review item -->
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Geen recente reviews gevonden.</p>
        <?php endif; ?>
    </div> <!-- End of reviews row -->
</div>


<!--footer-->
<footer class="footer text-dark py-5 fs-5 ">
    <div class="container">
        <div class="row text-center text-lg-start">
            <div class="col-lg-3 mb-4">
                <h3>Over ons</h3>
                <ul class="list-unstyled">
                    <li><a href="#">Bedrijfsgegevens</a></li>
                    <li><a href="#">Over ons</a></li>
                    <li><a href="#">Openingstijden en route</a></li>
                    <li><a href="#">Zakelijke klanten</a></li>
                </ul>
            </div>

            <div class="col-lg-3 mb-4">
                <h3>Klantenservice</h3>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-emphasis">Bestelling plaatsen</a></li>
                    <li><a href="#" class="text-emphasis">Betaalinfo</a></li>
                    <li><a href="#" class="text-emphasis">Garantie</a></li>
                    <li><a href="#" class="text-emphasis">Verzenden & bezorgen</a></li>
                    <li><a href="#" class="text-emphasis">Retourneren</a></li>
                    <li><a href="#" class="text-emphasis">Reparatie en onderhoud</a></li>
                </ul>
            </div>

            <div class="col-lg-3 mb-4">
                <h3>Openingstijden</h3>
                <div class="p-4 bg-light text-dark rounded">
                    <p>Maandag t/m Zaterdag: 09:30 - 16:30</p>
                    <p>Zondag: Gesloten</p>
                </div>
            </div>


            <div class="col-lg-3 mb-4">
                <h3>Wij staan voor je klaar</h3>
                <ul class="list-unstyled">
                    <li>
                    <li><a href="#"><i class="bi bi-chat-right-dots"></i> Chat direct</li>
                    <li>
                    <li><a href="#"><i class="bi bi-envelope"></i> Stuur een bericht</li>
                    <li>
                    <li><a href="#"><i class="bi bi-telephone"></i> Bel ons</li>
                    <li>
                    <li><a href="#"><i class="bi bi-shop"></i> Bezoek onze showroom</li>
                </ul>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col text-center">
                <p> © 2024 ETF, alle rechten voorbehouden.</p>
                <a href="#" class="text-dark me-3">Algemene Voorwaarden</a>
                <a href="#" class="text-dark">Privacybeleid</a>
            </div>
        </div>
    </div>
</footer>
<script src="js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>