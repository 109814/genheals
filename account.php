<?php
session_start();

// Proteksi halaman: Wajib login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$title = "My Account";
include 'includes/header.php';
require_once("koneksi.php");


// ==========================================
// KONEKSI DATABASE
// ==========================================


$user_id = $_SESSION['user_id'];
$pesan_notif = "";

// ==========================================
// PROSES UPDATE PROFIL (Jika form edit disubmit)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $new_nama = trim($_POST['nama_pengguna']);
    $new_wa = trim($_POST['nomor_whatsapp']);
    $new_email = trim($_POST['email']);
    $new_tgl_lahir = $_POST['tanggal_lahir'];

    // Cek apakah username atau whatsapp yang baru sudah dipakai orang lain (kecuali milik dia sendiri)
    $stmt_cek = $conn->prepare("SELECT id FROM users WHERE (nama_pengguna = ? OR nomor_whatsapp = ?) AND id != ?");
    $stmt_cek->bind_param("ssi", $new_nama, $new_wa, $user_id);
    $stmt_cek->execute();
    $res_cek = $stmt_cek->get_result();

    if ($res_cek->num_rows > 0) {
        $pesan_notif = "<div class='alert alert-warning alert-dismissible fade show shadow-sm' role='alert'>
                            <strong>Gagal!</strong> Nama Pengguna atau Nomor WhatsApp sudah digunakan oleh akun lain.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
    } else {
        // Jika aman, lakukan Update
        $stmt_update = $conn->prepare("UPDATE users SET nama_pengguna = ?, nomor_whatsapp = ?, email = ?, tanggal_lahir = ? WHERE id = ?");
        $stmt_update->bind_param("ssssi", $new_nama, $new_wa, $new_email, $new_tgl_lahir, $user_id);

        if ($stmt_update->execute()) {
            $_SESSION['username'] = $new_nama; // Update session
            $pesan_notif = "<div class='alert alert-success alert-dismissible fade show shadow-sm' role='alert'>
                                <strong>Berhasil!</strong> Profil kamu telah diperbarui.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
        } else {
            $pesan_notif = "<div class='alert alert-danger alert-dismissible fade show shadow-sm' role='alert'>
                                <strong>Error!</strong> Terjadi kesalahan sistem saat memperbarui profil.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
        }
        $stmt_update->close();
    }
    $stmt_cek->close();
}

// ==========================================
// AMBIL DATA PROFIL & LANGGANAN
// ==========================================

