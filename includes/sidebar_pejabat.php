<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="index.php?page=dashboard" aria-expanded="false"><i
                            data-feather="home" class="feather-icon"></i><span class="hide-menu">Pejabat
                            Dashboard</span></a>
                </li>
                <!-- Form Permohonan -->
                <li class="list-divider"></li>

                <!-- end form Permohon -->
                <li class="nav-small-cap"><span class="hide-menu">Permohonan</span></li>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="index.php?page=permohonan_masuk" aria-expanded="false"><i
                            data-feather="file-text" class="feather-icon"></i><span class="hide-menu">Permohonan Masuk
                        </span></a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="index.php?page=riwayat_permohonan"
                        aria-expanded="false"><i data-feather="file-text" class="feather-icon"></i><span
                            class="hide-menu"> Riwayat
                            Persetujuan
                        </span></a>
                </li>
                <li class="sidebar-item text-muted">
                    <a class="sidebar-link sidebar-link" href="index.php?page=ditolak" aria-expanded="false"><i
                            data-feather="file-text" class="feather-icon"></i><span class="hide-menu"> Ditolak
                        </span></a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="index.php?page=all_permohonan" aria-expanded="false"><i
                            data-feather="file-text" class="feather-icon"></i><span class="hide-menu">Semua
                            Permohonan</span></a>
                </li>
                <li class="list-divider"></li>
                <li class="nav-small-cap"><span class="hide-menu">Pengaturan</span></li>

                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="index.php?page=profile" aria-expanded="false"><i
                            data-feather="lock" class="feather-icon"></i><span class="hide-menu"> Profile </span></a>
                </li>




                <!-- <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false"><i
                            data-feather="crosshair" class="feather-icon"></i><span class="hide-menu">Multi level
                            dd</span></a>
                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link"><span class="hide-menu"> item
                                    1.1</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link"><span class="hide-menu"> item
                                    1.2</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false"><span
                                    class="hide-menu">Menu 1.3</span></a>
                            <ul aria-expanded="false" class="collapse second-level base-level-line">
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link"><span class="hide-menu"> item
                                            1.3.1</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link"><span class="hide-menu"> item
                                            1.3.2</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link"><span class="hide-menu"> item
                                            1.3.3</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="sidebar-link"><span class="hide-menu"> item
                                            1.3.4</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link"><span class="hide-menu"> item
                                    1.4</span></a>
                        </li>
                    </ul>
                </li> -->


                <li class="nav-small-cap"><span class="hide-menu">Extra</span></li>
                <!-- <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="../../docs/docs.html" aria-expanded="false"><i
                            data-feather="edit-3" class="feather-icon"></i><span
                            class="hide-menu">Documentation</span></a>
                </li> -->
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="<?= $base_url ?>/auth/logout.php"
                        aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span
                            class="hide-menu">Logout</span></a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->