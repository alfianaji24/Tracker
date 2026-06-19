// Initialize all dashboard charts
function initDashboardCharts(data) {
    // Chart Status Pembayaran
    if (document.getElementById('chartStatusPembayaran')) {
        const ctxStatus = document.getElementById('chartStatusPembayaran').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Belum Lunas', 'Lunas', 'Batal'],
                datasets: [{
                    data: [data.statusBelumLunas, data.statusLunas, data.statusBatal],
                    backgroundColor: ['#fbbf24', '#10b981', '#d1d5db'],
                    borderColor: ['#f59e0b', '#059669', '#9ca3af'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 13 },
                            padding: 15
                        }
                    }
                }
            }
        });
    }

    // Chart Tarif Distribution
    if (document.getElementById('chartTarifDistribusi')) {
        const ctxTarif = document.getElementById('chartTarifDistribusi').getContext('2d');
        new Chart(ctxTarif, {
            type: 'doughnut',
            data: {
                labels: data.tarifLabels,
                datasets: [{
                    data: data.tarifCounts,
                    backgroundColor: ['#667eea', '#f093fb', '#4facfe', '#fa709a', '#30cfd0'],
                    borderColor: ['#5568d3', '#e082ea', '#3e9cee', '#f95989', '#1fbfbf'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 13 },
                            padding: 15
                        }
                    }
                }
            }
        });
    }

    // Chart Pemakaian Per Bulan
    if (document.getElementById('chartPemakaianBulanan')) {
        const ctxPemakaian = document.getElementById('chartPemakaianBulanan').getContext('2d');
        new Chart(ctxPemakaian, {
            type: 'line',
            data: {
                labels: data.pemakaianLabels,
                datasets: [{
                    label: 'Pemakaian Air (m³)',
                    data: data.pemakaianBulan,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 13 },
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 12 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        const chartDataEl = document.getElementById('chartData');
        if (chartDataEl) {
            const chartData = JSON.parse(chartDataEl.dataset.charts);
            initDashboardCharts(chartData);
        }
    });
} else {
    const chartDataEl = document.getElementById('chartData');
    if (chartDataEl) {
        const chartData = JSON.parse(chartDataEl.dataset.charts);
        initDashboardCharts(chartData);
    }
}
