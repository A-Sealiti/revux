<?php
// Verbind met de database
include_once 'database.php';

// Haal de geselecteerde tijdsperiode (maand, week, dag) op uit de GET-parameters
$interval = isset($_GET['interval']) ? $_GET['interval'] : 'month';

// Pas de query aan op basis van het geselecteerde interval
if ($interval === 'week') {
    $query = "SELECT YEAR(visit_date) AS year, WEEK(visit_date) AS week, COUNT(*) AS visitor_count 
              FROM visitor_log 
              GROUP BY YEAR(visit_date), WEEK(visit_date)
              ORDER BY year DESC, week DESC";
} elseif ($interval === 'day') {
    $query = "SELECT DATE(visit_date) AS date, COUNT(*) AS visitor_count 
              FROM visitor_log 
              GROUP BY DATE(visit_date)
              ORDER BY date DESC";
} else { // Default is maand
    $query = "SELECT YEAR(visit_date) AS year, MONTH(visit_date) AS month, COUNT(*) AS visitor_count 
              FROM visitor_log 
              GROUP BY YEAR(visit_date), MONTH(visit_date)
              ORDER BY year DESC, month DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h1>Bezoekers per Periode</h1>
        <div class="button-container">
            <form method="GET" action="" style="display: inline;">
                <input type="hidden" name="interval" value="month">
                <button type="submit">Maand</button>
            </form>
            <form method="GET" action="" style="display: inline;">
                <input type="hidden" name="interval" value="week">
                <button type="submit">Week</button>
            </form>
            <form method="GET" action="" style="display: inline;">
                <input type="hidden" name="interval" value="day">
                <button type="submit">Dag</button>
            </form>
        </div>

        <!-- Canvas voor de grafiek -->
        <canvas id="visitorChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('visitorChart').getContext('2d');
        const visitorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_map(function($item) {
                    if (isset($item['week'])) {
                        return 'Week ' . $item['week'] . ' - ' . $item['year'];
                    } elseif (isset($item['date'])) {
                        return $item['date'];
                    } else {
                        return $item['year'] . '-' . str_pad($item['month'], 2, '0', STR_PAD_LEFT);
                    }
                }, array_reverse($data))); ?>,
                datasets: [{
                    label: 'Aantal Bezoekers',
                    data: <?php echo json_encode(array_column(array_reverse($data), 'visitor_count')); ?>, // Draai de volgorde van de data om
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: '<?php echo ucfirst($interval); ?>'
                        },
                        grid: {
                            display: true,
                            color: '#e1e1e1'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Aantal Bezoekers'
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            callback: function(value) {
                                return Math.round(value);
                            }
                        },
                        grid: {
                            display: true,
                            color: '#e1e1e1'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#000',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#fff',
                        borderWidth: 1
                    }
                }
            }
        });
    </script>
</body>
</html>
