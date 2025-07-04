<?php
require_once 'classes/user.php';
session_start();
include_once 'modules/database.php';
include_once 'modules/functions.php';

require_once 'classes/purchase.php';


if (!isset($_SESSION['user'])) {
    header("Location: inlog.php"); // Redirect als niet ingelogd
    exit();
}

$user = $_SESSION['user'];
// var_dump($_SESSION['user']);
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Toon de inhoud van de winkelwagen voor debugging
//    var_dump($_SESSION['cart']);

    // Verwerk de winkelwagen
    foreach ($_SESSION['cart'] as $scooter_id => $scooter) {
        // Verwerk elk product in de winkelwagen
        // echo ": $scooter_id, Aantal: {$scooter['aantal']}<br>";
    }
} else {
    echo "Je winkelwagentje is leeg.";
}
//var_dump($_SESSION['cart']);
if (isset($_GET['id'])) {
    $_SESSION['scooter_id'] = (int) $_GET['id'];
}

// Controleer vervolgens de scooter_id in de sessie
$scooter_id = isset($_SESSION['scooter_id']) ? $_SESSION['scooter_id'] : null;

// Laat een bericht zien als de scooter ID niet beschikbaar is
if ($scooter_id === null) {
    // echo "Scooter ID niet gevonden in de sessie.";
} else {
    // echo "Scooter ID is: " . $scooter_id;
}

//var_dump($_SESSION['scooter_id']);

//$scooter_id = isset($_GET['scooter_id']) ? $_GET['scooter_id'] : (isset($_SESSION['scooter_id']) ? $_SESSION['scooter_id'] : null);

// Haal scooter_id op vanuit de sessie of request
//$scooter_id = $_GET['scooter_id'];
$zoekterm = isset($_GET['zoekterm']) ? $_GET['zoekterm'] : '';
const FNAME_REQUIRED = 'Voornaam invullen';
const LNAME_REQUIRED = 'Achternaam invullen';
const EMAIL_REQUIRED = 'Email invullen';
const EMAIL_INVALID = 'Geldig email adres invullen';
const ADRESS_REQUIRED = 'Adres invullen';
const ZIPCODE_REQUIRED = 'Postcode invullen';
const CITY_REQUIRED = 'Woonplaats invullen';
const AGREE_REQUIRED = 'Voorwaarden accepteren';

$errors = [];
$inputs = [];
$_SESSION['user'] = $user;

//$PDI = new$db;
if (isset($_POST['verzenden'])) {
    // sanitize and validate fname
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS);

    $fname = trim($fname);
    if (empty($fname)) {
        $errors['fname'] = FNAME_REQUIRED;
    } else {
        $inputs['fname'] = $fname;
    }

// sanitize and validate lname
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS);

    $lname = trim($lname);
    if (empty($lname)) {
        $errors['lname'] = LNAME_REQUIRED;
    } else {
        $inputs['lname'] = $lname;
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($email === false) {
        $errors['email'] = EMAIL_INVALID;
    } else {
        $inputs['email'] = $email;
    }


// sanitize and validate adress
    $adress = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
    $adress = trim($adress);

    if (empty($adress)) {
        $errors['address'] = ADRESS_REQUIRED;
    } else {
        $inputs['address'] = $adress;
    }

// sanitize and validate zipcode
    $zipcode = filter_input(INPUT_POST, 'zipcode', FILTER_SANITIZE_SPECIAL_CHARS);
    $zipcode = trim($zipcode);

    if (empty($zipcode)) {
        $errors['zipcode'] = ZIPCODE_REQUIRED;
    } else {
        $inputs['zipcode'] = $zipcode;
    }

// sanitize and validate city
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
    $city = trim($city);

    if (empty($city)) {
        $errors['city'] = CITY_REQUIRED;
    } else {
        $inputs['city'] = $city;
    }

// accept terms
    $agree = filter_input(INPUT_POST, 'agree', FILTER_SANITIZE_SPECIAL_CHARS);

// check against the valid value
    if ($agree === null) {
        $errors['agree'] = AGREE_REQUIRED;
    }



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Controleer of alle benodigde POST-velden zijn ingevuld
    if (
        isset($_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['address'], $_POST['zipcode'], $_POST['city'])
        && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['email'])
        && !empty($_POST['address']) && !empty($_POST['zipcode']) && !empty($_POST['city'])
    ) {
        // Haal de gebruikers-ID op
        $user_id = $user->id; // Of gebruik $_SESSION['user_id'] als dit in de sessie staat

        // Roep de functie aan om alle gegevens op te slaan
        $result = savePurchaseFromCart(
            $pdo,
            $_POST['fname'],
            $_POST['lname'],
            $_POST['email'],
            $_POST['address'],
            $_POST['zipcode'],
            $_POST['city'],
            $user_id
        );

        // Geef een bericht terug aan de gebruiker
        if ($result['success']) {
            $_SESSION['message'] = "Je bestelling is succesvol geplaatst!";
            unset($_SESSION['cart']); // Leeg de winkelwagen
        } else {
            $_SESSION['message'] = "Fout bij het plaatsen van de bestelling: " . $result['error'];
        }
    } else {
        $_SESSION['message'] = "Vul alle verplichte velden in!";
    }

    // Stuur de gebruiker terug naar de homepage
    header("Location: index.php");
    exit();
}

}

