<?php
session_start();

include_once 'modules/database.php';
include_once 'modules/functions.php';
require_once 'classes/user.php';

$scooter_id = isset($_SESSION['scooter_id']) ? $_SESSION['scooter_id'] : null;

// Debugging
if ($scooter_id === null) {
    // echo "Scooter ID niet gevonden in de sessie.";
} else {
    // echo "Scooter ID: " . $scooter_id;
}
$scooter_id = isset($_SESSION['scooter_id']) ? $_SESSION['scooter_id'] : null;
$zoekterm = isset($_GET['zoekterm']) ? $_GET['zoekterm'] : '';

$cartItemCount = getCartItemCount();
if (!isset($_SESSION['user'])) {
    header("Location: inlog.php"); // Redirect als niet ingelogd
    exit();
}

$user = $_SESSION['user'];
// var_dump($_SESSION['user']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_submitted'])) {
    // Debug: laat de ontvangen POST-gegevens zien
    var_dump($_POST);

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Toon de inhoud van de winkelwagen voor debugging
        // var_dump($_SESSION['cart']);

        // Verwerk de winkelwagen
        foreach ($_SESSION['cart'] as $scooter_id => $scooter) {
            // Verwerk elk product in de winkelwagen
            // echo "Scooter ID: $scooter_id, Aantal: {$scooter['aantal']}<br>";
        }
    } else {
        echo "Je winkelwagentje is leeg.";
    }

    // Ontvang de waarden van het formulier
    $scooter_id = $_POST['scooter_id'];
    $scooter_naam = $_POST['scooter_naam'];
    $scooter_model = $_POST['scooter_model'];
    $scooter_prijs = $_POST['scooter_prijs'];
    $scooter_kleur = $_POST['scooter_kleur'];
    $scooter_afbeelding = $_POST['scooter_afbeelding'];
    $accessories = isset($_POST['accessories']) ? $_POST['accessories'] : [];
    $aantal = isset($_POST['aantal']) ? intval($_POST['aantal']) : 1;

    // Nu roep je de functie aan met alle vereiste parameters
    addScooterToCart($scooter_id, $scooter_naam, $scooter_model, $scooter_prijs, $scooter_kleur, $scooter_afbeelding, $accessories, $aantal);

    // Optioneel: Redirect naar de winkelwagentje
    header("Location: cart.php");
    exit();
}
if (!isset($_SESSION['winkelwagentje'])) {
    $_SESSION['winkelwagentje'] = [];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scooter_id = $_POST['scooter_id'];
    $amount = $_POST['amount'];

    // Werk de hoeveelheid bij in de sessie
    if (isset($_SESSION['cart'][$scooter_id])) {
        $_SESSION['cart'][$scooter_id]['aantal'] = (int)$amount;

        // Bereken de nieuwe totaalprijs
        $updatedTotal = 0;
        foreach ($_SESSION['cart'] as $scooter) {
            $updatedTotal += $scooter['prijs'] * $scooter['aantal'];
        }

        // Stuur een JSON-respons terug naar de browser
        echo json_encode([
            'updatedAmount' => $_SESSION['cart'][$scooter_id]['aantal'],
            'updatedTotal' => number_format($updatedTotal, 2, ',', '.')
        ]);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scooter_id']) && isset($_POST['amount'])) {
    $scooter_id = $_POST['scooter_id'];
    $amount = intval($_POST['amount']);

    // Controleer of het product bestaat in de winkelwagen
    if (isset($_SESSION['cart'][$scooter_id])) {
        // Werk het aantal bij
        $_SESSION['cart'][$scooter_id]['aantal'] = $amount;

        // Bereken het nieuwe totaalbedrag
        $updatedTotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $updatedTotal += $item['prijs'] * $item['aantal'];
        }

        // Geef een JSON-respons terug
        echo json_encode([
            'success' => true,
            'updatedAmount' => $amount,
            'updatedTotal' => number_format($updatedTotal, 2, ',', '.')
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product niet gevonden.']);
    }
} else {
    // echo json_encode(['success' => false, 'message' => 'Ongeldige aanvraag.']);
}
$isLoggedIn = isset($_SESSION['user']);

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
                </a>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Jouw Winkelwagentje</h2>
            <div class="row">
                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $scooter_id => $scooter): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 d-flex flex-column position-relative shadow-sm">
                                <img src="img/<?php echo ($scooter['afbeelding']); ?>" class="card-img-top" alt="<?php echo ($scooter['kleur']); ?> scooter afbeelding">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-3 text-primary"><?php echo ($scooter['naam']); ?></h5>
                                    <p>
                                        <strong>Model:</strong> <?php echo $scooter['model']; ?><br>
                                        <strong>Prijs:</strong> €<?php echo number_format($scooter['prijs'], 2, ',', '.'); ?><br>
                                        <strong>Kleur:</strong> <?php echo ($scooter['kleur']); ?><br>
                                        <strong>Aantal:</strong>
                                        <span id="amount-<?php echo $scooter_id; ?>"><?php echo $scooter['aantal']; ?></span>
                                        <select name="aantal" class="form-select" onchange="updateCart(<?php echo $scooter_id; ?>, this.value)">
                                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php echo ($scooter['aantal'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select><br>
                                        <strong>Accessoires:</strong> <?php echo !empty($scooter['accessories']) ? implode(", ", $scooter['accessories']) : 'Geen'; ?>
                                    </p>
                                </div>
                                <div class="actions-wrap">
                                    <div class="actions d-flex align-items-center justify-content-between">
                                        <a href="detail.php?id=<?php echo $scooter_id; ?>" class="btn btn-success mb-3 m-2">Accessoires toevoegen</a>
                                        <form action="remove.php" method="post" class="d-inline">
                                            <input type="hidden" name="scooter_id" value="<?php echo $scooter_id; ?>">
                                            <button type="submit" class="btn btn-danger mb-3 m-2 fs-3"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Je winkelwagentje is leeg.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4 mt-5">
            <div class="card shadow-lg">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">Bestelling Overzicht</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Aantal</th>
                                <th>Prijs</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $totaalPrijs = 0; ?>
                            <?php foreach ($_SESSION['cart'] as $scooter_id => $scooter): ?>
                                <?php $totaalPrijs += $scooter['prijs'] * $scooter['aantal']; ?>
                                <tr>
                                    <td><?php echo $scooter['naam'] . ' - ' . $scooter['model']; ?></td>
                                    <td id="total-amount-<?php echo $scooter_id; ?>"><?php echo $scooter['aantal']; ?></td>
                                    <td>€<?php echo number_format($scooter['prijs'] * $scooter['aantal'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5><strong>Totaal:</strong></h5>
                            <h5 class="text-success" id="total-price">€<?php echo number_format($totaalPrijs, 2, ',', '.'); ?></h5>
                        </div>
                        <?php foreach ($_SESSION['cart'] as $scooter_id => $scooter): ?>
                            <input type="hidden" name="scooter_ids[]" value="<?php echo $scooter_id; ?>">
                            <input type="hidden" name="amounts[<?php echo $scooter_id; ?>]" value="<?php echo $scooter['aantal']; ?>">
                        <?php endforeach; ?>
                        <a href="order.php?id=<?php echo $scooter_id; ?>"><button class="btn btn-success mb-3 m-2">Bestelling plaatsen</button></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
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
<script>
    function updateCart(scooter_id, amount) {
        // Maak een AJAX-aanroep naar de server
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Ontvang de JSON-respons
                const response = JSON.parse(xhr.responseText);

                // Werk de hoeveelheden en prijzen bij
                document.getElementById(`amount-${scooter_id}`).innerText = response.updatedAmount;
                document.getElementById(`total-amount-${scooter_id}`).innerText = response.updatedAmount;
                document.getElementById('total-price').innerText = `€${response.updatedTotal}`;
            }
        };

        // Verstuur gegevens
        xhr.send(`scooter_id=${scooter_id}&amount=${amount}`);
    }
</script>
</body>
</html>

