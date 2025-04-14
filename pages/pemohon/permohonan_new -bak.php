<?php
var_dump(session_status()); // Cek status session

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>
<!-- ============================================================== -->
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
                                echo " " . $_SESSION['name'] . "! Anda Login sebagai " . $_SESSION['role'];
                                ?>
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard default</a></li>
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

        </div>
        <!-- *************************************************************** -->
        <!-- *************************************************************** -->
        <!-- Start Top Leader Table -->
        <!-- *************************************************************** -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Permohonan Baru</h4>
                        <form method="post" action="index.php?page=proses_permohonan">
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <h6 class="card-title">Judul Kegiatan</h6>
                                        <div class="form-group">
                                            <input type="text" name="detail_kegiatan" class="form-control"
                                                placeholder="isi dengan judul kegiatan permohonan" />
                                        </div>
                                    </div>
                                    <div class="col-12"><br></div>
                                    <div class="col-md-12">
                                        <h6 class="card-title">Input Data Barang</h6>
                                    </div>
                                    <table class="table" id="permohonanTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Nama Barang</th>
                                                <th class="text-center">Satuan<bR><small class="text-muted">(contoh:
                                                        pax,buah,pcs)</small></th>
                                                <th class="text-center">Harga Satuan<br><small class="text-muted">(harga
                                                        satuan barang)</small>
                                                </th>
                                                <th class="text-center">Jumlah Barang</th>
                                                <th class="text-center">Total Harga</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemRows"></tbody>
                                    </table>
                                </div>

                                <div class="form-actions">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-info" id="addItem">Tambah Barang</button>
                                        <button type="reset" class="btn btn-dark">Reset</button>
                                    </div>
                                </div>
                                <h4 class="mt-3">Grand Total: Rp <span id="grandTotal"
                                        name="total_harga_detail[]">0</span></h4>
                                <button type="submit" class="btn btn-success" id="savePermohonan" disabled>Ajukan
                                    Permohonan</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- *************************************************************** -->
        <!-- End Top Leader Table -->



    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        let itemCount = 0;

        $("#addItem").click(function() {
            itemCount++;
            let row = `<tr id="row${itemCount}">
             <td class="text-center">${itemCount}</td>            <td><input type="text" name="nama_barang[]" class="form-control" required></td>
            <td><input type="text" name="satuan[]" class="form-control" required></td>
            <td><input type="number" name="harga_satuan[]" class="form-control harga_satuan" required></td>
            <td><input type="number" name="jumlah[]" class="form-control jumlah" required></td>
            <td><input type="text" name="total_harga[]" class="form-control total_harga" readonly></td>
            <td><button type="button" class="btn btn-danger removeItem" data-id="${itemCount}">Hapus</button></td>
        </tr>`;
            $("#itemRows").append(row);
            updateGrandTotal();
        });

        $(document).on("input", ".harga_satuan, .jumlah", function() {
            let row = $(this).closest("tr");
            let harga = parseFloat(row.find(".harga_satuan").val()) || 0;
            let jumlah = parseInt(row.find(".jumlah").val()) || 0;
            let total = harga * jumlah;
            row.find(".total_harga").val(total);
            updateGrandTotal();
        });

        $(document).on("click", ".removeItem", function() {
            $(this).closest("tr").remove();
            updateGrandTotal();
        });

        function updateGrandTotal() {
            let grandTotal = 0;
            $(".total_harga").each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $("#grandTotal").text(new Intl.NumberFormat("id-ID").format(grandTotal));
            $("#savePermohonan").prop("disabled", $(".total_harga").length === 0);
        }
        // SweetAlert2 Konfirmasi Submit
        $("form").submit(function(e) {
            e.preventDefault(); // Mencegah submit langsung
            let form = this; // Simpan referensi form

            Swal.fire({
                title: "Konfirmasi Pengajuan",
                text: "Apakah Anda yakin ingin mengajukan permohonan ini? Permohonan tidak dapat dibatalkan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, ajukan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Gunakan referensi form yang disimpan
                }
            });
        });

    });
    $("form").submit(function(e) {
        $("#grandTotalInput").val($("#grandTotal").text().replace(/\D/g, '')); // Ambil total tanpa format
    });
    </script>