<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$kategori_aktif = $_GET['kategori'] ?? 'Seluruh Tubuh';

// Data statis untuk banner sebelah kiri (gambar dan deskripsi pengantar)
$info_kategori = [
    'Seluruh Tubuh' => [
        'gambar' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&q=80',
        'deskripsi' => 'Latih seluruh otot tubuhmu untuk kebugaran maksimal dan pembakaran kalori menyeluruh secara efektif.'
    ],
    'Otot Perut' => [
        'gambar' => 'https://images.unsplash.com/photo-1507398941214-572c25f4b1dc?w=800&q=80',
        'deskripsi' => 'Fokus pada pembentukan otot inti (core) untuk perut yang lebih kuat, kencang, dan postur lebih baik.'
    ],
    'Lengan' => [
        'gambar' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=800&q=80',
        'deskripsi' => 'Kencangkan dan bentuk otot lengan agar lebih proporsional, kuat, dan bebas menggelambir.'
    ],
    'Pinggul Kaki' => [
        'gambar' => 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=800&q=80',
        'deskripsi' => 'Latih kekuatan dan bentuk pinggul serta kaki untuk pondasi tubuh yang lebih stabil dan ideal.'
    ]
];

$info = $info_kategori[$kategori_aktif] ?? $info_kategori['Seluruh Tubuh'];

// KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "genheals_db";
$conn = new mysqli($host, $user, $pass, $db);

// Ambil data modul yang kategorinya sesuai pilihan (misal: "Otot Perut")
$stmt = $conn->prepare("SELECT * FROM modul_latihan WHERE kategori = ? ORDER BY id DESC");
$stmt->bind_param("s", $kategori_aktif);
$stmt->execute();
$res_modul = $stmt->get_result();

$title = "Fokus: " . $kategori_aktif;
include 'includes/header.php';
?>

<style>
    /* Styling Layout Split Screen */
    .split-container {
        background-color: white; border-radius: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        overflow: hidden; margin-top: 30px; min-height: 600px; display: flex; flex-wrap: wrap;
    }
    
    .left-panel {
        background-size: cover; background-position: center; position: relative; padding: 40px; color: white;
        display: flex; flex-direction: column; justify-content: space-between;
    }
    .left-panel::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.2) 100%); z-index: 1;
    }
    .left-content { position: relative; z-index: 2; }
    
    .btn-back { color: white; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: 0.3s; }
    .btn-back:hover { color: var(--color-surface); margin-left: -5px; }

    /* Panel Kanan (Daftar Kategori/Modul) */
    .right-panel { padding: 50px 40px; background-color: white; }
    
    .module-card {
        background-color: white; border-radius: 15px; padding: 20px; margin-bottom: 15px;
        border: 1px solid #eee; display: flex; align-items: center; transition: 0.3s; text-decoration: none; color: var(--color-text);
    }
    .module-card:hover {
        border-color: var(--color-primary); box-shadow: 0 10px 20px rgba(197, 31, 93, 0.1);
        transform: translateX(5px); color: var(--color-text);
    }
    .module-icon {
        width: 50px; height: 50px; border-radius: 12px; background-color: var(--color-bg);
        color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-right: 20px;
    }
</style>

<main class="mb-5 fade-in-up">
    <div class="container">
        
        <div class="split-container row g-0">
            <div class="col-lg-6 left-panel" style="background-image: url('<?= $info['gambar']; ?>');">
                <div class="left-content">
                    <a href="dashboard.php" class="btn-back"><i class="bi bi-arrow-left me-2"></i> KEMBALI</a>
                </div>
                <div class="left-content mt-5">
                    <h1 class="fw-bold display-3 mb-3"><?= htmlspecialchars($kategori_aktif); ?></h1>
                    <p class="fs-5" style="opacity: 0.9; max-width: 90%;"><?= $info['deskripsi']; ?></p>
                </div>
            </div>

            <div class="col-lg-6 right-panel">
                <h2 class="fw-bold mb-1">Pilih Kategori</h2>
                <p class="text-muted mb-4">Pilih menu latihan yang sesuai dengan target Anda hari ini.</p>

                <div class="pe-2">
                    <?php if ($res_modul->num_rows > 0): ?>
                        <?php while($row = $res_modul->fetch_assoc()): ?>
                            
                            <a href="detail_latihan.php?id=<?= $row['id']; ?>" class="module-card">
                                <div class="module-icon">
                                    <i class="bi bi-shield-fill-check"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1" style="text-transform: uppercase;"><?= htmlspecialchars($row['judul']); ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($row['estimasi_waktu']); ?> • Pemula</small>
                                </div>
                                <i class="bi bi-chevron-right text-muted fs-5"></i>
                            </a>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-5 rounded-4" style="background-color: var(--color-bg); border: 1px dashed var(--color-surface);">
                            <i class="bi bi-journal-x fs-1 text-muted mb-2"></i>
                            <h6 class="fw-bold text-muted">Belum Ada Program</h6>
                            <p class="small text-muted mb-0">Operator belum menambahkan latihan untuk kategori ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</main>

<?php 
$stmt->close();
$conn->close();
include 'includes/footer.php'; 
?>