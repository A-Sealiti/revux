<?php
include_once 'classes/user.php';
session_start();
include_once 'modules/database.php';
include_once 'modules/functions.php';

//functie om user op te halen
$user = getUserFromSession();
$isLoggedIn = isset($_SESSION['user']);


$zoekterm = isset($_GET['zoekterm']) ? $_GET['zoekterm'] : '';

if ($user) {
    $user_id = $user->id;

    // Haal de gegevens van de gebruiker op
    $user_data = getUserData($pdo, $user_id);

    // Haal de bestellingen van de gebruiker op
    $orders = getUserOrders($pdo, $user_id);

//    var_dump($orders);
} else {
    header('Location: login.php');
    exit;
}
$userReviews = getUserReviews($pdo, $user_id);
$favorieten = [];
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']->id;

    // Haal de favoriete scooters van de gebruiker op
    $favorieten = getFavorieteScooters($user_id, $pdo);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style_profile.css">

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
                                        <button type="submit" class="dropdown-item">Profile</button>
                                    </form>
                                </li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="inlog.php">Inloggen</a></li>
                                <li><a class="dropdown-item" href="register.php">Registreren</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
                <a href="cart.php" class="position-relative">
                    <i class="fs-5 mt-1 text-black bi bi-cart3"></i>
                    <span id="cart-count"
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?php echo getCartItemCount(); ?>
     </span>
                </a></div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</nav
</div>


