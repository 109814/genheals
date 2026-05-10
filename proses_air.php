<?php
session_start();
header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "genheals_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_air') {
    
    $user_id = $_SESSION['user_id'];
    $tanggal = date('Y-m-d');
    $jumlah_gelas = (int)$_POST['jumlah_gelas'];
    $total_ml = $jumlah_gelas * 250; // Asumsi 1 gelas = 250ml

    // Cek apakah data air hari ini sudah ada untuk user tersebut
    $stmt_cek = $conn->prepare("SELECT id FROM pelacak_air WHERE user_id = ? AND tanggal = ?");
    $stmt_cek->bind_param("is", $user_id, $tanggal);
    $stmt_cek->execute();
    $res_cek = $stmt_cek->get_result();

    if ($res_cek->num_rows > 0) {
        // Jika sudah ada, lakukan UPDATE
        $stmt_update = $conn->prepare("UPDATE pelacak_air SET jumlah_gelas = ?, total_ml = ? WHERE user_id = ? AND tanggal = ?");
        $stmt_update->bind_param("iiis", $jumlah_gelas, $total_ml, $user_id, $tanggal);
        if ($stmt_update->execute()) {
            echo json_encode(['status' => 'success', 'gelas' => $jumlah_gelas, 'total_ml' => $total_ml]);
        } else {
            echo json_encode(['status' => 'error']);
        }
        $stmt_update->close();
    } else {
        // Jika belum ada, lakukan INSERT
        $stmt_insert = $conn->prepare("INSERT INTO pelacak_air (user_id, tanggal, jumlah_gelas, total_ml) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("isii", $user_id, $tanggal, $jumlah_gelas, $total_ml);
        if ($stmt_insert->execute()) {
            echo json_encode(['status' => 'success', 'gelas' => $jumlah_gelas, 'total_ml' => $total_ml]);
        } else {
            echo json_encode(['status' => 'error']);
        }
        $stmt_insert->close();
    }
    
    $stmt_cek->close();
    $conn->close();
    exit();
}
?>