?>
<!--echo "<h2>Debug Inputs:</h2><pre>";-->
<!--var_dump($inputs, $user->id, $scooter_id);-->
<!--echo "</pre>";-->
<!--$scooter_name = getScooterName($scooter_id);-->
<!--?>-->
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
                                    <form action="profile.php" method="post" class="d-inline">
                                        <button  type="submit" class="dropdown-item" >Profile</button>
                                    </form>
                                </li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="inlog.php">Inloggen</a></li>
                                <li><a class="dropdown-item" href="register.php" >Registreren</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
                <a href="cart.php" class="position-relative">
                    <i class="fs-5 mt-1 text-black bi bi-cart3"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?php echo getCartItemCount(); ?>
    </span>
                </a>            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</nav>

<header>
    <div class="container-fluid py-5 "  style="background: url('img/header.png'); background-size: cover">
        <div class="row py-5"></div>
    </div>
</header>
<main>
    <div class="container-lg">
        <p class="display-5 fw-bold"><?=getScooterName($_GET['id'])?> bestellen</p>
        <form  method="post">
            <div class="mb-3 mt-3">
                <label for="fname" class="form-label">Voornaam:</label>
                <input type="text" class="form-control" id="fname" value="<?php echo $inputs['fname'] ?? '' ?>" name="fname" >
                <div  class="form-text text-danger">
                    <?= $errors['fname'] ?? '' ?>
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label for="lname" class="form-label">Achternaam:</label>
                <input type="text" class="form-control" id="lname" value="<?php echo $inputs['lname'] ?? '' ?>"  name="lname" >
                <div  class="form-text text-danger">
                    <?= $errors['lname'] ?? '' ?>
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email:</label>
                <input type="text" class="form-control" id="email" value="<?php echo $inputs['email'] ?? '' ?>" name="email" >
                <div  class="form-text text-danger">
                    <?= $errors['email'] ?? '' ?>
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label for="address" class="form-label">Adres:</label>
                <input type="text" class="form-control" id="address"  value="<?php echo $inputs['address'] ?? '' ?>" name="address" >
                <div  class="form-text text-danger">
                    <?= $errors['address'] ?? '' ?>
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label for="zipcode" class="form-label">Postcode:</label>
                <input type="text" class="form-control" id="zipcode"  value="<?php echo $inputs['zipcode'] ?? '' ?>" name="zipcode" >
                <div  class="form-text text-danger">
                    <?= $errors['zipcode'] ?? '' ?>
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label for="city" class="form-label">Woonplaats:</label>
                <input type="text" class="form-control" id="city" value="<?php echo $inputs['city'] ?? '' ?>" name="city" >
                <div  class="form-text text-danger">
                    <?= $errors['city'] ?? '' ?>
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="myCheck" name="agree" >
                <label class="form-check-label" for="myCheck">I agree on conditions.</label>
                <div  class="form-text text-danger">
                    <?= $errors['agree'] ?? '' ?>
                </div>
            </div>
            <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
            <input type="submit" name="verzenden" value="Submit" class="btn btn-primary mb-5">
        </form>
    </div>
</main>

<!--<footer-->
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
                    <li>
                        <i class="bi bi-shop"></i> Bezoek onze showroom
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19652.557117407145!2d4.291495325093141!3d51.99649720000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5b44fc97a975b%3A0x1a24f22ff7625791!2sSunder%20Bromfietsen!5e0!3m2!1snl!2snl!4v1729421677913!5m2!1snl!2snl"
                                width="400"
                                height="350"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>

                        </a>
                    </li>
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