<div class="container container-body mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card text-center border border-primary">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        <h4><strong>Hallo <?= ($user_data->first_name ?? 'Gebruiker') ?></strong></h4>
                        <p class="text-center welkom ">Welkom bij ons webshop</p>
                        <a href="logout.php" class="btn btn-primary uitloggen-button">Uitloggen</a>
                    </div>
                    <div>
                        <div class="col-12 text-center position-relative">
                            <?php
                            $uploadedPicture = $user_data->picture ?? null;
                            ?>

                            <div id="preview" class="mb-2" style="display: none;">
                                <img id="previewImage" src="#" alt="Nieuwe profielfoto" class="img-thumbnail rounded-circle" style="width: 100px; border: 2px solid #ddd;">
                            </div>

                            <?php if ($uploadedPicture && file_exists($uploadedPicture)): ?>
                                <img src="<?= ($uploadedPicture) ?>" alt="Profielfoto" id="currentProfilePicture" class="img-thumbnail rounded-circle mb-2" style="width: 100px; height: 100px; border: 2px solid #ddd;" onclick="toggleActionMenu(event)">
                            <?php else: ?>
                                <i class="bi bi-person-circle" id="profileIcon" style="font-size: 100px; color: #ddd; cursor: pointer;" onclick="toggleActionMenu(event)"></i>
                            <?php endif; ?>
                            <form action="upload_profile.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                                <input type="file" name="picture" id="profile_picture" class="form-control d-none" onchange="document.getElementById('uploadForm').submit();" required>
                                <input type="hidden" name="user_id" value="<?= ($user_data->id) ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="container mt-5">
        <h3>Je Laatste Bestellingen</h3>
        <div id="ordersCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-touch="false">
            <div class="carousel-inner">
                <?php
                $chunkedOrders = array_chunk($orders, 3);
                foreach ($chunkedOrders as $index => $ordersGroup): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="container py-4">
                            <div class="row">
                                <?php foreach ($ordersGroup as $order): ?>
                                    <div class="col-10 col-md-4 mb-4">
                                        <div class="card order-card d-flex flex-row shadow-sm border-light">
                                            <img src="img/<?php echo($order['afbeelding'] ?: 'placeholder.png'); ?>"
                                                 alt="<?= ($order['naam']) ?>" class="card-img-left"
                                                 style="width: 40%; max-height: 160px; object-fit: cover;">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <?= ($order['naam']) ?> (<?= ($order['model']) ?>)
                                                </h5>
                                                <p><strong>Prijs: <?= ($order['prijs']) ?> ,-</strong></p>
                                                <p><strong>Kleur:</strong> <?= ($order['kleur']) ?></p>
                                                <p><strong>Jaar:</strong> <?= ($order['jaar']) ?></p>
                                                <p><strong>Besteld op:</strong> <?= ($order['date']) ?></p>
                                                <p><strong>Aantal:</strong> <?= ($order['amount']) ?></p>
                                                <p><strong>Status:</strong> <?= ($order['status']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#ordersCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Vorige</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#ordersCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Volgende</span>
            </button>
        </div>
    </div>

    <!-- Reviews Container -->


    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-touch="false">
        <div class="carousel-inner">

            <?php
            $chunkedReviews = array_chunk($userReviews, 3);
            foreach ($chunkedReviews as $index => $reviewsGroup): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="container py-4">
                        <h3 class="mt-4">Jouw Laatste Recensies</h3>
                        <div class="row">
                            <?php foreach ($reviewsGroup as $review): ?>
                                <div class="col-10 col-md-4 mb-4">
                                    <div class="card shadow-sm border-light">
                                        <div class="card-body">
                                            <h5 class="d-flex align-items-center">
                                                <?php if ($uploadedPicture && file_exists($uploadedPicture)): ?>
                                                    <img src="<?= ($uploadedPicture) ?>" alt="Profielfoto" id="currentProfilePicture" class="img-thumbnail rounded-circle me-2" style="width: 50px; height: 50px; border: 2px solid #ddd;" onclick="toggleActionMenu(event)">
                                                <?php else: ?>
                                                    <i class="bi bi-person-circle me-2" id="profileIcon" style="font-size: 50px; color: #ddd; cursor: pointer;" onclick="toggleActionMenu(event)"></i>
                                                <?php endif; ?>
                                                <span class="fw-bold"><?= ($review['reviewer_name']) ?></span>
                                            </h5>
                                            <span class="text-info" style="color: blue;"><?= ($review['scooter_name']) ?></span>
                                            <p class="card-text"><?= ($review['review_tekst']) ?></p>
                                            <small class="text-muted"><em>Geplaatst op <?= ($review['datum']) ?></em></small><br>
                                            <small class="text-muted">
                                                <em>Aantal reviews:
                                                    <?php
                                                    $starCount = $review['review_count'];
                                                    for ($i = 1; $i <= $starCount; $i++): ?>
                                                        <span class="text-warning fs-5">&#9733;</span>
                                                    <?php endfor; ?>

                                                    <?php
                                                    for ($i = $starCount + 1; $i <= 5; $i++): ?>
                                                        <span class="text-muted fs-5">&#9734;</span> <!-- Lege ster -->
                                                    <?php endfor; ?>
                                                </em>
                                            </small>
                                        </div>
                                        <img src="img/<?php echo($review['afbeelding'] ?: 'placeholder.png'); ?>"
                                             alt="<?= ($review['scooter_name']) ?>"
                                             class="card-img-bottom"
                                             style="width: 150px; height: 150px; object-fit: cover; margin: 10px auto; display: block;">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Vorige</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Volgende</span>
        </button>
    </div>


    <div class="container mt-5">
        <h3>Favoriete Scooters</h3>

        <?php if (empty($favorieten)): ?>
            <p>Je hebt nog geen scooters geliket.</p>
        <?php else: ?>
            <div id="favoriteScootersCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $chunks = array_chunk($favorieten, 3);
                    foreach ($chunks as $index => $scooterGroup): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="row">
                                <?php foreach ($scooterGroup as $scooter): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100 d-flex flex-column position-relative">
                                            <img src="img/<?php echo($scooter['afbeelding']); ?>"
                                                 class="card-img-top"
                                                 alt="Afbeelding van <?php echo($scooter['naam']); ?>">
                                            <div class="card-body">
                                                <form action="like_product.php" method="POST">
                                                    <input type="hidden" name="scooter_id"
                                                           value="<?php echo $scooter['id']; ?>">
                                                    <span class="like-button position-absolute top-0 end-0 m-2"
                                                          style="cursor: pointer; z-index: 1;">
                                                <i class="fs-3 <?php echo in_array($scooter['id'], $scooter) ? 'bi bi-suit-heart-fill text-danger' : 'bi bi-suit-heart'; ?>"></i>
                                                <span class="like-count"
                                                      style="display: <?php echo (isset($scooter['likes']) && $scooter['likes'] > 0) ? 'inline' : 'none'; ?>;">
                                                    (<?php echo isset($scooter['likes']) ? $scooter['likes'] : 0; ?>)
                                                </span>
                                            </span>
                                                </form>
                                                <h5 class="card-title"><?php echo($scooter['naam']); ?>
                                                    - <?php echo($scooter['model']); ?></h5>
                                                <p class="card-text">
                                                    <strong>Jaar:</strong> <?php echo($scooter['jaar']); ?><br>
                                                    <strong>Kleur:</strong> <?php echo($scooter['kleur']); ?><br>
                                                    <strong>Prijs:</strong>
                                                    €<?php echo number_format($scooter['prijs'], 2); ?><br>
                                                    <strong>voorraad:</strong> <?php echo($scooter['stock']); ?>
                                                </p>
                                            </div>
                                            <a href="detail.php?id=<?php echo $scooter['id']; ?>" class="btn btn-success btn-rounded btn-sm" style="border-radius: 20px; padding: 10px 15px; margin-bottom: 10px">
                                                <i class="bi bi-info-circle"></i> Bekijk Details
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#favoriteScootersCarousel"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Vorige</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#favoriteScootersCarousel"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Volgende</span>
                </button>
            </div>
        <?php endif; ?>
    </div>


    <!--    gegeven card-->
    <div class="row mt-4">
        <h5>Gegevens & Voorkeuren</h5>
        <div class="col-md-6">
            <div class="card preferences-card p-3">

                <div class="row">
                    <div class="col-8">
                        <ul class="list-unstyled">
                            <li><strong>Naam:</strong> <?= ($user_data->first_name . ' ' . $user_data->last_name) ?>
                            </li>
                            <li><strong>Postcode:</strong> <?= ($orders[0]['zipcode'] ?? 'Niet beschikbaar') ?></li>
                            <li><strong>Adres:</strong> <?= ($orders[0]['address'] ?? 'Niet beschikbaar') ?></li>
                            <li><strong>Stad:</strong> <?= ($orders[0]['city'] ?? 'Niet beschikbaar') ?></li>
                            <li><strong>Klantnummer:</strong> <?= ($user_data->id) ?></li>
                            <li><strong>Email:</strong> <?= ($user_data->email) ?></li>
                        </ul>
                    </div>
                    <div class="col-4 text-center position-relative">
                        <?php
                        $uploadedPicture = $user_data->picture ?? null;
                        ?>

                        <!-- Voorbeeld of huidige profielfoto -->
                        <div id="preview" class="mb-2" style="display: none;">
                            <img id="previewImage" src="#" alt="Nieuwe profielfoto" class="img-thumbnail rounded-circle" style="width: 100px; border: 2px solid #ddd;">
                        </div>

                        <?php if ($uploadedPicture && file_exists($uploadedPicture)): ?>
                            <img src="<?= ($uploadedPicture) ?>" alt="Profielfoto" id="currentProfilePicture" class="img-thumbnail rounded-circle mb-2" style="width: 100px; height: 100px; border: 2px solid #ddd;  cursor: pointer" onclick="toggleActionMenu(event)">
                        <?php else: ?>
                            <i class="bi bi-person-circle" id="profileIcon" style="font-size: 100px; color: #ddd; cursor: pointer;" onclick="toggleActionMenu(event)"></i>
                        <?php endif; ?>

                        <!-- Upload formulier -->
                        <form action="upload_profile.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                            <input type="file" name="picture" id="profile_picture" class="form-control d-none" onchange="document.getElementById('uploadForm').submit();" required>
                            <input type="hidden" name="user_id" value="<?= ($user_data->id) ?>">
                        </form>

                        <div id="actionMenu" class="action-menu" style="display: none;">
                            <button id="editPicture" type="button" class="btn btn-secondary mt-2" onclick="document.getElementById('profile_picture').click();">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button id="deletePicture" type="button" class="btn btn-danger mt-2" onclick="deleteProfilePicture(<?= ($user_data->id) ?>);">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>


                </div>
                <a href="edit_data.php" class="d-block mt-2">Beheer je gegevens</a>
            </div>
        </div>
    </div>
</div>


<br>
<br>
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
<script>

    function toggleActionMenu(event) {
        event.stopPropagation(); // Stop de klik van propageren naar het document
        const actionMenu = document.getElementById('actionMenu');

        // Toont of verbergt het actie-menu
        actionMenu.style.display = (actionMenu.style.display === "none" || actionMenu.style.display === "") ? "block" : "none";
    }

    // Verberg het actie-menu als je ergens anders op de pagina klikt
    document.addEventListener('click', function(event) {
        const actionMenu = document.getElementById('actionMenu');
        const target = event.target;

        if (!actionMenu.contains(target) && target.id !== 'currentProfilePicture' && target.id !== 'profileIcon') {
            actionMenu.style.display = 'none'; // Verberg het menu
        }
    });

    // Functie om de profielfoto te verwijderen
    function deleteProfilePicture(userId) {
        if (confirm("Weet je zeker dat je de profielfoto wilt verwijderen?")) {
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('delete_picture', true); // Geef aan dat dit een delete-verzoek is

            fetch('upload_profile.php', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (response.ok) {
                    window.location.reload(); // Vernieuw de pagina om de wijzigingen weer te geven
                } else {
                    alert("Er is een fout opgetreden bij het verwijderen van de foto.");
                }
            }).catch(error => {
                console.error('Error:', error);
                alert("Er is een onverwachte fout opgetreden.");
            });
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>