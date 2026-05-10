<?php
session_start();
require_once("koneksi.php");

// ==========================================
// 2. LOGIKA AUTENTIKASI
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Pastikan variabel 'action' ada
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // --------------------------------------
        // PROSES REGISTRASI
        // --------------------------------------
        if ($action === 'register') {
            // Mengambil data dari form HTML (name="username" dan name="whatsapp")
            $username = trim($_POST['username']);
            $whatsapp = trim($_POST['whatsapp']);
            $password = $_POST['password'];
            $konfirmasi_password = $_POST['konfirmasi_password'];

            // Cek apakah password & konfirmasi sama
            if ($password !== $konfirmasi_password) {
                $_SESSION['pesan'] = "<strong>Gagal!</strong> Kata sandi dan konfirmasi tidak cocok.";
                $_SESSION['tipe_pesan'] = "danger";
                $_SESSION['register_error'] = true;
                header("Location: auth.php");
                exit();
            }

            // Cek apakah nama_pengguna atau nomor_whatsapp sudah terdaftar di database
            $stmt_check = $conn->prepare("SELECT id FROM users WHERE nama_pengguna = ? OR nomor_whatsapp = ?");
            $stmt_check->bind_param("ss", $username, $whatsapp);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $_SESSION['pesan'] = "<strong>Gagal!</strong> Nama Pengguna atau Nomor WhatsApp sudah digunakan.";
                $_SESSION['tipe_pesan'] = "warning";
                $_SESSION['register_error'] = true; 
                header("Location: auth.php");
                exit();
            } else {
                // Enkripsi Password agar aman
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Masukkan data ke database sesuai dengan kolom yang baru
                $stmt_insert = $conn->prepare("INSERT INTO users (nama_pengguna, nomor_whatsapp, password) VALUES (?, ?, ?)");
                $stmt_insert->bind_param("sss", $username, $whatsapp, $hashed_password);
                
                if ($stmt_insert->execute()) {
                    $_SESSION['pesan'] = "<strong>Berhasil!</strong> Akunmu telah dibuat. Silakan masuk.";
                    $_SESSION['tipe_pesan'] = "success";
                    header("Location: auth.php");
                    exit();
                } else {
                    $_SESSION['pesan'] = "<strong>Terjadi Kesalahan!</strong> Gagal mendaftarkan akun.";
                    $_SESSION['tipe_pesan'] = "danger";
                    $_SESSION['register_error'] = true;
                    header("Location: auth.php");
                    exit();
                }
                $stmt_insert->close();
            }
            $stmt_check->close();
        }

        // --------------------------------------
        // PROSES LOGIN
        // --------------------------------------
        elseif ($action === 'login') {
            $login_id = trim($_POST['username']); // Input ini bisa berisi nama_pengguna ATAU nomor_whatsapp
            $password = $_POST['password'];

            // Cari user berdasarkan nama_pengguna ATAU nomor_whatsapp di kolom database yang baru
            $stmt_login = $conn->prepare("SELECT id, nama_pengguna, password, role FROM users WHERE nama_pengguna = ? OR nomor_whatsapp = ?");
            $stmt_login->bind_param("ss", $login_id, $login_id);
            $stmt_login->execute();
            $result = $stmt_login->get_result();

            if ($result->num_rows === 1) {
                $user_data = $result->fetch_assoc();
                
                // Verifikasi kecocokan password dengan hash yang ada di database
                if (password_verify($password, $user_data['password'])) {
                    
                    // Berhasil Login: Set Sesi
                    $_SESSION['user_id'] = $user_data['id'];
                    $_SESSION['username'] = $user_data['nama_pengguna'];
                    $_SESSION['role'] = $user_data['role']; // Simpan role untuk membedakan admin/user
                    
                    // Arahkan ke halaman My Account
                    header("Location: account.php");
                    exit();
                } else {
                    $_SESSION['pesan'] = "<strong>Gagal Masuk!</strong> Kata sandi yang kamu masukkan salah.";
                    $_SESSION['tipe_pesan'] = "danger";
                    header("Location: auth.php");
                    exit();
                }
            } else {
                $_SESSION['pesan'] = "<strong>Gagal Masuk!</strong> Akun tidak ditemukan.";
                $_SESSION['tipe_pesan'] = "danger";
                header("Location: auth.php");
                exit();
            }
            $stmt_login->close();
        }
    }
}

$conn->close();
?>