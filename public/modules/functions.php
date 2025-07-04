<?php
$zoekterm = isset($_GET['zoekterm']) ? $_GET['zoekterm'] : '';


// Functie om de leverdatum te berekenen
function getDeliveryText(): string
{
    $dayOfWeek = date('N');
    $currentTime = date('H:i');
    $cutoffTime = '16:00';

    if ($dayOfWeek == 5 && $currentTime > $cutoffTime) {
        return "Bestel nu, dinsdag thuis!";
    } elseif ($dayOfWeek == 6 || $dayOfWeek == 7) {
        return "Bestel vandaag, dinsdag thuis!";
    } elseif ($dayOfWeek == 5 && $currentTime <= $cutoffTime) {
        return "Bestel vandaag, zaterdag thuis!";
    } else {
        return "Vandaag besteld voor 16:00, morgen thuis!";
    }
}

// Functie om alle reviews op te halen
function getReviews(): array
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM reviews");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie om de recentste reviews op te halen
function getRecentReviews(): array
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM reviews ORDER BY datum DESC LIMIT 9");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie om reviews te filteren
function getFilteredReviews(?int $minStars = null, ?int $maxStars = null, $startDate = null, $endDate = null, $limit = 9): array
{
    global $pdo;
    $sql = "SELECT * FROM reviews";
    $conditions = [];

    // Voeg sterrenfilters toe
    if ($minStars !== null) {
        $conditions[] = "sterren >= :minStars";
    }
    if ($maxStars !== null) {
        $conditions[] = "sterren <= :maxStars";
    }
    // Voeg datumfilters toe
    if ($startDate) {
        $conditions[] = "datum >= :startDate";
    }
    if ($endDate) {
        $conditions[] = "datum <= :endDate";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY datum DESC LIMIT :limit";

    // Bereid de SQL-statement voor
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    if ($minStars !== null) {
        $stmt->bindParam(':minStars', $minStars, PDO::PARAM_INT);
    }
    if ($maxStars !== null) {
        $stmt->bindParam(':maxStars', $maxStars, PDO::PARAM_INT);
    }
    if ($startDate) {
        $stmt->bindParam(':startDate', $startDate);
    }
    if ($endDate) {
        $stmt->bindParam(':endDate', $endDate);
    }

    // Bind de limit parameter als het een integer is
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

    // Voer de query uit en vang eventuele fouten
    try {
        $stmt->execute();
        // Haal de resultaten op
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Er is een fout opgetreden: ' . $e->getMessage();
        return [];
    }
}

// Functie om scooters op te halen
function getScooters(): array
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM scooter_brands");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie om scooter gegevens op te halen op basis van ID
function getScooterById(int $scooterId): ?array
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM scooter_brands WHERE id = :id");
    $stmt->bindParam(':id', $scooterId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Functie om reviews voor een specifieke scooter op te halen
function getReviewsForScooter(int $scooterId): array
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE scooter_id = :id LIMIT 6");
    $stmt->bindParam(':id', $scooterId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie om een review toe te voegen
function addReview(int $scooterId, int $userId, string $naam, int $sterren, string $review_tekst): bool
{
    global $pdo;

    // Check if the scooter_id exists in the scooter_brands table
    $query = $pdo->prepare("SELECT COUNT(*) FROM scooter_brands WHERE id = :scooter_id");
    $query->bindParam(':scooter_id', $scooterId, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchColumn();

    // If scooter_id does not exist, throw an error
    if ($result == 0) {
        throw new Exception("Invalid scooter_id: No scooter exists with this ID.");
    }

    // Check if the user_id exists in the user table
    $query = $pdo->prepare("SELECT COUNT(*) FROM user WHERE id = :user_id");
    $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $query->execute();
    $userExists = $query->fetchColumn();

    // If user_id does not exist, throw an error
    if ($userExists == 0) {
        throw new Exception("Invalid user_id: No user exists with this ID.");
    }

    // Current date for the review
    $datum = date('Y-m-d H:i:s');

    // Insert the review
    $stmt = $pdo->prepare("INSERT INTO reviews (scooter_id, user_id, naam, sterren, review_tekst, datum) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$scooterId, $userId, $naam, $sterren, $review_tekst, $datum]);
}
// Functie om scooters te zoeken
function zoekScooters($zoekterm = '', $beschrijving = '', $minPrijs = null, $maxPrijs = null, $sorterenOptie = '', $merk_id = null): array
{
    global $pdo;
    $sql = "SELECT * FROM scooter_brands WHERE 1=1";
    $parameters = [];

    // Filter op merk
    if ($merk_id !== null) {
        $sql .= " AND merk_id = :merk_id";
        $parameters[':merk_id'] = (int)$merk_id;
    }

    // Filter op naam
    if (!empty($zoekterm)) {
        $sql .= " AND naam LIKE :zoekterm";
        $parameters[':zoekterm'] = '%' . $zoekterm . '%';
    }

    // Filter op beschrijving
    if (!empty($beschrijving)) {
        $sql .= " AND beschrijving LIKE :beschrijving";
        $parameters[':beschrijving'] = '%' . $beschrijving . '%';
    }

    // Filter op minimum prijs
    if ($minPrijs !== null && $minPrijs !== '') {
        $sql .= " AND prijs >= :min_prijs";
        $parameters[':min_prijs'] = (float)$minPrijs;
    }

    // Filter op maximum prijs
    if ($maxPrijs !== null && $maxPrijs !== '') {
        $sql .= " AND prijs <= :max_prijs";
        $parameters[':max_prijs'] = (float)$maxPrijs;
    }

    // Sorteeropties
    if (!empty($sorterenOptie)) {
        switch ($sorterenOptie) {
            case 'naam_asc':
                $sql .= " ORDER BY naam ASC";
                break;
            case 'naam_desc':
                $sql .= " ORDER BY naam DESC";
                break;
            case 'prijs_asc':
                $sql .= " ORDER BY prijs ASC";
                break;
            case 'prijs_desc':
                $sql .= " ORDER BY prijs DESC";
                break;
        }
    }

    // Uitvoeren
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parameters);

    // Haal de resultaten op
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functie om een scooter toe te voegen aan de winkelwagentje
function addScooterToCart($scooter_id, $scooter_naam, $scooter_model, $scooter_prijs, $scooter_kleur, $scooter_afbeelding, $accessories, $aantal = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Controleer op fouten en voeg debug-informatie toe
    if (!isset($scooter_id) || empty($scooter_id)) {
        die("Geen scooter ID opgegeven.");
    }

    // Als het scooter ID al in de cart bestaat
    if (isset($_SESSION['cart'][$scooter_id])) {
        // Verhoog het aantal
        $_SESSION['cart'][$scooter_id]['aantal'] += $aantal;
        $_SESSION['cart'][$scooter_id]['accessories'] = $accessories; // Optioneel
    } else {
        // Voeg een nieuw item toe aan de winkelwagentje
        $_SESSION['cart'][$scooter_id] = [
            'naam' => $scooter_naam,
            'model' => $scooter_model,
            'prijs' => $scooter_prijs,
            'kleur' => $scooter_kleur,
            'afbeelding' => $scooter_afbeelding,
            'accessories' => $accessories,
            'aantal' => $aantal
        ];
    }

    echo "Scooter toegevoegd aan winkelwagentje.";
}

// Functie om het aantal items in de winkelwagentje te krijgen
function getCartItemCount(): int
{
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0; // Retourneer 0 als er geen producten in het winkelwagentje zijn
    }

    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['aantal'])) {
            $count += $item['aantal'];
        }
    }
    return $count;
}

$_SESSION['cart_count'] = getCartItemCount();
$deliveryText = getDeliveryText();

// Haal alle reviews op
$reviews = getReviews();

// Haal recentste reviews op
$recentReviews = getRecentReviews();

// Haal scooters op
$scooters = getScooters();

// Voor specifieke scooter om reviews op te halen
$scooterId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($scooterId > 0) {
    $reviewsForScooter = getReviewsForScooter($scooterId);
}

// Een review toevoegen
if (isset($_POST['submit'])) {
    $scooterId = $_POST['scooter_id'];
    $user = $_SESSION['user'];
    $userId = $user->id; // Extract the user ID from the user object
    $naam = $_POST['naam'];
    $sterren = $_POST['sterren'];
    $review_tekst = $_POST['review_tekst'];

    if (addReview($scooterId, $userId, $naam, $sterren, $review_tekst)) {
        header("Location: detail.php?id=" . $scooterId);
        exit();
    } else {
        echo "An error occurred while saving the review.";
    }
}
function getScooterName(int $id): string
{
    global $pdo;
    $sth = $pdo->prepare('SELECT naam, model, jaar, kleur FROM scooter_brands WHERE id = ?');
    $sth->bindParam(1, $id, PDO::PARAM_INT);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);

    return $result['naam'] ?? '';
}


