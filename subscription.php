<?php
session_start();

// Jika ada request AJAX untuk mencatat transaksi pending (sebelum dilempar ke WA)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action']) && $_POST['ajax_action'] === 'set_pending') {
    header('Content-Type: application/json');
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "genheals_db";
    $conn = new mysqli($host, $user, $pass, $db);

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $paket = $_POST['paket'];
        $stmt = $conn->prepare("INSERT INTO langganan (user_id, paket, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("is", $user_id, $paket);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        $stmt->close();
    }
    $conn->close();
    exit();
}

// Proteksi halaman
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$title = "Pilih Paket Langganan Premium";
include 'includes/header.php';

// Koneksi Database Utama
$host = "localhost";
$user = "root";
$pass = "";
$db   = "genheals_db";
$conn = new mysqli($host, $user, $pass, $db);

$user_id = $_SESSION['user_id'];
$pesan_notif = "";

// Proses Klaim Trial (Kirim via Form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'claim_trial') {
    // Cek apakah user sudah pernah ambil trial
    $stmt_cek = $conn->prepare("SELECT id FROM langganan WHERE user_id = ? AND paket = 'Trial Gratis 7 Hari'");
    $stmt_cek->bind_param("i", $user_id);
    $stmt_cek->execute();
    $res_cek = $stmt_cek->get_result();

    if ($res_cek->num_rows > 0) {
        $pesan_notif = "<div class='alert alert-warning alert-dismissible fade show shadow-sm' role='alert'>
                            Kamu sudah pernah menikmati masa <strong>Trial Gratis 7 Hari</strong>. Silakan pilih paket langganan premium lainnya.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
    } else {
        // Insert Trial Aktif
        $tgl_mulai = date('Y-m-d H:i:s');
        $tgl_akhir = date('Y-m-d H:i:s', strtotime('+7 days'));
        $paket = 'Trial Gratis 7 Hari';
        $status = 'aktif';

        $stmt_insert = $conn->prepare("INSERT INTO langganan (user_id, paket, mulai_berlaku, berakhir_pada, status) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("issss", $user_id, $paket, $tgl_mulai, $tgl_akhir, $status);
        if ($stmt_insert->execute()) {
            $pesan_notif = "<div class='alert alert-success alert-dismissible fade show shadow-sm' role='alert'>
                                <strong>Hore!</strong> Trial Gratis 7 Hari kamu sudah aktif.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
        }
        $stmt_insert->close();
    }
    $stmt_cek->close();
}
?>

<style>
    /* Styling Dashboard Langganan */
    .subs-header {
        text-align: center;
        padding: 50px 0 30px 0;
    }
    .trial-banner {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(197, 31, 93, 0.2);
        margin-bottom: 40px;
        transition: transform 0.3s ease;
    }
    .trial-banner:hover { transform: translateY(-5px); }
    
    .pricing-card {
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        padding: 40px 20px 30px 20px;
        text-align: center;
    }
    .pricing-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(197, 31, 93, 0.15);
        border-color: var(--color-surface);
    }
    .pricing-card.popular {
        border-color: var(--color-primary);
        background-color: #fff9fa;
    }
    .badge-popular {
        background-color: var(--color-primary);
        color: white;
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        padding: 6px 20px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }
    
    .price-amount {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--color-text);
        margin: 15px 0;
    }
    
    .btn-pricing {
        background-color: var(--color-text);
        color: white;
        border-radius: 25px;
        padding: 10px 30px;
        font-weight: 600;
        margin-top: auto;
        transition: 0.3s;
        border: none;
    }
    .btn-pricing:hover { background-color: var(--color-primary); color: white;}
    .btn-pricing.btn-popular {
        background-color: var(--color-primary);
    }
    .btn-pricing.btn-popular:hover { background-color: var(--color-secondary); }

    /* Modal Styling */
    .custom-modal .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }
    .custom-modal .modal-header {
        background-color: var(--color-primary);
        color: white;
        border-bottom: none;
    }
</style>

