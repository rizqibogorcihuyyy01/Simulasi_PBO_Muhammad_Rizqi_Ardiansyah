<?php
// Impor berkas koneksi dan kelas-kelas PBO
require_once 'koneksi/database.php';
require_once 'Pendaftaran.php';
require_once 'PendaftaranReguler.php';
require_once 'PendaftaranPrestasi.php';
require_once 'PendaftaranKedinasan.php';

// Inisialisasi koneksi database
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    echo "<div style='color:#ef4444; background:#1e293b; text-align:center; padding:50px; font-family:sans-serif; min-height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center;'>
            <h2 style='margin-bottom:10px;'>Koneksi Database Gagal</h2>
            <p>Pastikan server database MySQL aktif dan database <strong>db_pendaftaran</strong> serta tabelnya sudah di-import.</p>
          </div>";
    exit;
}

// Memanggil metode query spesifik masing-masing kelas anak (Tahap 4)
$daftarReguler = PendaftaranReguler::getDaftarReguler($db);
$daftarPrestasi = PendaftaranPrestasi::getDaftarPrestasi($db);
$daftarKedinasan = PendaftaranKedinasan::getDaftarKedinasan($db);

// Menghitung data statistik untuk Dashboard
$countReguler = count($daftarReguler);
$countPrestasi = count($daftarPrestasi);
$countKedinasan = count($daftarKedinasan);
$totalPendaftar = $countReguler + $countPrestasi + $countKedinasan;

$totalBiaya = 0;
$totalNilai = 0;

// Penggabungan seluruh pendaftaran untuk list global
$semuaPendaftaran = array_merge($daftarReguler, $daftarPrestasi, $daftarKedinasan);

foreach ($semuaPendaftaran as $p) {
    $totalBiaya += $p->hitungTotalBiaya();
    $totalNilai += $p->getNilaiUjian();
}

$rataRataNilai = $totalPendaftar > 0 ? round($totalNilai / $totalPendaftar, 2) : 0;

