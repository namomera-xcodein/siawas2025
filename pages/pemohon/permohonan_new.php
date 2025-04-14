<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
// Ambil nama dan level dari session
$nama_user = $_SESSION['name'] ?? 'Pengguna';
$level_jabatan = $_SESSION['level_jabatan'] ?? 'Tidak Diketahui';
?>
<!-- Preloader -->
<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>

<div class="page-wrapper">
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
                    echo " " . $_SESSION['name'] . "</br> Anda Login sebagai " . $_SESSION['level_jabatan'] . "</br>";
                    ?>
                </h3>
            </div>
        </div>
    </div>

    <div class="container-fluid">
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
                                                placeholder="Isi dengan judul kegiatan permohonan" required />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h6 class="card-title">Input Data Barang</h6>
                                    </div>
                                    <table class="table" id="permohonanTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="text-center">Nama Barang</th>
                                                <th class="text-center">Satuan</th>
                                                <th class="text-center">Harga Satuan</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-center">Total Harga</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemRows"></tbody>
                                    </table>
                                </div>

                                <div class="form-actions text-right">
                                    <button type="button" class="btn btn-info" id="addItem">Tambah Barang</button>
                                    <button type="reset" class="btn btn-dark">Reset</button>
                                </div>
                                <h4 class="mt-3">Grand Total: Rp <span id="grand_total_permohonan">0</span></h4>
                                <input type="hidden" name="grand_total_harga" id="grand_total_permohonan_input">
                                <!-- <input type="hidden" name="grand_total_permohonan" id="grand_total_permohonan_input"> -->
                                <button type="submit" class="btn btn-success" id="savePermohonan" disabled>Ajukan
                                    Permohonan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        let itemCount = 0;

        $("#addItem").click(function() {
            itemCount++;
            let row = `<tr id="row${itemCount}">
                <td class="text-center">${itemCount}</td>
                <td><input type="text" name="nama_barang[]" class="form-control" required></td>
                <td><input type="text" name="satuan[]" class="form-control" required></td>
                <td><input type="number" name="harga_satuan[]" class="form-control harga_satuan" required></td>
                <td><input type="number" name="jumlah_barang[]" class="form-control jumlah_barang" required></td>
                <td><input type="text" name="total_harga_barang[]" class="form-control total_harga_barang" readonly></td>
                <td><button type="button" class="btn btn-danger removeItem" data-id="${itemCount}">Hapus</button></td>
            </tr>`;
            $("#itemRows").append(row);
            updateGrandTotal();
        });

        $(document).on("input", ".harga_satuan, .jumlah_barang", function() {
            let row = $(this).closest("tr");
            let harga = parseFloat(row.find(".harga_satuan").val()) || 0;
            let jumlah = parseInt(row.find(".jumlah_barang").val()) || 0;
            let total = harga * jumlah;
            row.find(".total_harga_barang").val(total);
            updateGrandTotal();
        });

        $(document).on("click", ".removeItem", function() {
            $(this).closest("tr").remove();
            updateGrandTotal();
        });

        function updateGrandTotal() {
            let grandTotal = 0;
            $(".total_harga_barang").each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });

            // Update tampilan Grand Total
            $("#grand_total_permohonan").text(new Intl.NumberFormat("id-ID").format(grandTotal));

            // Simpan nilai grand total ke input hidden agar bisa dikirim ke proses_permohonan.php
            $("#grand_total_permohonan_input").val(grandTotal);

            // Nonaktifkan tombol simpan jika tidak ada barang
            $("#savePermohonan").prop("disabled", $(".total_harga_barang").length === 0);
        }

        // function updateGrandTotal() {
        //     let grandTotal = 0;
        //     $(".total_harga_barang").each(function() {
        //         grandTotal += parseFloat($(this).val()) || 0;
        //     });
        //     $("#grand_total_permohonan").text(new Intl.NumberFormat("id-ID").format(grandTotal));
        //     $("#grand_total_permohonan_input").val(grandTotal);
        //     $("#savePermohonan").prop("disabled", $(".total_harga_barang").length === 0);
        // }

        $("form").submit(function(e) {
            e.preventDefault();
            let form = this;

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
                    form.submit();
                }
            });
        });
    });
    </script>
</div>