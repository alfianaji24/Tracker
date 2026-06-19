// Dashboard Charts Module

export function initStatusPembayaranChart(statusBelumLunas, statusLunas, statusBatal) {
    const ctxStatus = document.getElementById('chartStatusPembayaran');
    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Belum Lunas', 'Lunas', 'Batal'],
                datasets: [{
                    data: [statusBelumLunas, statusLunas, statusBatal],
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
                            font: {
                                size: 13
                            },
                            padding: 15
                        }
                    }
                }
            }
        });
    }
}

export function initTarifDistribusiChart(tarifLabels, tarifCounts) {
    const ctxTarif = document.getElementById('chartTarifDistribusi');
    if (ctxTarif) {
        new Chart(ctxTarif, {
            type: 'doughnut',
            data: {
                labels: tarifLabels,
                datasets: [{
                    data: tarifCounts,
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
                            font: {
                                size: 13
                            },
                            padding: 15
                        }
                    }
                }
            }
        });
    }
}

export function initPemakaianBulananChart(pemakaianLabels, pemakaianBulan) {
    const ctxPemakaian = document.getElementById('chartPemakaianBulanan');
    if (ctxPemakaian) {
        new Chart(ctxPemakaian, {
            type: 'line',
            data: {
                labels: pemakaianLabels,
                datasets: [{
                    label: 'Pemakaian Air (m³)',
                    data: pemakaianBulan,
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
                            font: {
                                size: 13
                            },
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
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

export function initTagihanPembayaranChart(tagihanLabels, tagihanPerBulan, pembayaranPerBulan) {
    const ctxTagihan = document.getElementById('chartTagihanPembayaran');
    if (ctxTagihan) {
        new Chart(ctxTagihan, {
            type: 'bar',
            data: {
                labels: tagihanLabels,
                datasets: [{
                        label: 'Tagihan Bulanan',
                        data: tagihanPerBulan,
                        backgroundColor: '#8b8bd4',
                        borderColor: '#7a7ac1',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Terbayar',
                        data: pembayaranPerBulan,
                        backgroundColor: '#5d6fff',
                        borderColor: '#4c5ee6',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 13
                            },
                            padding: 15,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
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

export function initAllCharts(chartData) {
    document.addEventListener('DOMContentLoaded', function() {
        initStatusPembayaranChart(chartData.statusBelumLunas, chartData.statusLunas, chartData.statusBatal);
        initTarifDistribusiChart(chartData.tarifLabels, chartData.tarifCounts);
        initPemakaianBulananChart(chartData.pemakaianLabels, chartData.pemakaianBulan);
        initTagihanPembayaranChart(chartData.tagihanLabels, chartData.tagihanPerBulan, chartData.pembayaranPerBulan);
    });
}
