<?php
include_once 'modules/database.php';
include_once 'auth/auth.php';
include_once 'classes/user.php';
$isLoggedIn = isset($_SESSION['user']);

// Controleer of de gebruiker is ingelogd en de juiste rol heeft
if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'admin') {
    header("Location: inlog.php");
    exit();
}

$deleted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    // Verwijder de gerelateerde items in purchase_scooters
    $delete_id = $_POST['delete_id'];

    $sql = "DELETE FROM purchase_scooters WHERE purchase_id = :purchase_id";
    $sth = $pdo->prepare($sql);
    $sth->bindParam(':purchase_id', $delete_id);
    $sth->execute();

    // Verwijder de bestelling
    $sql = "DELETE FROM purchase WHERE id = :id";
    $sth = $pdo->prepare($sql);
    $sth->bindParam(':id', $delete_id);
    $sth->execute();

    $deleted = true;
}

// Haal alle bestellingen en bijbehorende items op uit de database
$sql = "SELECT p.id AS purchase_id, p.fname, p.lname, p.email, p.address, p.zipcode, p.city, p.date, p.user_id, p.status, ps.scooter_id, ps.amount 
        FROM purchase p 
        LEFT JOIN purchase_scooters ps ON p.id = ps.purchase_id";
$sth = $pdo->prepare($sql);
$sth->execute();
$purchases = $sth->fetchAll(PDO::FETCH_ASSOC);
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
    <script src="js/main.js"></script>
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
                <a class="navbar-brand me-3" href="store_worker.php">
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
                                </li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="inlog.php">Inloggen</a></li>
                                <li><a class="dropdown-item" href="register.php">Registreren</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="row">
        <h1 class="mb-4">Bestellingen Beheer</h1>
        <div class="container-xs mb-3">
            <a href="admin.php" class="btn btn-secondary">Terug naar overzicht</a>
        </div>

        <?php if ($deleted): ?>
            <div class="alert alert-success" role="alert">
                Bestelling is succesvol verwijderd.
            </div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Email</th>
                <th>Adres</th>
                <th>Postcode</th>
                <th>Stad</th>
                <th>Datum</th>
                <th>Gebruikers ID</th>
                <th>Status</th>
                <th>Producten</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($purchases as $purchase): ?>
                <tr>
                    <td><?php echo($purchase['purchase_id']); ?></td>
                    <td><?php echo($purchase['fname']); ?></td>
                    <td><?php echo($purchase['lname']); ?></td>
                    <td><?php echo($purchase['email']); ?></td>
                    <td><?php echo($purchase['address']); ?></td>
                    <td><?php echo($purchase['zipcode']); ?></td>
                    <td><?php echo($purchase['city']); ?></td>
                    <td><?php echo($purchase['date']); ?></td>
                    <td><?php echo($purchase['user_id']); ?></td>
                    <td><?php echo($purchase['status']); ?></td>
                    <td>
                        <ul>
                            <li>Scooter
                                ID: <?php echo ($purchase['scooter_id']) . " - Aantal: " . ($purchase['amount']); ?></li>
                        </ul>
                    </td>
                    <td>
                        <form action="delete_order.php" method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo($purchase['purchase_id']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Weet je zeker dat je deze bestelling wilt verwijderen?');">
                                <i class="fas fa-trash-alt fs-5"></i> Verwijderen
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
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