//function savePurchase(array $inputs): bool
//{
//    global $pdo;
//
//    $sth = $pdo->prepare('INSERT INTO purchase  (fname,lname,email,address,zipcode,city,date,scooter_id) VALUES (?,?,?,?,?,?,NOW(),?)');
//    $sth->bindParam(1, $inputs['fname']);
//    $sth->bindParam(2, $inputs['lname']);
//    $sth->bindParam(3, $inputs['email']);
//    $sth->bindParam(4, $inputs['address']);
//    $sth->bindParam(5, $inputs['zipcode']);
//    $sth->bindParam(6, $inputs['city']);
//    $sth->bindParam(7, $_GET['id']);
//
//    return $sth->execute();
//}


function savePurchaseFromCart(PDO $pdo, $fname, $lname, $email, $address, $zipcode, $city, $user_id) {
    // Controleren of verplichte velden zijn ingevuld
    if (empty($fname) || empty($lname) || empty($email) || empty($address) || empty($zipcode) || empty($city) || empty($user_id)) {
        return ['success' => false, 'error' => 'Alle velden zijn verplicht.'];
    }

    // Controleren of de winkelwagen bestaat en niet leeg is
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return ['success' => false, 'error' => 'De winkelwagen is leeg.'];
    }

    // Begin een transactie
    $pdo->beginTransaction();

    try {
        // Invoegen in de purchase tabel
        $stmt = $pdo->prepare("INSERT INTO purchase (fname, lname, email, address, zipcode, city, date, user_id) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->execute([$fname, $lname, $email, $address, $zipcode, $city, $user_id]);

        // Verkrijg de laatste purchase_id
        $purchase_id = $pdo->lastInsertId();

        // Voorbereiden van de statement voor purchase_scooters
        $scooter_stmt = $pdo->prepare("INSERT INTO purchase_scooters (purchase_id, scooter_id, amount) VALUES (?, ?, ?)");

        // Itereren door elke scooter in de winkelwagen
        foreach ($_SESSION['cart'] as $scooter_id => $scooter) {
            $amount = $scooter['aantal'];

            // Controleer of het aantal groter is dan 0 voordat je iets invoegt
            if ($amount > 0) {
                // Debug output
                error_log("Inserting: Purchase ID: $purchase_id, Scooter ID: $scooter_id, Amount: $amount");

                // Uitvoeren van de invoeging voor elke scooter
                $scooter_stmt->execute([$purchase_id, $scooter_id, $amount]);

                // Verminder de voorraad in de scooter_brands tabel
                $updateStockStmt = $pdo->prepare("UPDATE scooter_brands SET stock = stock - ? WHERE id = ?");
                $updateStockStmt->execute([$amount, $scooter_id]);
            }
        }

        // Commit de transactie
        $pdo->commit();
        return ['success' => true, 'error' => null];

    } catch (PDOException $e) {
        // Rollback bij een fout
        $pdo->rollBack();

        // Log de fout
        error_log("Fout bij het opslaan van de aankoop: " . $e->getMessage());
        return ['success' => false, 'error' => 'Er is een fout opgetreden bij het bewaren van de bestelling.'];
    }
}
function getScooter(int $id): object
{
    global $pdo;
    $sth = $pdo->prepare('SELECT * FROM scooter_brands WHERE id = ?');
    $sth->execute([$id]);
    return $sth->fetch(PDO::FETCH_OBJ);
}

