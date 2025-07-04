console.log("loaded")
// Verkoopgrafiek als Donut Chart
const ctx1 = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: salesData.labels,
        datasets: [{
            label: 'Aantal Verkochte Producten',
            data: salesData.values,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FF9F40'],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw + ' stuks';
                    }
                }
            }
        }
    }
});

// Omzetgrafiek als Lijn Chart
const ctx2 = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx2, {
    type: 'line',
    data: {
        labels: revenueData.labels,
        datasets: [{
            label: 'Totale Omzet',
            data: revenueData.values,
            borderColor: 'rgb(58,225,225)',
            backgroundColor: 'rgba(58,225,225, 0.4)',
            pointRadius: 3,
            fill: false,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '€' + value;
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return 'Omzet: €' + tooltipItem.raw;
                    }
                }
            }
        }
    }
});


// Klantengrafiek als Lijn Chart
const ctx3 = document.getElementById('customersChart').getContext('2d');
const customersChart = new Chart(ctx3, {
    type: 'line',
    data: {
        labels: customersData.labels,
        datasets: [{
            label: 'Aantal Klanten',
            data: customersData.values,
            borderColor: 'rgba(255,206,86,1)',
            backgroundColor: 'rgba(255,206,86, 0.4)',
            pointRadius: 3,
            fill: false,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                min: 0,
                ticks: {
                    callback: function(value) {
                        return value;
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return 'Klanten: ' + tooltipItem.raw;
                    }
                }
            }
        }
    }
});

// Sterbeoordelingen als Polar Area Chart
const ctx4 = document.getElementById('ratingsChart').getContext('2d');
const ratingsChart = new Chart(ctx4, {
    type: 'polarArea',
    data: {
        labels: ratingsData.labels,
        datasets: [{
            label: 'Aantal Reviews per Beoordeling',
            data: ratingsData.values,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FF9F40'],
        }]
    },
    options: {
        responsive: true
    }
});