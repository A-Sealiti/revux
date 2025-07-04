<?php
include_once 'database.php';

// Functie voor het genereren van een kleur op basis van merknaam
function generateColorFromName($name) {
    $hash = md5($name); // Genereer een hash op basis van de merknaam
    $r = hexdec(substr($hash, 0, 2));
    $g = hexdec(substr($hash, 2, 2));
    $b = hexdec(substr($hash, 4, 2));
    return "rgba($r, $g, $b, 0.5)";
}

// Ophalen van scooter gegevens per maand
try {
    // Query voor het ophalen van verkoop per merk per maand
    $sql = "SELECT m.naam, 
                MONTH(p.date) AS maand, 
                SUM(ps.amount) AS total_sold
         FROM scooter_brands sb
         LEFT JOIN merk m ON sb.merk_id = m.id 
         LEFT JOIN purchase_scooters ps ON sb.id = ps.scooter_id
         LEFT JOIN purchase p ON ps.purchase_id = p.id
         GROUP BY m.naam, MONTH(p.date)
         ORDER BY m.naam, maand";


    $stmt = $pdo->query($sql);
    $scooterNames = [];
    $salesData = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Zorg ervoor dat de merknaam uniek is
        if (!in_array($row['naam'], $scooterNames)) {
            $scooterNames[] = $row['naam'];
        }

        // Voeg de maandgegevens toe aan de salesData array
        $salesData[$row['naam']][$row['maand']] = $row['total_sold'] ? (int)$row['total_sold'] : 0;
    }
} catch (PDOException $e) {
    die("Fout bij het ophalen van gegevens: " . $e->getMessage());
}

// Zet de gegevens om naar JSON voor de JavaScript
$scooterData = [
    'labels' => ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'], // Maanden
    'datasets' => []
];

// Zet de verkoopdata voor elke scooternaam om in een dataset voor de grafiek
foreach ($scooterNames as $name) {
    $monthlySales = [];
    for ($month = 1; $month <= 12; $month++) {
        $monthlySales[] = isset($salesData[$name][$month]) ? $salesData[$name][$month] : 0; // Zet 0 als geen verkoop
    }

    // Dynamisch kleur genereren op basis van merknaam
    $backgroundColor = generateColorFromName($name);
    $borderColor = str_replace('0.5', '1', $backgroundColor); // Verander transparantie van de rand

    // Voeg de dataset toe aan scooterData
    $scooterData['datasets'][] = [
        'label' => $name,
        'data' => $monthlySales,
        'backgroundColor' => $backgroundColor,
        'borderColor' => $borderColor,
        'borderWidth' => 1
    ];
}

// Houdt rekening met AJAX-verzoeken
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode($scooterData);
    exit; // Stop verdere uitvoer
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
    <link rel="stylesheet" href="dashboard.css">


</head>
<body>
<div class="container-fluid">
    <div class="container mt-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="dashboard.php">Admin Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="#">Bestellingen</a></li>
                        <li class="nav-item"><a class="nav-link" href="products.php">Producten</a></li>
                        <li class="nav-item"><a class="nav-link" href="visitor.php">Bezoekers</a></li>
                        <li class="nav-item"><a class="nav-link" href="../admin.php">Home</a></li>

                    </ul>
                </div>
            </div>
        </nav>
<div class="container mt-4">
    <h1 class="text-center">Scooter Verkoop Dashboard</h1>

    <!-- Verkoopgrafiek -->
    <div class="card shadow-sm">
        <div class="card-body">
            <canvas id="scooterSalesChart" style="height: 400px;"></canvas>
        </div>
    </div>
</div>

<script>
    // Ophalen van gegevens
    fetch('products.php?ajax=true')
        .then((response) => {
            console.log('Respons status:', response.status);
            if (!response.ok) {
                throw new Error('Netwerk fout: ' + response.statusText);
            }
            return response.json();
        })
        .then((scooterData) => {
            const ctx = document.getElementById('scooterSalesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: scooterData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Maanden'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Aantal Verkocht'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        })
</script>
</body>
</html>
