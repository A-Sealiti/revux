<?php
session_start();
include_once 'database.php';

// Zet foutmeldingen aan
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Haal de datums op die de admin kan kiezen
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01');
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-t');

try {
    // Huidige datum om de omzet te berekenen
    $query = "
SELECT 
    SUM(ps.amount * sc.prijs) AS total_revenue,
    COUNT(DISTINCT p.user_id) AS total_customers,
    SUM(ps.amount) AS total_sales -- Totaal aantal verkochte producten
FROM 
    purchase p
LEFT JOIN 
    purchase_scooters ps ON p.id = ps.purchase_id
LEFT JOIN 
    scooter_brands sc ON ps.scooter_id = sc.id
WHERE 
    p.date >= :start_date AND p.date <= :end_date
";

    // Voer de query uit en haal de resultaten op
    $stmt = $pdo->prepare($query);
    $stmt->execute(['start_date' => $start_date, 'end_date' => $end_date]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Haal de verkoop per maand op
    $sales_query = "
        SELECT MONTH(p.date) AS month, COUNT(p.id) AS sales
        FROM purchase p
        WHERE p.date >= :start_date AND p.date <= :end_date
        GROUP BY MONTH(p.date)
    ";
    $sales_stmt = $pdo->prepare($sales_query);
    $sales_stmt->execute(['start_date' => $start_date, 'end_date' => $end_date]);
    $sales_data = $sales_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Haal de omzet per maand op
    $revenue_query = "
        SELECT MONTH(p.date) AS month, SUM(ps.amount * sc.prijs) AS revenue
        FROM purchase p
        LEFT JOIN purchase_scooters ps ON p.id = ps.purchase_id
        LEFT JOIN scooter_brands sc ON ps.scooter_id = sc.id
        WHERE p.date >= :start_date AND p.date <= :end_date
        GROUP BY MONTH(p.date)
    ";
    $revenue_stmt = $pdo->prepare($revenue_query);
    $revenue_stmt->execute(['start_date' => $start_date, 'end_date' => $end_date]);
    $revenue_data = $revenue_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Klanten per maand ophalen
    $customers_query = "
        SELECT MONTH(p.date) AS month, COUNT(DISTINCT p.user_id) AS customers
        FROM purchase p
        WHERE p.date >= :start_date AND p.date <= :end_date
        GROUP BY MONTH(p.date)
    ";
    $customers_stmt = $pdo->prepare($customers_query);
    $customers_stmt->execute(['start_date' => $start_date, 'end_date' => $end_date]);
    $customers_data = $customers_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Haal de sterbeoordelingen op
    $reviews_query = "
        SELECT r.sterren, COUNT(*) AS count
        FROM reviews r
        WHERE r.datum >= :start_date AND r.datum <= :end_date
        GROUP BY r.sterren
    ";
    $reviews_stmt = $pdo->prepare($reviews_query);
    $reviews_stmt->execute(['start_date' => $start_date, 'end_date' => $end_date]);
    $reviews_data = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Conversie (gemiddelde verkoop per klant in EUR)
    $conversion = $data['total_customers'] > 0 ? $data['total_revenue'] / $data['total_customers'] : 0;

    // Voor de grafieken, maak labels en values arrays voor Chart.js
    $fullMonths = range(1, 12);

    // Verkoopgegevens
    $sales_labels = [];
    $sales_values = [];
    foreach ($fullMonths as $month) {
        $sales_labels[] = date('M', strtotime("2023-$month-01"));
        $sales_values[] = 0;

        foreach ($sales_data as $item) {
            if ($item['month'] == $month) {
                $sales_values[$month - 1] = $item['sales'];
                break;
            }
        }
    }

    // Omzetgegevens
    $revenue_labels = [];
    $revenue_values = [];
    foreach ($fullMonths as $month) {
        $revenue_labels[] = date('M', strtotime("2023-$month-01"));
        $revenue_values[] = 0;

        foreach ($revenue_data as $item) {
            if ($item['month'] == $month) {
                $revenue_values[$month - 1] = $item['revenue'];
                break;
            }
        }
    }

    // Klantengegevens
    $customers_labels = [];
    $customers_values = [];
    foreach ($fullMonths as $month) {
        $customers_labels[] = date('M', strtotime("2023-$month-01"));
        $customers_values[] = 0;

        foreach ($customers_data as $item) {
            if ($item['month'] == $month) {
                $customers_values[$month - 1] = $item['customers'];
                break;
            }
        }
    }

    // Voor de reviews grafiek
    $ratings_labels = [];
    $ratings_values = [];
    foreach ($reviews_data as $item) {
        $ratings_labels[] = $item['sterren'];
        $ratings_values[] = $item['count'];
    }

} catch (PDOException $e) {
    echo "Fout bij het uitvoeren van de query: " . $e->getMessage();
    die();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../js/chart.js" defer></script>
</head>
<body>
<div class="container mt-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="order.php">Bestellingen</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Producten</a></li>
                <li class="nav-item"><a class="nav-link" href="visitor.php">Bezoekers</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin.php">Home</a></li>

            </ul>
        </div>
    </nav>

    <!-- Datumfilter -->
    <form method="POST" action="dashboard.php">
        <div class="row">
            <div class="col-md-6">
                <label for="start_date">Start Datum:</label>
                <input type="date" id="start_date" name="start_date" class="form-control"
                       value="<?php echo $start_date; ?>">
            </div>
            <div class="col-md-6">
                <label for="end_date">Eind Datum:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Filter</button>
    </form>

    <!-- Statistieken Overzicht -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-header">Totale Verkoop</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $data['total_sales']; ?> Producten</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-header">Totale Omzet</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo number_format($data['total_revenue'], 2, ',', '.'); ?> EUR</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-header">Aantal Klanten</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $data['total_customers']; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-header">Gemiddelde Verkoop per Klant</div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo number_format($conversion, 2, ',', '.'); ?> EUR</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafieken -->
    <h3 class="mt-5">Verkoopstatistieken</h3>
    <div class="row">
        <div class="col-md-6">
            <h4 class="mt-3">Verkoop per Maand</h4>
            <canvas id="salesChart"></canvas>
        </div>
        <div class="col-md-6">
            <h4 class="mt-3">Omzet per Maand</h4>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6"><h3 class="mt-5">Sterbeoordelingen</h3>
                <canvas id="ratingsChart"></canvas>
            </div>
            <div class="col-md-6"><h3 class="mt-5">Aantal Klanten per Maand</h3>
                <canvas id="customersChart"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
    const salesData = <?php echo json_encode(['labels' => $sales_labels, 'values' => $sales_values]); ?>;
    const revenueData = <?php echo json_encode(['labels' => $revenue_labels, 'values' => $revenue_values]); ?>;
    const customersData = <?php echo json_encode(['labels' => $customers_labels, 'values' => $customers_values]); ?>;
    const ratingsData = <?php echo json_encode(['labels' => $ratings_labels, 'values' => $ratings_values]); ?>;

</script>
</body>
</html>
