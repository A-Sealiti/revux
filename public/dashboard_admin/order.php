<?php
include_once 'database.php';

try {
    $sql = "SELECT MONTH(date) AS month, COUNT(*) AS order_count 
            FROM purchase
            GROUP BY MONTH(date)
            ORDER BY month ASC";
    $stmt = $pdo->query($sql);
    $months = [];
    $orderCounts = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $months[] = date('F', mktime(0, 0, 0, $row['month'], 10)); // Maandnaam
        $orderCounts[] = $row['order_count'];
    }
} catch (PDOException $e) {
    die("Fout bij het ophalen van gegevens: " . $e->getMessage());
}

// Zet de gegevens om naar JSON voor de JavaScript
$data = [
    'labels' => $months,
    'values' => $orderCounts
];

// Verstuur de data als JSON
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode($data);
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

        <!-- Dashboard content -->
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center mb-4">Dashboard Bestellingen</h1>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <canvas id="ordersChart" style="height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    fetch('order.php?ajax=true')
        .then((response) => response.json())
        .then((ordersData) => {
            const ctx = document.getElementById('ordersChart').getContext('2d');
            const ordersChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ordersData.labels,
                    datasets: [{
                        label: 'Aantal Bestellingen',
                        data: ordersData.values,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            enabled: true,
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
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Aantal Bestellingen'
                            }
                        }
                    }
                }
            });
        })
</script>
</body>
</html>