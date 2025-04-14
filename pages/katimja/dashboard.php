<?

?>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    <?php
                    $time = date('H');
                    if ($time >= 5 && $time <= 11) {
                        echo "Selamat Pagi";
                    } elseif ($time >= 12 && $time <= 15) {
                        echo "Selamat Siang";
                    } elseif ($time >= 16 && $time <= 18) {
                        echo "Selamat Sore";
                    } else {
                        echo "Selamat Malam";
                    }
                    echo " " . $_SESSION['name'] . "! ek<br>
                                Halo, Anda Login sebagai " . $_SESSION['role'] . "<br>userid anda adalah" . $_SESSION['user_id'] . "! ";
                    ?>
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard Keuangan</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-right">
                    <!-- <select
                        class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                        <option selected>Aug 19</option>
                        <option value="1">July 19</option>
                        <option value="2">Jun 19</option>
                    </select> -->
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- *************************************************************** -->
        <!-- Start First Cards -->
        <!-- *************************************************************** -->
        <div class="card-group">
            <div class="card border-right">
                <div class="card-body">
                    <div class="d-flex d-lg-flex d-md-block align-items-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                            <?php
                            // Pastikan session `user_id` ada
                            if (!isset($_SESSION['user_id'])) {
                                die("Error: Session user_id tidak ditemukan!");
                            }
                            $user_id = $_SESSION['user_id'];

                            // Perbaiki query dengan kolom yang benar
                            $sql = "SELECT users.name, level.level_jabatan, level.deskripsi_jabatan 
                            FROM users 
                            JOIN level ON users.level_user = level.id_level  -- level_user = id_level
                            WHERE users.id = ?";  // Perbaiki dari id_user menjadi id

                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $user = $result->fetch_assoc();
                            ?>

                        </h3>

                        <?php echo "Selamat Datang, " . htmlspecialchars($user['name']) . "!<br>";
                        echo "<strong>" . htmlspecialchars($user['level_jabatan']) . "</strong><br><br>";
                        echo "<small>" . htmlspecialchars($user['deskripsi_jabatan']) . "</small><br>"; ?> <div
                            class="ml-auto mt-md-3 mt-lg-0">
                            <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- *************************************************************** -->
        <!-- Start Top Leader Table -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="card-title">Data User</h4>
                            <div class="ml-auto">
                                <div class="dropdown sub-dropdown">
                                    <button class="btn btn-link text-muted dropdown-toggle" type="button" id="dd1"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
                                        <a class="dropdown-item" href="#">Insert</a>
                                        <a class="dropdown-item" href="#">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-4">
                                            <h4 class="card-title">Chart Permohonanx</h4>
                                        </div>
                                        <!-- Chart Container -->
                                        <div class="chart-container"
                                            style="position: relative; height:50vh; width:100%">
                                            <canvas id="statusChart"></canvas>
                                        </div>

                                        <!-- Add Chart.js -->
                                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                                        <?php
                                        // Get data from database
                                        $sql = "SELECT status, COUNT(*) as count FROM permohonan GROUP BY status";
                                        $result = $conn->query($sql);

                                        $labels = [];
                                        $data = [];
                                        $colors = ['#FFC107', '#28A745', '#DC3545']; // Yellow for waiting, Green for approved, Red for rejected

                                        while ($row = $result->fetch_assoc()) {
                                            $labels[] = $row['status'];
                                            $data[] = $row['count'];
                                        }
                                        ?>

                                        <script>
                                        const ctx = document.getElementById('statusChart').getContext('2d');
                                        new Chart(ctx, {
                                            type: 'pie', // You can change to 'bar' for bar chart
                                            data: {
                                                labels: <?php echo json_encode($labels); ?>,
                                                datasets: [{
                                                    data: <?php echo json_encode($data); ?>,
                                                    backgroundColor: <?php echo json_encode($colors); ?>,
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                plugins: {
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Distribusi Status Permohonan'
                                                    }
                                                }
                                            }
                                        });
                                        </script>


                                        <div class="chart-container"
                                            style="position: relative; height:50vh; width:100%">
                                            <canvas id="userChart"></canvas>
                                        </div>

                                        <?php
                                        // Get data from database for user applications
                                        $sql = "SELECT u.name, COUNT(*) as count 
                                                           FROM permohonan p 
                                                           JOIN users u ON p.user_id = u.id 
                                                           GROUP BY u.name";
                                        $result = $conn->query($sql);

                                        $userLabels = [];
                                        $userData = [];

                                        while ($row = $result->fetch_assoc()) {
                                            $userLabels[] = $row['name'];
                                            $userData[] = $row['count'];
                                        }
                                        ?>

                                        <script>
                                        const userCtx = document.getElementById('userChart').getContext('2d');
                                        new Chart(userCtx, {
                                            type: 'bar',
                                            data: {
                                                labels: <?php echo json_encode($userLabels); ?>,
                                                datasets: [{
                                                    label: 'Jumlah Permohonan',
                                                    data: <?php echo json_encode($userData); ?>,
                                                    backgroundColor: '#4B89DC',
                                                    borderColor: '#3671BC',
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
                                                            stepSize: 1
                                                        }
                                                    }
                                                },
                                                plugins: {
                                                    legend: {
                                                        position: 'top',
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Distribusi Permohonan per Pengguna'
                                                    }
                                                }
                                            }
                                        });
                                        </script>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- *************************************************************** -->
        <!-- End Top Leader Table -->
        <!-- *************************************************************** -->
    </div>