<?php
//include '../../koneksi.php';

/// ===== 1. Donut Chart: Jumlah permohonan berdasarkan status_permohonan =====
$statusQuery = $conn->query("SELECT id_status, nama_status FROM status_permohonan");
$statusLabelsMap = [];
$statusDataMap = [];

// Inisialisasi label dan data default
while ($row = $statusQuery->fetch_assoc()) {
    $statusLabelsMap[$row['id_status']] = $row['nama_status'];
    $statusDataMap[$row['id_status']] = 0;
}

// Hitung jumlah permohonan per status2
$permohonanQuery = $conn->query("SELECT status2, COUNT(*) as jumlah FROM permohonan GROUP BY status2");
while ($row = $permohonanQuery->fetch_assoc()) {
    $statusDataMap[$row['status2']] = $row['jumlah'];
}

$donutLabels = array_values($statusLabelsMap);
$donutData = array_values($statusDataMap);


/// ===== 2. Bar Chart: Jumlah permohonan per bulan =====
$barLabels = [];
$barData = [];

$queryBar = "
    SELECT 
        DATE_FORMAT(tanggal_permohonan, '%Y-%m') AS bulan, 
        COUNT(*) AS jumlah 
    FROM permohonan 
    GROUP BY bulan 
    ORDER BY bulan ASC
";
$resultBar = $conn->query($queryBar);

while ($row = $resultBar->fetch_assoc()) {
    $barLabels[] = date('F Y', strtotime($row['bulan'] . "-01"));
    $barData[] = $row['jumlah'];
}


/// ===== 3. Line Chart: Total Nilai Permohonan per Bulan =====
$lineLabels = [];
$lineData = [];

$queryLine = "
    SELECT 
        DATE_FORMAT(tanggal_permohonan, '%Y-%m') AS bulan,
        SUM(grand_total_harga) AS total
    FROM permohonan
    GROUP BY bulan
    ORDER BY bulan ASC
";
$resultLine = $conn->query($queryLine);

while ($row = $resultLine->fetch_assoc()) {
    $lineLabels[] = date('F Y', strtotime($row['bulan'] . "-01"));
    $lineData[] = (float) $row['total'];
}

$query = "
    SELECT 
        YEAR(tanggal_permohonan) AS tahun, 
        MONTH(tanggal_permohonan) AS bulan,
        COUNT(*) AS jumlah
    FROM permohonan
    WHERE YEAR(tanggal_permohonan) IN (YEAR(CURDATE()) - 1, YEAR(CURDATE()))
    GROUP BY tahun, bulan
    ORDER BY bulan ASC, tahun ASC
";
$result = $conn->query($query);

// Siapkan array kosong
$dataChart = [];

// Inisialisasi data per bulan
for ($i = 1; $i <= 12; $i++) {
    $dataChart[$i] = [
        'bulan' => date('F', mktime(0, 0, 0, $i, 10)),
        'y' => 0, // Tahun sebelumnya
        'z' => 0  // Tahun sekarang
    ];
}

$tahun_sekarang = date('Y');
$tahun_lalu = $tahun_sekarang - 1;

while ($row = $result->fetch_assoc()) {
    $bulan = (int)$row['bulan'];
    if ($row['tahun'] == $tahun_lalu) {
        $dataChart[$bulan]['y'] = (int)$row['jumlah'];
    } elseif ($row['tahun'] == $tahun_sekarang) {
        $dataChart[$bulan]['z'] = (int)$row['jumlah'];
    }
}

// Encode jadi JSON
$morris_data = [];

foreach ($dataChart as $bulan => $item) {
    $morris_data[] = [
        'bulan' => $item['bulan'],
        'Tahun_Lalu' => $item['y'],
        'Tahun_Sekarang' => $item['z']
    ];
}

?>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h5 class="page-title text-truncate text-dark font-weight-medium mb-1">Grafik Permohonan</h5>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard Pengawas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Grafik Permohonan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        <div class="row">
            <!-- Donut Chart -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Status Permohonan</h4>
                        <div class="chart-container" style="position: relative; height: 400px; width: 100%;">
                            <canvas id="donutChart"></canvas>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Jumlah Permohonan per Bulan</h4>
                        <div class="chart-container" style="position: relative; height: 400px; width: 100%;">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Line Chart -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Total Nilai Permohonan per Bulan</h4>
                        <div class="chart-container" style="position: relative; height: 400px; width: 100%;">
                            <canvas id="lineChart"></canvas>
                        </div>

                    </div>
                </div>
            </div>

            <!-- column -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Perbandingan Jumlah Permohonan: <?= $tahun_lalu ?> vs
                            <?= $tahun_sekarang ?></h4>
                        <div id="morris-bar-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            <!-- column -->

        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Donut Chart
            var donutCtx = document.getElementById('donutChart').getContext('2d');
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($donutLabels) ?>,
                    datasets: [{
                        data: <?= json_encode($donutData) ?>,
                        backgroundColor: [
                            '#4fc3f7', '#f1c40f', '#2ecc71', '#e74c3c',
                            '#9b59b6', '#1abc9c', '#e67e22', '#34495e', '#95a5a6'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Bar Chart
            var barCtx = document.getElementById('barChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($barLabels) ?>,
                    datasets: [{
                        label: 'Jumlah Permohonan',
                        data: <?= json_encode($barData) ?>,
                        backgroundColor: '#4fc3f7',
                        borderColor: '#0288d1',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Line Chart
            var lineCtx = document.getElementById('lineChart').getContext('2d');
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($lineLabels) ?>,
                    datasets: [{
                        label: 'Total Nilai (Rp)',
                        data: <?= json_encode($lineData) ?>,
                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                        borderColor: '#2ecc71',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        </script>

        <script>
            new Morris.Bar({
                element: 'morris-bar-chart',
                data: <?= json_encode($morris_data); ?>,
                xkey: 'bulan',
                ykeys: ['Tahun_Lalu', 'Tahun_Sekarang'],
                labels: ['<?= $tahun_lalu ?>', '<?= $tahun_sekarang ?>'],
                barColors: ['#1e88e5', '#ffb22b'],
                hideHover: 'auto',
                resize: true
            });
        </script>

    </div>
</div>
