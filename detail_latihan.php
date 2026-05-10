<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$modul_id = (int)$_GET['id'];

// KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "genheals_db";
$conn = new mysqli($host, $user, $pass, $db);

// Ambil Detail Modul
$stmt = $conn->prepare("SELECT * FROM modul_latihan WHERE id = ?");
$stmt->bind_param("i", $modul_id);
$stmt->execute();
$res_modul = $stmt->get_result();

if ($res_modul->num_rows === 0) {
    echo "Modul tidak ditemukan.";
    exit();
}
$modul = $res_modul->fetch_assoc();
$stmt->close();

// Ambil Daftar Gerakan
$stmt_gerakan = $conn->prepare("SELECT * FROM gerakan_latihan WHERE modul_id = ?");
$stmt_gerakan->bind_param("i", $modul_id);
$stmt_gerakan->execute();
$res_gerakan = $stmt_gerakan->get_result();

$title = $modul['judul'];
include 'includes/header.php';
?>

<style>
    /* Styling Split Screen Layout */
    .split-container {
        background-color: white;
        border-radius: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-top: 30px;
        min-height: 600px;
        display: flex;
        flex-wrap: wrap;
    }
    
    /* Panel Kiri (Gambar Cover) */
    .left-panel {
        background-size: cover;
        background-position: center;
        position: relative;
        padding: 40px;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    /* Overlay Gelap agar teks terbaca */
    .left-panel::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.1) 100%);
        z-index: 1;
    }
    .left-content { position: relative; z-index: 2; }
    .btn-back { color: white; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: 0.3s; }
    .btn-back:hover { color: var(--color-surface); margin-left: -5px; }

    /* Panel Kanan (Daftar Latihan) */
    .right-panel {
        padding: 50px 40px;
        background-color: white;
    }
    .exercise-list {
        max-height: 450px;
        overflow-y: auto;
        padding-right: 10px;
    }
    /* Kustomisasi Scrollbar */
    .exercise-list::-webkit-scrollbar { width: 6px; }
    .exercise-list::-webkit-scrollbar-thumb { background-color: var(--color-surface); border-radius: 10px; }

    .exercise-item {
        background-color: #f8f9fa;
        border-radius: 20px;
        padding: 15px 20px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        transition: 0.3s;
        border: 1px solid transparent;
    }
    .exercise-item:hover {
        background-color: white;
        border-color: var(--color-surface);
        box-shadow: 0 5px 15px rgba(197, 31, 93, 0.08);
        transform: translateY(-2px);
    }
    .ex-thumb { width: 60px; height: 60px; border-radius: 12px; object-fit: cover; margin-right: 15px; }
    .ex-play-btn {
        background-color: #212529; color: white; width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: 0.3s; flex-shrink: 0;
    }
    .exercise-item:hover .ex-play-btn { background-color: var(--color-primary); transform: scale(1.1); }

    /* Modal Video Khusus */
    .video-modal-content {
        border-radius: 20px; border: none; background-color: white;
    }
    .btn-close-floating {
        position: absolute; top: -15px; right: -15px;
        background-color: var(--color-primary); color: white;
        border: 3px solid white; border-radius: 50%;
        width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 10; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: 0.3s;
    }
    .btn-close-floating:hover { background-color: #dc3545; transform: rotate(90deg); }
    .iframe-container {
        position: relative; width: 100%; padding-bottom: 56.25%; /* 16:9 Aspect Ratio */ border-radius: 15px; overflow: hidden;
    }
    .iframe-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }
</style>

<main class="mb-5 fade-in-up">
    <div class="container">
        
        <div class="split-container row g-0">
            <!-- Sisi Kiri: Gambar Cover & Judul -->
            <div class="col-lg-6 left-panel" style="background-image: url('<?= htmlspecialchars($modul['gambar_cover']); ?>');">
                <div class="left-content">
                    <a href="dashboard.php" class="btn-back"><i class="bi bi-arrow-left me-2"></i> KEMBALI</a>
                </div>
                <div class="left-content mt-5">
                    <h1 class="fw-bold display-4 mb-3"><?= htmlspecialchars($modul['judul']); ?></h1>
                    <p class="fs-5" style="opacity: 0.9; max-width: 90%;"><?= htmlspecialchars($modul['deskripsi']); ?></p>
                </div>
            </div>

            <!-- Sisi Kanan: Daftar Latihan -->
            <div class="col-lg-6 right-panel">
                <h2 class="fw-bold mb-1">Daftar Latihan</h2>
                <p class="text-danger fw-bold mb-4"><?= htmlspecialchars($modul['estimasi_waktu']); ?> • <?= $res_gerakan->num_rows; ?> gerakan intensif</p>

                <div class="exercise-list">
                    <?php if ($res_gerakan->num_rows > 0): ?>
                        <?php while($gerakan = $res_gerakan->fetch_assoc()): ?>
                            
                            <div class="exercise-item">
                                <!-- Pakai gambar cover modul sebagai thumbnail gerakan -->
                                <img src="<?= htmlspecialchars($modul['gambar_cover']); ?>" class="ex-thumb" alt="Thumbnail">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($gerakan['nama_gerakan']); ?></h6>
                                    <small class="text-muted"><?= htmlspecialchars($gerakan['durasi']); ?> • <?= htmlspecialchars($gerakan['fokus']); ?></small>
                                </div>
                                <button class="ex-play-btn shadow-sm" onclick="playVideo('<?= htmlspecialchars($gerakan['nama_gerakan']); ?>', '<?= htmlspecialchars($gerakan['link_youtube']); ?>')">
                                    <i class="bi bi-play-fill fs-4"></i>
                                </button>
                            </div>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">Belum ada gerakan ditambahkan.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- MODAL PEMUTAR VIDEO -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content video-modal-content p-4 position-relative">
            
            <!-- Tombol Close Melayang Merah/Magenta -->
            <div class="btn-close-floating" data-bs-dismiss="modal">
                <i class="bi bi-x-lg"></i>
            </div>

            <div class="text-center mb-3">
                <h4 class="fw-bold mb-0" id="videoModalTitle">Judul Gerakan</h4>
            </div>
            
            <div class="iframe-container shadow-sm border">
                <iframe id="videoPlayer" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            
            <div class="text-center mt-3">
                <p class="text-muted small mb-0">Ikuti gerakan dengan saksama untuk hasil maksimal.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi memutar video ke dalam Modal
    function playVideo(judul, link) {
        document.getElementById('videoModalTitle').innerText = judul;
        // Tambahkan parameter autoplay ke link youtube
        let autoPlayLink = link.includes('?') ? link + '&autoplay=1' : link + '?autoplay=1';
        document.getElementById('videoPlayer').src = autoPlayLink;
        
        var myModal = new bootstrap.Modal(document.getElementById('videoModal'));
        myModal.show();
    }

    // Hentikan video saat modal ditutup
    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('videoPlayer').src = '';
    });
</script>

<?php 
$stmt_gerakan->close();
$conn->close();
include 'includes/footer.php'; 
?>