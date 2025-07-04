<?php

include_once 'modules/database.php';
require_once 'classes/user.php';

const FIRSTNAME_REQUIRED = 'Voornaam invullen';
const LASTNAME_REQUIRED = 'Achternaam invullen';
const EMAIL_REQUIRED = 'Email invullen';
const UNIQUE_EMAIL_REQUIRED = 'Email is al geregistreerd';
const PASSWORD_REQUIRED = 'Password invullen';

$errors = [];
$inputs = [];

if (isset($_POST['send'])) {
    // Sanitize en valideer voornaam
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
    $firstname = trim($firstname);
    if (empty($firstname)) {
        $errors['firstname'] = FIRSTNAME_REQUIRED;
    } else {
        $inputs['firstname'] = $firstname;
    }

    // Sanitize en valideer achternaam
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = trim($lastname);
    if (empty($lastname)) {
        $errors['lastname'] = LASTNAME_REQUIRED;
    } else {
        $inputs['lastname'] = $lastname;
    }

    // Valideer en controleer email
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        $errors['email'] = EMAIL_REQUIRED;
    } else {
        // Check for email uniqueness
        $inputs['email'] = $email;
        $sth = $pdo->prepare('SELECT * FROM user WHERE email = :email');
        $sth->bindParam(':email', $inputs['email']);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $errors['email'] = UNIQUE_EMAIL_REQUIRED;
        }
    }

    // Valideer en hash het wachtwoord
    $password = filter_input(INPUT_POST, 'password');
    $password = trim($password);
    if (empty($password)) {
        $errors['password'] = PASSWORD_REQUIRED;
    } else {
        // Hash het wachtwoord
        $inputs['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    // Bepaal de rol op basis van het e-mailadres
    $role = 'member'; // Standaardrol is 'member'
    if (str_ends_with($inputs['email'], '@now.etf.nl')) {
        $role = 'admin'; // Als de e-mail eindigt op '@now.etf.nl', stel de rol in op 'admin'
    }

    // Als er geen fouten zijn, voer de database-insert uit
    if (count($errors) === 0) {
        $sth = $pdo->prepare('INSERT INTO user (first_name, last_name, email, password, role) VALUES (:firstname, :lastname, :email, :password, :role)');

        $sth->bindParam(':firstname', $inputs['firstname']);
        $sth->bindParam(':lastname', $inputs['lastname']);
        $sth->bindParam(':email', $inputs['email']);
        $sth->bindParam(':password', $inputs['password']); // Gehashed wachtwoord
        $sth->bindParam(':role', $role); // Voeg rol toe aan de insert

        $result = $sth->execute();

        // Redirect naar de juiste pagina
        header("Location: inlog.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Your Experience</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>
</nav>

<div class="d-flex justify-content-center">
    <div class="forms m-5 w-50 p-4">
        <h2 class="text-center">Registreren</h2>
        <form method="post" action="">
            <div class="row">
                <div class="col-md-6 p-3 border-end">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">Voornaam</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?= $inputs['firstname'] ?? '' ?>">
                        <div class="form-text text-danger">
                            <?= $errors['firstname'] ?? '' ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Achternaam</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?= $inputs['lastname'] ?? '' ?>">
                        <div class="form-text text-danger">
                            <?= $errors['lastname'] ?? '' ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 p-3">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $inputs['email'] ?? '' ?>">
                        <div class="form-text text-danger">
                            <?= $errors['email'] ?? '' ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Wachtwoord</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="form-text text-danger">
                            <?= $errors['password'] ?? '' ?>
                        </div>
                    </div>

                    <div class="row">
                        <?php if(!empty($_SESSION['message'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?=$_SESSION['message']?>
                                <?php $_SESSION['message']=null; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <button type="submit" name="send" class="btn btn-primary w-100">Registreren</button>
        </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"
    </script>
</footer>
</body>
</html>