// 1. Ambil Data Profil User
$stmt_user = $conn->prepare("SELECT nama_pengguna, nomor_whatsapp, email, tanggal_lahir, created_at, role FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$data_user = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

// 2. Cek & Update Masa Aktif Langganan (Auto-Expired Logic)
$tgl_sekarang = date('Y-m-d H:i:s');
$stmt_expired = $conn->prepare("UPDATE langganan SET status = 'nonaktif' WHERE user_id = ? AND status = 'aktif' AND berakhir_pada < ?");
$stmt_expired->bind_param("is", $user_id, $tgl_sekarang);
$stmt_expired->execute();
$stmt_expired->close();

// 3. Ambil Status Langganan Terakhir
$stmt_subs = $conn->prepare("SELECT paket, mulai_berlaku, berakhir_pada, status FROM langganan WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt_subs->bind_param("i", $user_id);
$stmt_subs->execute();
$res_subs = $stmt_subs->get_result();

$subs_aktif = false;
$data_subs = null;

if ($res_subs->num_rows > 0) {
    $data_subs = $res_subs->fetch_assoc();
    if ($data_subs['status'] === 'aktif') {
        $subs_aktif = true;
    }
}
$stmt_subs->close();
$conn->close();

// Format Tanggal untuk Tampilan
function formatTanggal($datetime)
{
    if (!$datetime)
        return 'Belum diatur';
    return date('d M Y, H:i', strtotime($datetime)) . ' WIB';
}
function formatTanggalLahir($date)
{
    if (!$date)
        return 'Belum diatur';
    return date('d/m/Y', strtotime($date));
}
?>

<style>
    /* Styling My Account */
    .account-header {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white;
        padding: 50px 0 80px 0;
        border-radius: 0 0 30px 30px;
        text-align: center;
        box-shadow: 0 10px 20px rgba(197, 31, 93, 0.15);
    }

    .profile-card {
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 40px;
        margin-top: -50px;
        margin-bottom: 30px;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        background-color: var(--color-surface);
        color: var(--color-primary);
        font-size: 3.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(197, 31, 93, 0.2);
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 2px;
        font-weight: 500;
    }

    .info-value {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--color-text);
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
    }

    /* Box Status Langganan */
    .subs-box {
        border-radius: 15px;
        padding: 25px 20px;
        border: 1px solid #eee;
        height: 100%;
    }

    .subs-box.aktif {
        background-color: #f8fff9;
        border-color: #198754;
    }

    .subs-box.pending {
        background-color: #fffdf5;
        border-color: #ffc107;
    }

    .subs-box.nonaktif {
        background-color: var(--color-bg);
        border-color: var(--color-surface);
    }

    .btn-logout {
        background-color: transparent;
        color: #dc3545;
        border: 2px solid #dc3545;
        border-radius: 25px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-logout:hover {
        background-color: #dc3545;
        color: white;
        transform: translateY(-2px);
    }

    /* Modal Styling Tambahan */
    .custom-input {
        background-color: var(--color-bg);
        border: 2px solid transparent;
        padding: 12px 15px;
        border-radius: 10px;
        width: 100%;
        color: var(--color-text);
    }

    .custom-input:focus {
        outline: none;
        border-color: var(--color-surface);
        background-color: #fff;
    }
</style>

<!-- HEADER ACCOUNT -->
<div class="account-header fade-in-up">
    <div class="container">
        <h2 class="fw-bold mb-1">Profil Saya</h2>
        <p class="mb-0" style="opacity: 0.9;">Kelola informasi profil dan status langgananmu.</p>
    </div>
</div>

<main class="mb-5 fade-in-up delay-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <?= $pesan_notif; ?>

                <div class="profile-card">
                    <div class="text-center mb-5">
                        <div class="profile-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: var(--color-primary);">
                            <?= htmlspecialchars($data_user['nama_pengguna']); ?></h4>

                        <?php if ($subs_aktif): ?>
                            <span class="badge bg-success rounded-pill px-3 py-2 fs-6"><i
                                    class="bi bi-star-fill text-warning me-1"></i> Premium Member</span>
                        <?php else: ?>
                            <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">Member Biasa</span>
                        <?php endif; ?>

                        <?php if ($data_user['role'] === 'admin'): ?>
                            <a href="admin/dashboard.php"
                                class="badge bg-dark rounded-pill px-3 py-2 fs-6 text-decoration-none ms-1"><i
                                    class="bi bi-shield-lock-fill me-1"></i> Panel Admin</a>
                        <?php endif; ?>
                    </div>

                    <div class="row g-5">
                        <!-- Kolom Info Profil -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-3"
                                style="border-bottom: 2px solid var(--color-surface); padding-bottom: 10px;">
                                <h5 class="fw-bold mb-0" style="color: var(--color-primary);">Informasi Pengguna</h5>
                                <!-- Tombol pemicu Modal Edit -->
                                <button class="btn btn-sm btn-light text-primary fw-bold px-3 rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#modalEditProfil">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                            </div>

                            <div class="info-label">Nama Pengguna</div>
                            <div class="info-value"><?= htmlspecialchars($data_user['nama_pengguna']); ?></div>

                            <div class="info-label">Nomor WhatsApp</div>
                            <div class="info-value"><?= htmlspecialchars($data_user['nomor_whatsapp']); ?></div>

                            <div class="info-label">Alamat Email</div>
                            <div class="info-value">
                                <?= $data_user['email'] ? htmlspecialchars($data_user['email']) : '<em class="text-muted fw-normal">Belum diatur</em>'; ?>
                            </div>

                            <div class="info-label">Tanggal Lahir</div>
                            <div class="info-value" style="border-bottom: none;">
                                <?= formatTanggalLahir($data_user['tanggal_lahir']); ?></div>
                        </div>

                        <!-- Kolom Status Langganan -->
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3"
                                style="color: var(--color-primary); border-bottom: 2px solid var(--color-surface); padding-bottom: 10px;">
                                Status Langganan</h5>

                            <?php if ($data_subs): ?>
                                <?php if ($data_subs['status'] === 'aktif'): ?>
                                    <div class="subs-box aktif">
                                        <h5 class="fw-bold text-success mb-3"><?= htmlspecialchars($data_subs['paket']); ?></h5>
                                        <div class="row mb-2">
                                            <div class="col-4 text-muted">Mulai:</div>
                                            <div class="col-8 fw-medium"><?= formatTanggal($data_subs['mulai_berlaku']); ?>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-4 text-muted">Berakhir:</div>
                                            <div class="col-8 fw-medium text-danger">
                                                <?= formatTanggal($data_subs['berakhir_pada']); ?></div>
                                        </div>
                                        <span class="badge bg-success w-100 py-2 fs-6">Status: AKTIF</span>
                                    </div>
                                <?php elseif ($data_subs['status'] === 'pending'): ?>
                                    <div class="subs-box pending text-center">
                                        <i class="bi bi-hourglass-split text-warning fs-1 mb-2"></i>
                                        <h5 class="fw-bold text-warning mb-2">Menunggu Konfirmasi</h5>
                                        <p class="small text-muted mb-4">Pesanan
                                            <strong><?= htmlspecialchars($data_subs['paket']); ?></strong> Anda sedang menunggu
                                            verifikasi admin.</p>
                                        <a href="https://api.whatsapp.com/send?phone=6285722408690" target="_blank"
                                            class="btn btn-outline-warning w-100 fw-bold rounded-pill">Hubungi Admin</a>
                                    </div>
                                <?php else: ?>
                                    <div class="subs-box nonaktif text-center">
                                        <i class="bi bi-exclamation-circle text-muted fs-1 mb-2"></i>
                                        <h5 class="fw-bold text-muted mb-2">Langganan Berakhir</h5>
                                        <p class="small text-muted mb-4">Masa aktif paket Anda telah habis.</p>
                                        <a href="subscription.php" class="btn btn-primary-custom w-100">Perpanjang Paket</a>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="subs-box nonaktif text-center">
                                    <i class="bi bi-star text-muted fs-1 mb-2"></i>
                                    <h5 class="fw-bold text-muted mb-2">Belum Ada Paket</h5>
                                    <p class="small text-muted mb-4">Tingkatkan pengalamanmu dengan fitur premium GenHeals.
                                    </p>
                                    <a href="subscription.php" class="btn btn-primary-custom w-100">Lihat Pilihan Paket</a>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="text-center mt-5 pt-3 border-top">
                        <a href="logout.php" class="btn btn-logout px-5 py-2"><i class="bi bi-box-arrow-right me-2"></i>
                            Keluar Akun</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<!-- MODAL EDIT PROFIL -->
<div class="modal fade" id="modalEditProfil" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background-color: var(--color-primary); color: white;">
                <h5 class="modal-title fw-bold">Edit Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="account.php" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="update_profile">

                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Nama Pengguna</label>
                        <input type="text" name="nama_pengguna" class="custom-input"
                            value="<?= htmlspecialchars($data_user['nama_pengguna']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Nomor WhatsApp</label>
                        <input type="number" name="nomor_whatsapp" class="custom-input"
                            value="<?= htmlspecialchars($data_user['nomor_whatsapp']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Alamat Email</label>
                        <input type="email" name="email" class="custom-input"
                            value="<?= htmlspecialchars($data_user['email'] ?? ''); ?>" placeholder="contoh@email.com">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted small">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="custom-input"
                            value="<?= htmlspecialchars($data_user['tanggal_lahir'] ?? ''); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fs-5">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>