<main class="mb-5 fade-in-up">
    <div class="container">
        
        <div class="subs-header">
            <h2 class="fw-bold" style="color: var(--color-primary);">Pilih Paket Langganan Premium</h2>
            <p class="text-muted">Dapatkan akses penuh ke semua fitur dan konten eksklusif kami.</p>
        </div>

        <?= $pesan_notif; ?>

        <!-- BANNER TRIAL -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="trial-banner">
                    <span class="badge bg-white text-dark mb-3 px-3 py-2 rounded-pill">🎁 Penawaran Spesial</span>
                    <h3 class="fw-bold">Trial Gratis 7 Hari</h3>
                    <p class="mb-4">Coba semua fitur premium tanpa biaya. Langganan tidak akan otomatis diperpanjang.</p>
                    <form method="POST">
                        <input type="hidden" name="action" value="claim_trial">
                        <button type="submit" class="btn btn-light rounded-pill px-5 py-2 fw-bold" style="color: var(--color-primary);">Klaim Sekarang</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- PRICING CARDS -->
        <div class="row justify-content-center g-4 mt-2 fade-in-up delay-1">
            
            <!-- Paket 1 Bulan -->
            <div class="col-md-4 col-lg-3">
                <div class="pricing-card">
                    <h6 class="fw-bold text-muted text-uppercase">Bulanan</h6>
                    <div class="price-amount">Rp5.000</div>
                    <p class="text-muted small">per bulan</p>
                    <p class="text-muted mb-4 mt-2">Akses Semua fitur selama 30 hari.</p>
                    <button class="btn btn-pricing w-100" onclick="bukaModalBayar('Paket Bulanan 1 Bulan', 5000)">Pilih Paket</button>
                </div>
            </div>

            <!-- Paket 6 Bulan (Popular) -->
            <div class="col-md-4 col-lg-4">
                <div class="pricing-card popular" style="padding-top: 50px;">
                    <div class="badge-popular">TERPOPULER</div>
                    <h6 class="fw-bold" style="color: var(--color-primary); text-transform: uppercase;">Paket Hemat 6 Bulan</h6>
                    <div class="price-amount" style="color: var(--color-primary);">Rp10.000</div>
                    <p class="text-muted small">per 6 bulan</p>
                    <p class="text-muted mb-4 mt-2 fw-medium">Akses Semua fitur selama 183 hari. Jauh lebih hemat!</p>
                    <button class="btn btn-pricing btn-popular w-100 py-3" onclick="bukaModalBayar('Paket Hemat 6 Bulan', 10000)">Pilih Paket</button>
                </div>
            </div>

            <!-- Paket Tahunan -->
            <div class="col-md-4 col-lg-3">
                <div class="pricing-card">
                    <h6 class="fw-bold text-muted text-uppercase">Tahunan</h6>
                    <div class="price-amount">Rp15.000</div>
                    <p class="text-muted small">per 12 bulan</p>
                    <p class="text-muted mb-4 mt-2">Akses Semua fitur selama 365 hari.</p>
                    <button class="btn btn-pricing w-100" onclick="bukaModalBayar('Paket Tahunan 12 Bulan', 15000)">Pilih Paket</button>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- MODAL KONFIRMASI PEMBAYARAN -->
<div class="modal fade custom-modal" id="modalPembayaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Konfirmasi Langganan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="mb-2">Anda memilih <strong id="nama_paket_modal" style="color: var(--color-primary);">Paket</strong></p>
                
                <div class="bg-light rounded-3 p-3 text-start mb-4 border" style="font-size: 0.9rem;">
                    <span class="fw-bold d-block mb-2">Langkah Selanjutnya:</span>
                    <ol class="mb-0 ps-3">
                        <li>Scan QRIS di bawah ini untuk pembayaran.</li>
                        <li>Klik tombol "Hubungi Admin" di bawah ini.</li>
                        <li>Kirimkan bukti transfer di chat WhatsApp.</li>
                        <li>Admin akan segera mengaktifkan akun Anda.</li>
                    </ol>
                </div>

                <div class="mb-4">
                    <!-- Ganti src ini dengan gambar QRIS asli kamu -->
                    <div style="width: 200px; height: 200px; background-color: #eee; margin: 0 auto; display: flex; align-items: center; justify-content: center; border: 2px dashed #ccc; border-radius: 10px;">
                        <span class="text-muted">Gambar QRIS<br>Tempatkan Di Sini</span>
                    </div>
                </div>

                <button type="button" id="btnHubungiAdmin" class="btn btn-primary-custom w-100 py-2 fs-5">
                    <i class="bi bi-whatsapp me-2"></i> Hubungi Admin Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let paketPilihanSaatIni = "";
    let hargaPaketSaatIni = 0;

    function bukaModalBayar(namaPaket, harga) {
        paketPilihanSaatIni = namaPaket;
        hargaPaketSaatIni = harga;
        
        // Update teks di dalam modal
        document.getElementById('nama_paket_modal').innerText = `${namaPaket} (Rp${harga.toLocaleString('id-ID')})`;
        
        // Tampilkan Modal
        let myModal = new bootstrap.Modal(document.getElementById('modalPembayaran'));
        myModal.show();
    }

    document.getElementById('btnHubungiAdmin').addEventListener('click', function() {
        // 1. Catat ke database sebagai 'pending' via AJAX
        let formData = new FormData();
        formData.append('ajax_action', 'set_pending');
        formData.append('paket', paketPilihanSaatIni);

        fetch('subscription.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // 2. Arahkan ke WhatsApp
            if(data.status === 'success') {
                let nomorAdmin = "6285722408690"; // Nomor sesuai tabel users
                let namaUser = "<?= htmlspecialchars($_SESSION['username']); ?>";
                let pesan = `Halo Admin GenHeals, saya ${namaUser} ingin mengaktifkan *${paketPilihanSaatIni}*. Berikut saya lampirkan bukti transfernya.`;
                let linkWA = `https://api.whatsapp.com/send?phone=${nomorAdmin}&text=${encodeURIComponent(pesan)}`;
                
                // Buka tab WhatsApp
                window.open(linkWA, '_blank');
                
                // Tutup modal
                bootstrap.Modal.getInstance(document.getElementById('modalPembayaran')).hide();
                alert("Permintaan langganan dikirim! Silakan selesaikan pembayaran dan kirim buktinya di WhatsApp.");
            } else {
                alert("Terjadi kesalahan sistem, silakan coba lagi.");
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>