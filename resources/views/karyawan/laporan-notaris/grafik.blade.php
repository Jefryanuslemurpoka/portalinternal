@extends('layouts.app')

@section('title', 'Grafik Perbandingan Notaris')
@section('page-title', 'Grafik Perbandingan Notaris')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📊 Grafik Perbandingan Order Notaris</h1>
                <p class="text-gray-600 text-sm mt-1">{{ $laporan->nama_bulan }}</p>
            </div>
            <a href="{{ route('karyawan.laporan-notaris.show', $laporan->id) }}" 
               class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
        @foreach($notarisList as $key => $nama)
            @php
                $total = $laporan->{'total_' . $key};
                $percentage = $laporan->grand_total > 0 ? ($total / $laporan->grand_total * 100) : 0;
            @endphp
            <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 border-teal-500">
                <p class="text-xs text-gray-600 mb-1">{{ $nama }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($total) }}</p>
                <p class="text-xs text-teal-600 mt-1">{{ number_format($percentage, 1) }}%</p>
            </div>
        @endforeach
    </div>

    <!-- Bar Chart -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Perbandingan Total Order</h3>
        <div class="h-96">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Order (Pie Chart)</h3>
            <div class="h-80">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Order (Doughnut Chart)</h3>
            <div class="h-80">
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Line Chart - Trend Bulanan Per Notaris -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Trend Order Bulanan Per Notaris (Tahun {{ $laporan->tahun }})</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($notarisList as $key => $nama)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ $nama }}</h4>
                    <div class="h-64">
                        <canvas id="lineChart{{ ucfirst($key) }}"></canvas>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari Laravel
    const notarisList = @json($notarisList);
    const laporan = @json($laporan);
    
    const labels = Object.values(notarisList);
    const dataValues = [
        laporan.total_gamal,
        laporan.total_eny,
        laporan.total_nyoman,
        laporan.total_otty,
        laporan.total_kartika,
        laporan.total_retno,
        laporan.total_neltje
    ];

    const colors = [
        'rgba(20, 184, 166, 0.8)',   // teal
        'rgba(59, 130, 246, 0.8)',   // blue
        'rgba(249, 115, 22, 0.8)',   // orange
        'rgba(236, 72, 153, 0.8)',   // pink
        'rgba(168, 85, 247, 0.8)',   // purple
        'rgba(34, 197, 94, 0.8)',    // green
        'rgba(234, 179, 8, 0.8)'     // yellow
    ];

    // Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Order',
                data: dataValues,
                backgroundColor: colors,
                borderColor: colors.map(c => c.replace('0.8', '1')),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 500
                    }
                }
            }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Doughnut Chart
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Line Charts - Trend Bulanan Per Notaris (Terpisah)
    const trendData = @json($trendData);
    
    // Jika tidak ada data trend (hanya 1 bulan), tampilkan pesan
    if (trendData.length < 2) {
        document.querySelectorAll('[id^="lineChart"]').forEach(canvas => {
            const ctx = canvas.getContext('2d');
            ctx.font = '14px sans-serif';
            ctx.fillStyle = '#9CA3AF';
            ctx.textAlign = 'center';
            ctx.fillText('Data trend bulanan akan muncul', canvas.width / 2, canvas.height / 2 - 10);
            ctx.fillText('setelah ada minimal 2 bulan laporan', canvas.width / 2, canvas.height / 2 + 10);
        });
    } else {
        const monthLabels = trendData.map(d => d.bulan_full);
        const notarisKeys = ['gamal', 'eny', 'nyoman', 'otty', 'kartika', 'retno', 'neltje'];
        const notarisLabels = ['Gamal', 'Eny', 'Nyoman', 'Otty', 'Kartika', 'Retno', 'Neltje'];
        
        // Buat chart terpisah untuk setiap notaris
        notarisKeys.forEach((key, index) => {
            const data = trendData.map(d => d[key]);
            const maxValue = Math.max(...data);
            
            new Chart(document.getElementById('lineChart' + notarisLabels[index]), {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: notarisLabels[index],
                        data: data,
                        borderColor: colors[index],
                        backgroundColor: colors[index].replace('0.8', '0.2'),
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointBackgroundColor: colors[index],
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    return 'Total Order: ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            max: maxValue > 0 ? Math.ceil(maxValue * 1.15) : 100,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    if (value >= 1000) {
                                        return (value / 1000).toFixed(1) + 'K';
                                    }
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    }
});
</script>
@endpush
@endsection