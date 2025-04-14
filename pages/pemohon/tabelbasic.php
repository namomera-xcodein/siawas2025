<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Riwayat Permohonan</h4>
                    <div class="table-responsive">
                        <table id="riwayatTable" class="table table-striped table-bordered no-wrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Permohonan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $user_id = $_SESSION['user_id'];
                                
                                $query = "SELECT p.*, jp.nomor_permohonan 
                                         FROM permohonan p
                                         JOIN nomor_permohonan jp ON p.jenis_id = jp.id
                                         WHERE p.user_id = ?
                                         ORDER BY p.created_at DESC";
                                
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                
                                $no = 1;
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . date('d-m-Y', strtotime($row['created_at'])) . "</td>";
                                    echo "<td>" . $row['nama_jenis'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>
                                            <a href='detail_permohonan.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>Detail</a>
                                            <a href='tracking.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Track</a>
                                          </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.bootstrap5.min.css">

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js">
    </script>

    <script>
    $(document).ready(function() {
        $('#riwayatTable').DataTable({
            responsive: true,
            "ordering": true,
            "searching": true,
            "paging": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
            }
        });
    });
    </script>
</div>