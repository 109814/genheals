<?php
session_start();
// Jika user sudah login, langsung arahkan ke account
if (isset($_SESSION['user_id'])) {
    header("Location: account.php");
    exit();
}
$title = "Login & Register";
include 'includes/header.php';
?>

<style>
    /* =========================================
       STYLING KHUSUS HALAMAN AUTH
       ========================================= */
    .auth-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 40px 20px;
    }
    .auth-container {
        background-color: #fff;
        border-radius: 20px;
        /* Shadow dihaluskan agar lebih elegan */
        box-shadow: 0 15px 35px rgba(197, 31, 93, 0.15), 0 5px 15px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
        width: 900px;
        max-width: 100%;
        min-height: 550px;
    }
    .form-container {
        position: absolute;
        top: 0;
        height: 100%;
        /* Menggunakan cubic-bezier untuk animasi super smooth */
        transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: white;
    }
    .sign-in-container { left: 0; width: 50%; z-index: 2; }
    .sign-up-container { left: 0; width: 50%; opacity: 0; z-index: 1; }
    
    /* Overlay Panel (Bagian Berwarna) */
    .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 100;
    }
    .overlay {
        background: linear-gradient(to right, var(--color-primary), var(--color-secondary));
        color: #ffffff;
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        transform: translateX(0);
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .overlay-panel {
        position: absolute;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 0 40px;
        text-align: center;
        top: 0;
        height: 100%;
        width: 50%;
        transform: translateX(0);
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .overlay-left { transform: translateX(-20%); }
    .overlay-right { right: 0; transform: translateX(0); }

    /* Animasi Pergeseran */
    .auth-container.right-panel-active .sign-in-container { transform: translateX(100%); opacity: 0; }
    .auth-container.right-panel-active .sign-up-container { transform: translateX(100%); opacity: 1; z-index: 5; }
    .auth-container.right-panel-active .overlay-container { transform: translateX(-100%); }
    .auth-container.right-panel-active .overlay { transform: translateX(50%); }
    .auth-container.right-panel-active .overlay-left { transform: translateX(0); }
    .auth-container.right-panel-active .overlay-right { transform: translateX(20%); }

    /* Styling Input & Tombol */
    .custom-input { 
        background-color: var(--color-bg); 
        border: 2px solid transparent; 
        padding: 12px 15px; 
        border-radius: 10px; 
        width: 100%; 
        color: var(--color-text);
        font-family: 'Poppins', sans-serif;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    .custom-input:focus {
        outline: none;
        border-color: var(--color-surface);
        background-color: #fff;
        box-shadow: 0 0 10px rgba(255, 75, 130, 0.15);
    }
    .btn-auth { 
        background-color: var(--color-primary);
        color: white;
        border-radius: 25px; 
        font-weight: 600; 
        padding: 12px 45px; 
        letter-spacing: 1px; 
        transition: transform 0.2s ease, background-color 0.3s ease, box-shadow 0.3s ease;
        border: none;
        width: 100%;
    }
    .btn-auth:hover { 
        background-color: var(--color-secondary); 
        color: white;
        box-shadow: 0 8px 20px rgba(255, 75, 130, 0.3);
        transform: translateY(-2px);
    }
    .btn-auth:active { transform: translateY(0) scale(0.98); }
    .btn-outline-custom { background-color: transparent; border: 2px solid #fff; color: #fff; width: auto; }
    .btn-outline-custom:hover { background-color: white; color: var(--color-primary); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

    /* Tampilan HP (Responsive) */
    .mobile-toggle { display: none; }
    @media (max-width: 768px) {
        .auth-container { min-height: auto; padding: 20px; box-shadow: none; background: transparent; }
        .form-container { position: relative; width: 100%; height: auto; padding: 40px 25px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); background: white;}
        .sign-up-container { display: none; }
        .auth-container.right-panel-active .sign-in-container { display: none; }
        .auth-container.right-panel-active .sign-up-container { display: flex; opacity: 1; transform: none; animation: fadeIn 0.5s ease forwards;}
        .overlay-container { display: none; } 
        .mobile-toggle { display: block; text-align: center; margin-top: 25px; font-size: 0.95rem; color: var(--color-text); }
        .mobile-toggle a { color: var(--color-primary); font-weight: bold; text-decoration: none; cursor: pointer; transition: color 0.3s; }
        .mobile-toggle a:hover { color: var(--color-secondary); }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    }
</style>

<main class="auth-wrapper fade-in-up">
    <div class="container d-flex justify-content-center flex-column align-items-center">
        
        <!-- Notifikasi Error/Success -->
        <?php if(isset($_SESSION['pesan'])): ?>
            <div class="alert alert-<?= $_SESSION['tipe_pesan']; ?> alert-dismissible fade show w-100 shadow-sm mb-4" style="max-width: 900px; border-radius: 12px;" role="alert">
                <?= $_SESSION['pesan']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['pesan']); unset($_SESSION['tipe_pesan']); ?>
        <?php endif; ?>

        <div class="auth-container" id="authContainer">
            
            <!-- FORM REGISTER -->
            <div class="form-container sign-up-container">
                <form action="proses_auth.php" method="POST" id="registerForm">
                    <h2 class="fw-bold mb-4" style="color: var(--color-primary);">Buat Akun</h2>
                    <input type="hidden" name="action" value="register">
                    <input type="text" name="username" class="custom-input" placeholder="Nama Pengguna" required>
                    <input type="number" name="whatsapp" class="custom-input" placeholder="Nomor WhatsApp" required>
                    <input type="password" name="password" id="regPassword" class="custom-input" placeholder="Kata Sandi" required>
                    <input type="password" name="konfirmasi_password" id="regConfirmPassword" class="custom-input mb-4" placeholder="Konfirmasi Kata Sandi" required>
                    <button type="submit" class="btn-auth">Daftar Sekarang</button>
                    
                    <div class="mobile-toggle">
                        Sudah punya akun? <a id="mobileSignInBtn">Masuk di sini</a>
                    </div>
                </form>
            </div>

            <!-- FORM LOGIN -->
            <div class="form-container sign-in-container">
                <form action="proses_auth.php" method="POST">
                    <h2 class="fw-bold mb-4" style="color: var(--color-primary);">Selamat Datang</h2>
                    <input type="hidden" name="action" value="login">
                    <input type="text" name="username" class="custom-input" placeholder="Nama Pengguna / No WhatsApp" required>
                    <input type="password" name="password" class="custom-input mb-4" placeholder="Kata Sandi" required>
                    <button type="submit" class="btn-auth">Masuk</button>
                    
                    <div class="mobile-toggle">
                        Belum punya akun? <a id="mobileSignUpBtn">Daftar di sini</a>
                    </div>
                </form>
            </div>

            <!-- PANEL OVERLAY (Hanya di Desktop) -->
            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h2 class="fw-bold">Selamat Datang Kembali!</h2>
                        <p class="my-3">Sudah punya akun? Masuk untuk melanjutkan rutinitas sehatmu hari ini.</p>
                        <button class="btn btn-outline-custom btn-auth px-5" id="signInBtn">Masuk</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h2 class="fw-bold">Halo, Teman Sehat!</h2>
                        <p class="my-3">Belum punya akun? Daftar sekarang dan mulai perjalanan hidup sehatmu bersama GenHeals.</p>
                        <button class="btn btn-outline-custom btn-auth px-5" id="signUpBtn">Daftar</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
    const container = document.getElementById('authContainer');
    
    // Fungsi untuk memicu transisi panel
    const showSignUp = () => container.classList.add('right-panel-active');
    const showSignIn = () => container.classList.remove('right-panel-active');

    // Desktop Buttons
    document.getElementById('signUpBtn').addEventListener('click', showSignUp);
    document.getElementById('signInBtn').addEventListener('click', showSignIn);
    
    // Mobile Buttons
    document.getElementById('mobileSignUpBtn').addEventListener('click', showSignUp);
    document.getElementById('mobileSignInBtn').addEventListener('click', showSignIn);

    // Tahan posisi di form register jika ada error registrasi dari PHP session
    <?php if(isset($_SESSION['register_error'])): ?>
        showSignUp();
        <?php unset($_SESSION['register_error']); ?>
    <?php endif; ?>

    // Validasi Password Match sebelum form dikirim
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.getElementById('regPassword').value;
        const confirmPassword = document.getElementById('regConfirmPassword').value;
        
        if(password !== confirmPassword) {
            e.preventDefault(); // Mencegah form terkirim
            alert('Oops! Kata sandi dan konfirmasi kata sandi tidak sama. Silakan periksa kembali.');
        }
    });
</script>

<?php include 'includes/footer.php'; ?>