function getUserFromSession() {
    return $_SESSION['user'] ?? null;
}

function getUserData($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_OBJ);
}

// modules/functions.php
function getUserOrders($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT p.id AS purchase_id, p.fname, p.lname, p.email, p.address, p.zipcode, p.city, p.date, p.user_id, 
               p.status,  -- Include status
               ps.scooter_id, ps.amount,  -- Include amount
               s.naam, s.model, s.jaar, s.kleur, s.afbeelding, s.prijs 
        FROM purchase p
        JOIN purchase_scooters ps ON p.id = ps.purchase_id
        JOIN scooter_brands s ON ps.scooter_id = s.id
        WHERE p.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getUserReviews($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT r.id, r.naam AS reviewer_name, r.review_tekst, r.datum, 
               s.id AS scooter_id, s.naam AS scooter_name, s.model, s.jaar, s.kleur, 
               s.afbeelding, s.prijs, 
               (SELECT COUNT(*) FROM reviews WHERE scooter_id = s.id) AS review_count
        FROM reviews r
        JOIN scooter_brands s ON r.scooter_id = s.id
        WHERE r.user_id = ?
        ORDER BY r.datum DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retrieve all reviews as an associative array
}

//favorite functions
function getFavorieten($user_id, $pdo) {
    // Haal de favoriete scooter_id's van de gebruiker op
    $query = $pdo->prepare("SELECT scooter_id FROM favorite WHERE user_id = :user_id");
    $query->execute(['user_id' => $user_id]);

    return $query->fetchAll(PDO::FETCH_COLUMN);
}


function getFavoriteScooters($userId) {
    global $conn; // Zorg ervoor dat je de databaseverbinding in de globale scope hebt
    $stmt = $conn->prepare("
        SELECT p.*
        FROM favorite f
        JOIN producten p ON f.scooter_id = p.id
        WHERE f.user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result();
}

function getFavorieteScooters($user_id, $pdo) {
    $query = $pdo->prepare("
        SELECT s.id, s.naam, s.model, s.jaar, s.kleur, s.afbeelding, s.prijs, s.stock
        FROM scooter_brands s
        JOIN favorite f ON s.id = f.scooter_id
        WHERE f.user_id = :user_id
    ");
    $query->execute(['user_id' => $user_id]);

    return $query->fetchAll(PDO::FETCH_ASSOC); // Alle favoriete scooters met details
}



?>