// Navigasi halaman aktif
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD PMB - Simulasi UAS PBO</title>
    <!-- Google Fonts Plus Jakarta Sans & FontAwesome Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="brand-text">PMB Portal</div>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item <?= $page == 'dashboard' ? 'active' : '' ?>">
                    <a href="index.php?page=dashboard">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item <?= $page == 'reguler' ? 'active' : '' ?>">
                    <a href="index.php?page=reguler">
                        <i class="fa-solid fa-user-graduate"></i>
                        <span>Jalur Reguler</span>
                    </a>
                </li>
                <li class="menu-item <?= $page == 'prestasi' ? 'active' : '' ?>">
                    <a href="index.php?page=prestasi">
                        <i class="fa-solid fa-award"></i>
                        <span>Jalur Prestasi</span>
                    </a>
                </li>
                <li class="menu-item <?= $page == 'kedinasan' ? 'active' : '' ?>">
                    <a href="index.php?page=kedinasan">
                        <i class="fa-solid fa-building-shield"></i>
                        <span>Jalur Kedinasan</span>
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <p>Simulasi UAS PBO</p>
                <p style="font-weight: 600; color: var(--text-primary); margin-top: 4px;">M. Rizqi Ardiansyah</p>
            </div>
        </aside>
        
        <!-- Main Content Section -->
        <main class="main-content">
            <!-- Top Navbar -->
            <header class="navbar">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <button class="menu-toggle" id="sidebarToggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="nav-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="tableSearch" placeholder="Cari nama atau asal sekolah..." onkeyup="searchTable()">
                    </div>
                </div>
                
                <div class="navbar-profile">
                    <div class="profile-info">
                        <div class="profile-name">M. Rizqi Ardiansyah</div>
                        <div class="profile-role">Panitia Penerimaan</div>
                    </div>
                    <div class="profile-avatar">RA</div>
                </div>
            </header>
            
            <div class="content-body">
                <?php if ($page == 'dashboard'): ?>
                    <!-- DASHBOARD OVERVIEW PAGE -->
                    <div class="page-header">
                        <h1 class="page-title">Dashboard Analitik</h1>
                        <p class="page-subtitle">Ikhtisar data penerimaan mahasiswa baru seluruh jalur pendaftaran.</p>
                    </div>
                    
                    <!-- Stats Grid -->
                    <div class="stats-grid">
                        <div class="stat-card card-blue">
                            <div class="stat-header">
                                <span class="stat-title">Total Calon Mahasiswa</span>
                                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                            </div>
                            <div class="stat-value"><?= $totalPendaftar ?></div>
                            <div class="stat-desc">Semua jalur pendaftaran aktif</div>
                        </div>
                        
                        <div class="stat-card card-purple">
                            <div class="stat-header">
                                <span class="stat-title">Total Pendapatan</span>
                                <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
                            </div>
                            <div class="stat-value">Rp <?= number_format($totalBiaya, 0, ',', '.') ?></div>
                            <div class="stat-desc">Kalkulasi biaya setelah diskon & surcharge</div>
                        </div>
                        
                        <div class="stat-card card-green">
                            <div class="stat-header">
                                <span class="stat-title">Rata-rata Nilai Ujian</span>
                                <div class="stat-icon"><i class="fa-solid fa-chart-simple"></i></div>
                            </div>
                            <div class="stat-value"><?= $rataRataNilai ?></div>
                            <div class="stat-desc">Skala penilaian maksimal 100</div>
                        </div>
                        
                        <div class="stat-card card-yellow">
                            <div class="stat-header">
                                <span class="stat-title">Sebaran Jalur (R | P | K)</span>
                                <div class="stat-icon"><i class="fa-solid fa-pie-chart"></i></div>
                            </div>
                            <div class="stat-value" style="font-size: 16px; margin-top: 15px; font-weight: 700;">
                                Reg: <?= $countReguler ?> | Pres: <?= $countPrestasi ?> | Dinas: <?= $countKedinasan ?>
                            </div>
                            <div class="stat-desc">Perbandingan pendaftar per jalur</div>
                        </div>
                    </div>
                    
                    <!-- Main Table showing all paths (Polymorphism Demo) -->
                    <div class="data-card">
                        <div class="card-title-bar">
                            <span class="card-title-text"><i class="fa-solid fa-list"></i> Seluruh Calon Mahasiswa</span>
                        </div>
                        <div class="table-responsive">
                            <table class="custom-table" id="pendaftaranTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Asal Sekolah</th>
                                        <th>Nilai Ujian</th>
                                        <th>Jalur</th>
                                        <th>Informasi Khusus Jalur</th>
                                        <th>Biaya Dasar</th>
                                        <th>Total Biaya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($semuaPendaftaran) > 0): ?>
                                        <?php $no = 1; foreach ($semuaPendaftaran as $p): ?>
                                            <?php 
                                            $badgeClass = '';
                                            $jalurText = '';
                                            if ($p instanceof PendaftaranReguler) {
                                                $badgeClass = 'badge-reguler';
                                                $jalurText = 'Reguler';
                                            } elseif ($p instanceof PendaftaranPrestasi) {
                                                $badgeClass = 'badge-prestasi';
                                                $jalurText = 'Prestasi';
                                            } elseif ($p instanceof PendaftaranKedinasan) {
                                                $badgeClass = 'badge-kedinasan';
                                                $jalurText = 'Kedinasan';
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td style="font-weight:600; color:var(--accent-indigo);">#<?= sprintf("%03d", $p->getIdPendaftaran()) ?></td>
                                                <td class="search-name" style="font-weight:600;"><?= htmlspecialchars($p->getNamaCalon()) ?></td>
                                                <td class="search-school"><?= htmlspecialchars($p->getAsalSekolah()) ?></td>
                                                <td><span class="score-badge"><?= number_format($p->getNilaiUjian(), 2) ?></span></td>
                                                <td><span class="badge <?= $badgeClass ?>"><?= $jalurText ?></span></td>
                                                <td>
                                                    <!-- Polimorfisme tampilkanInfoJalur() -->
                                                    <div class="info-box">
                                                        <span class="info-value"><?= htmlspecialchars($p->tampilkanInfoJalur()) ?></span>
                                                    </div>
                                                </td>
                                                <td class="price-base">Rp <?= number_format($p->getBiayaPendaftaranDasar(), 0, ',', '.') ?></td>
                                                <td class="price-final <?= $p instanceof PendaftaranKedinasan ? 'text-orange' : ($p instanceof PendaftaranPrestasi ? 'text-green' : '') ?>">
                                                    <!-- Polimorfisme hitungTotalBiaya() -->
                                                    Rp <?= number_format($p->hitungTotalBiaya(), 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="empty-state">
                                                <i class="fa-solid fa-folder-open"></i>
                                                <p>Tidak ada data pendaftaran ditemukan.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                <?php elseif ($page == 'reguler'): ?>
                    <!-- REGULER PATHWAY VIEW -->
                    <div class="page-header">
                        <h1 class="page-title">Pendaftaran Jalur Reguler</h1>
                        <p class="page-subtitle">Menampilkan calon mahasiswa yang mendaftar melalui seleksi Jalur Reguler.</p>
                    </div>
                    
                    <div class="data-card">
                        <div class="card-title-bar">
                            <span class="card-title-text"><i class="fa-solid fa-user-graduate"></i> Calon Mahasiswa Jalur Reguler</span>
                        </div>
                        <div class="table-responsive">
                            <table class="custom-table" id="pendaftaranTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Asal Sekolah</th>
                                        <th>Nilai Ujian</th>
                                        <th>Program Studi</th>
                                        <th>Lokasi Kampus</th>
                                        <th>Biaya Dasar</th>
                                        <th>Total Biaya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($daftarReguler) > 0): ?>
                                        <?php $no = 1; foreach ($daftarReguler as $p): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td style="font-weight:600; color:var(--accent-indigo);">#<?= sprintf("%03d", $p->getIdPendaftaran()) ?></td>
                                                <td class="search-name" style="font-weight:600;"><?= htmlspecialchars($p->getNamaCalon()) ?></td>
                                                <td class="search-school"><?= htmlspecialchars($p->getAsalSekolah()) ?></td>
                                                <td><span class="score-badge"><?= number_format($p->getNilaiUjian(), 2) ?></span></td>
                                                <td><span style="font-weight:600;"><?= htmlspecialchars($p->getPilihanProdi()) ?></span></td>
                                                <td><span class="badge badge-level"><i class="fa-solid fa-location-dot" style="margin-right:4px;"></i><?= htmlspecialchars($p->getLokasiKampus()) ?></span></td>
                                                <td>Rp <?= number_format($p->getBiayaPendaftaranDasar(), 0, ',', '.') ?></td>
                                                <td class="price-final">Rp <?= number_format($p->hitungTotalBiaya(), 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="empty-state">
                                                <i class="fa-solid fa-folder-open"></i>
                                                <p>Tidak ada data pendaftaran reguler ditemukan.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php elseif ($page == 'prestasi'): ?>
                    <!-- PRESTASI PATHWAY VIEW -->
                    <div class="page-header">
                        <h1 class="page-title">Pendaftaran Jalur Prestasi</h1>
                        <p class="page-subtitle">Menampilkan calon mahasiswa yang memiliki prestasi akademik maupun non-akademik.</p>
                    </div>
                    
                    <div class="data-card">
                        <div class="card-title-bar">
                            <span class="card-title-text"><i class="fa-solid fa-award"></i> Calon Mahasiswa Jalur Prestasi</span>
                        </div>
                        <div class="table-responsive">
                            <table class="custom-table" id="pendaftaranTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Asal Sekolah</th>
                                        <th>Nilai Ujian</th>
                                        <th>Jenis Prestasi</th>
                                        <th>Tingkat Prestasi</th>
                                        <th>Biaya Dasar</th>
                                        <th>Total Biaya (Potongan Rp50.000)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($daftarPrestasi) > 0): ?>
                                        <?php $no = 1; foreach ($daftarPrestasi as $p): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td style="font-weight:600; color:var(--accent-indigo);">#<?= sprintf("%03d", $p->getIdPendaftaran()) ?></td>
                                                <td class="search-name" style="font-weight:600;"><?= htmlspecialchars($p->getNamaCalon()) ?></td>
                                                <td class="search-school"><?= htmlspecialchars($p->getAsalSekolah()) ?></td>
                                                <td><span class="score-badge"><?= number_format($p->getNilaiUjian(), 2) ?></span></td>
                                                <td><span style="font-weight:600;"><?= htmlspecialchars($p->getJenisPrestasi()) ?></span></td>
                                                <td><span class="badge badge-level"><?= htmlspecialchars($p->getTingkatPrestasi()) ?></span></td>
                                                <td class="price-base">Rp <?= number_format($p->getBiayaPendaftaranDasar(), 0, ',', '.') ?></td>
                                                <td class="price-final text-green">Rp <?= number_format($p->hitungTotalBiaya(), 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="empty-state">
                                                <i class="fa-solid fa-folder-open"></i>
                                                <p>Tidak ada data pendaftaran prestasi ditemukan.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php elseif ($page == 'kedinasan'): ?>
                    <!-- KEDINASAN PATHWAY VIEW -->
                    <div class="page-header">
                        <h1 class="page-title">Pendaftaran Jalur Kedinasan</h1>
                        <p class="page-subtitle">Menampilkan calon mahasiswa yang bermitra khusus dengan instansi kedinasan.</p>
                    </div>
                    
                    <div class="data-card">
                        <div class="card-title-bar">
                            <span class="card-title-text"><i class="fa-solid fa-building-shield"></i> Calon Mahasiswa Jalur Kedinasan</span>
                        </div>
                        <div class="table-responsive">
                            <table class="custom-table" id="pendaftaranTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Asal Sekolah</th>
                                        <th>Nilai Ujian</th>
                                        <th>SK Ikatan Dinas</th>
                                        <th>Instansi Sponsor</th>
                                        <th>Biaya Dasar</th>
                                        <th>Total Biaya (Surcharge +25%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($daftarKedinasan) > 0): ?>
                                        <?php $no = 1; foreach ($daftarKedinasan as $p): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td style="font-weight:600; color:var(--accent-indigo);">#<?= sprintf("%03d", $p->getIdPendaftaran()) ?></td>
                                                <td class="search-name" style="font-weight:600;"><?= htmlspecialchars($p->getNamaCalon()) ?></td>
                                                <td class="search-school"><?= htmlspecialchars($p->getAsalSekolah()) ?></td>
                                                <td><span class="score-badge"><?= number_format($p->getNilaiUjian(), 2) ?></span></td>
                                                <td><code style="background-color:rgba(255,255,255,0.05); padding: 4px 8px; border-radius:4px; font-size:12px; border: 1px solid var(--border-color);"><?= htmlspecialchars($p->getSkIkatanDinas()) ?></code></td>
                                                <td><span style="font-weight:600;"><?= htmlspecialchars($p->getInstansiSponsor()) ?></span></td>
                                                <td class="price-base">Rp <?= number_format($p->getBiayaPendaftaranDasar(), 0, ',', '.') ?></td>
                                                <td class="price-final text-orange">Rp <?= number_format($p->hitungTotalBiaya(), 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="empty-state">
                                                <i class="fa-solid fa-folder-open"></i>
                                                <p>Tidak ada data pendaftaran kedinasan ditemukan.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Scripting for live table search and responsive toggles -->
    <script>
        // Fungsi pencarian dinamis pada tabel
        function searchTable() {
            const input = document.getElementById("tableSearch");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("pendaftaranTable");
            if (!table) return;
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                const nameCol = tr[i].getElementsByClassName("search-name")[0];
                const schoolCol = tr[i].getElementsByClassName("search-school")[0];
                if (nameCol || schoolCol) {
                    const txtValueName = nameCol ? nameCol.textContent || nameCol.innerText : "";
                    const txtValueSchool = schoolCol ? schoolCol.textContent || schoolCol.innerText : "";
                    if (txtValueName.toLowerCase().indexOf(filter) > -1 || txtValueSchool.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // Fungsi responsive sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
