<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit();
}

$title = "Dashboard Latihan";
include 'includes/header.php';

// KONEKSI & AMBIL DATA DARI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "genheals_db";
$conn = new mysqli($host, $user, $pass, $db);

$user_id = $_SESSION['user_id'];
$tanggal_hari_ini = date('Y-m-d');
$gelas_sekarang = 0;
$total_ml_sekarang = 0;

// 1. Ambil Data Pelacak Air
$stmt_air = $conn->prepare("SELECT jumlah_gelas, total_ml FROM pelacak_air WHERE user_id = ? AND tanggal = ?");
$stmt_air->bind_param("is", $user_id, $tanggal_hari_ini);
$stmt_air->execute();
$res_air = $stmt_air->get_result();
if ($res_air->num_rows > 0) {
    $row_air = $res_air->fetch_assoc();
    $gelas_sekarang = $row_air['jumlah_gelas'];
    $total_ml_sekarang = $row_air['total_ml'];
}
$stmt_air->close();

// 2. Ambil Data Modul Perawatan Wajah (Dinamis)
$res_wajah = $conn->query("SELECT * FROM modul_latihan WHERE kategori = 'Perawatan Wajah' ORDER BY id DESC");
?>

<style>
    .dashboard-header {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white; padding: 40px 0 20px 0; border-radius: 0 0 30px 30px; margin-bottom: 30px;
        box-shadow: 0 10px 20px rgba(197, 31, 93, 0.15);
    }
    
    .nav-tabs { border-bottom: 2px solid var(--color-surface); justify-content: center; border: none; gap: 10px;}
    .nav-tabs .nav-link { 
        color: var(--color-text); font-weight: 600; border: none; padding: 10px 20px;
        border-radius: 20px; transition: all 0.3s ease; background-color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .nav-tabs .nav-link:hover { background-color: var(--color-surface); color: var(--color-primary); }
    .nav-tabs .nav-link.active { background-color: var(--color-primary); color: white; box-shadow: 0 5px 15px rgba(255, 75, 130, 0.3); }

    .card-latihan { 
        border: none; border-radius: 20px; background: white; text-align: center; padding: 25px 15px 0 15px; 
        transition: 0.3s; box-shadow: 0 8px 20px rgba(0,0,0,0.05); overflow: hidden; height: 100%; display: flex; flex-direction: column; justify-content: space-between;
    }
    .card-latihan:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(255, 75, 130, 0.15); }
    .btn-mulai { background-color: var(--color-primary); color: white; width: calc(100% + 30px); margin-left: -15px; margin-bottom: 0; padding: 12px; border: none; font-weight: bold; transition: 0.3s;}
    .btn-mulai:hover { background-color: var(--color-secondary); color: white; }

    .card-treatment {
        background-color: white; border-radius: 20px; padding: 15px; border: 1px solid #eee;
        transition: all 0.3s ease; text-decoration: none; display: flex; flex-direction: column;
        color: var(--color-text); height: 100%; box-shadow: 0 8px 15px rgba(0,0,0,0.05);
    }
    .card-treatment:hover { transform: translateY(-5px); box-shadow: 0 15px 25px rgba(197, 31, 93, 0.1); border-color: var(--color-surface); color: var(--color-text); }
    .card-treatment img { width: 100%; height: 200px; object-fit: cover; border-radius: 15px; margin-bottom: 15px; }
    
    .btn-circle-dark { background-color: #212529; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: 0.3s; flex-shrink: 0;}
    .card-treatment:hover .btn-circle-dark { background-color: var(--color-primary); transform: scale(1.1); }

    /* MENU FOKUS TUBUH (LINGKARAN) */
    .focus-circle {
        width: 130px; height: 130px; border-radius: 50%; border: 5px solid var(--color-surface);
        overflow: hidden; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .focus-circle img { width: 100%; height: 100%; object-fit: cover; }
    .body-focus-link { display: block; }
    .body-focus-link:hover .focus-circle { border-color: var(--color-primary); transform: scale(1.05); box-shadow: 0 10px 20px rgba(197, 31, 93, 0.2); }
    .body-focus-link:hover h6 { color: var(--color-primary); }

    .water-tracker-box { background-color: white; border-radius: 25px; padding: 40px 20px; box-shadow: 0 10px 30px rgba(197, 31, 93, 0.08); }
    .glass-circle { width: 220px; height: 220px; border: 8px solid var(--color-surface); border-radius: 50%; margin: 0 auto; position: relative; overflow: hidden; background-color: #fafafa; display: flex; align-items: center; justify-content: center; box-shadow: inset 0 0 20px rgba(0,0,0,0.05); }
    .water-fill { position: absolute; bottom: 0; width: 100%; height: 0%; background: linear-gradient(to top, #4facfe 0%, #00f2fe 100%); transition: height 0.6s cubic-bezier(0.4, 0.0, 0.2, 1); z-index: 1; opacity: 0.8; }
    .glass-text { position: relative; z-index: 2; font-weight: 800; font-size: 1.8rem; color: var(--color-text); }
</style>

<div class="dashboard-header text-center fade-in-up">
    <div class="container">
        <h2 class="fw-bold mb-2">Halo, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="mb-0" style="opacity: 0.9;">Pilih Program Latihanmu. Konsistensi adalah kunci!</p>
    </div>
</div>

<main class="mb-5 fade-in-up delay-1">
    <div class="container">
        
        <ul class="nav nav-tabs mb-5 flex-nowrap overflow-auto pb-2" id="latihanTab" role="tablist" style="white-space: nowrap;">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tantangan" type="button">Tantangan</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#perawatan" type="button">Perawatan Wajah</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#fokus" type="button">Fokus Tubuh</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pelacak" type="button"><i class="bi bi-droplet-fill me-1"></i> Pelacak Air</button></li>
        </ul>

        <div class="tab-content" id="latihanTabContent">
            
            <div class="tab-pane fade show active" id="tantangan" role="tabpanel">
                <div class="row g-4">
                    <div class="col-6 col-md-3">
                        <div class="card-latihan">
                            <span class="badge bg-light text-dark mb-2 mx-auto rounded-pill px-3 shadow-sm" style="width: max-content;">PILATES 5</span>
                            <div class="flex-grow-1 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-person-fill text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <a href="#" class="btn btn-mulai text-decoration-none d-block">MULAI</a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card-latihan">
                            <span class="badge bg-light text-dark mb-2 mx-auto rounded-pill px-3 shadow-sm" style="width: max-content;">YOGA</span>
                            <div class="flex-grow-1 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-person-hearts text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <a href="#" class="btn btn-mulai text-decoration-none d-block">MULAI</a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card-latihan">
                            <span class="badge bg-light text-dark mb-2 mx-auto rounded-pill px-3 shadow-sm" style="width: max-content;">WORKOUT</span>
                            <div class="flex-grow-1 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-person-workspace text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <a href="#" class="btn btn-mulai text-decoration-none d-block">MULAI</a>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card-latihan">
                            <span class="badge bg-light text-dark mb-2 mx-auto rounded-pill px-3 shadow-sm" style="width: max-content;">SENAM</span>
                            <div class="flex-grow-1 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-person-arms-up text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <a href="#" class="btn btn-mulai text-decoration-none d-block">MULAI</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="perawatan" role="tabpanel">
                <div class="row g-4 justify-content-center">
                    <?php if ($res_wajah && $res_wajah->num_rows > 0): while($row = $res_wajah->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4">
                            <a href="detail_latihan.php?id=<?= $row['id']; ?>" class="card-treatment">
                                <img src="<?= htmlspecialchars($row['gambar_cover']); ?>" alt="<?= htmlspecialchars($row['judul']); ?>">
                                <div class="d-flex justify-content-between align-items-end mt-auto">
                                    <div>
                                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($row['judul']); ?></h5>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i> <?= htmlspecialchars($row['estimasi_waktu']); ?></small>
                                    </div>
                                    <div class="btn-circle-dark"><i class="bi bi-arrow-right"></i></div>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; else: ?>
                        <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                            <i class="bi bi-emoji-smile text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="fw-bold" style="color: var(--color-primary);">Perawatan Wajah</h5>
                            <p class="text-muted">Belum ada modul Perawatan Wajah yang diunggah.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="fokus" role="tabpanel">
                <div class="row g-4 justify-content-center pt-3">
                    
                    <div class="col-6 col-md-3 text-center">
                        <a href="fokus_tubuh.php?kategori=Seluruh Tubuh" class="text-decoration-none text-dark body-focus-link">
                            <div class="focus-circle mx-auto mb-3">
                                <img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?w=500&q=80" alt="Seluruh tubuh">
                            </div>
                            <h6 class="fw-bold transition">Seluruh tubuh</h6>
                        </a>
                    </div>
                    
                    <div class="col-6 col-md-3 text-center">
                        <a href="fokus_tubuh.php?kategori=Otot Perut" class="text-decoration-none text-dark body-focus-link">
                            <div class="focus-circle mx-auto mb-3">
                                <img src="https://images.unsplash.com/photo-1507398941214-572c25f4b1dc?w=500&q=80" alt="Otot perut">
                            </div>
                            <h6 class="fw-bold">Otot perut</h6>
                        </a>
                    </div>
                    
                    <div class="col-6 col-md-3 text-center">
                        <a href="fokus_tubuh.php?kategori=Lengan" class="text-decoration-none text-dark body-focus-link">
                            <div class="focus-circle mx-auto mb-3">
                                <img src="https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=500&q=80" alt="Lengan">
                            </div>
                            <h6 class="fw-bold">Lengan</h6>
                        </a>
                    </div>
                    
                    <div class="col-6 col-md-3 text-center">
                        <a href="fokus_tubuh.php?kategori=Pinggul Kaki" class="text-decoration-none text-dark body-focus-link">
                            <div class="focus-circle mx-auto mb-3">
                                <img src="https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=500&q=80" alt="Pinggul & Kaki">
                            </div>
                            <h6 class="fw-bold">Pinggul & Kaki</h6>
                        </a>
                    </div>

                </div>
            </div>

            <div class="tab-pane fade" id="pelacak" role="tabpanel">
                <div class="water-tracker-box">
                    <div class="row align-items-center">
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            <div class="glass-circle mb-4">
                                <div class="water-fill" id="water-fill"></div>
                                <span class="glass-text" id="glass-count">0 / 8<br><small class="fw-medium text-muted" style="font-size: 1rem;">gelas</small></span>
                            </div>
                            <div class="d-flex justify-content-center gap-3">
                                <button id="reset-water-btn" class="btn btn-light rounded-circle shadow-sm" style="width: 50px; height: 50px;"><i class="bi bi-arrow-counterclockwise fs-5"></i></button>
                                <button id="add-water-btn" class="btn btn-primary-custom rounded-circle shadow" style="width: 60px; height: 60px; font-size: 24px;">+</button>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="p-4 rounded-4" style="background-color: var(--color-bg); border: 1px dashed var(--color-primary);">
                                <h4 class="fw-bold mb-3" style="color: var(--color-primary);">Motivasi Harian</h4>
                                <h5 id="motivation-text" class="fw-bold text-dark mb-2">Ayo mulai harimu!</h5>
                                <p class="text-muted mb-4">Awali harimu dengan hidrasi yang cukup untuk menjaga metabolisme tubuh.</p>
                                <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-white shadow-sm">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-cup-straw" style="color: var(--color-secondary); font-size: 1.5rem;"></i>
                                        <div>
                                            <small class="text-muted d-block" style="line-height: 1;">Volume per gelas</small>
                                            <span class="fw-bold text-dark">250 ml</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block" style="line-height: 1;">Total Hari Ini</small>
                                        <span class="fw-bold fs-5" style="color: var(--color-primary);" id="total-ml-text">0 ml</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
    let currentGlasses = <?= $gelas_sekarang; ?>;
    const maxGlasses = 8;
    const volumePerGlass = 250;
    const countDisplay = document.getElementById('glass-count');
    const fillDisplay = document.getElementById('water-fill');
    const totalMlDisplay = document.getElementById('total-ml-text');
    const motivationText = document.getElementById('motivation-text');
    
    const motivations = ["Ayo mulai harimu!", "Gelas pertama! Awal yang bagus.", "Dua gelas! Kulitmu akan berterima kasih.", "Tiga gelas! Tetap semangat.", "Empat gelas! Setengah jalan menuju target.", "Lima gelas! Tubuhmu semakin segar.", "Enam gelas! Sedikit lagi mencapai target.", "Tujuh gelas! Hampir selesai.", "Target tercapai! Kamu luar biasa hari ini 🎉", "Overhidrasi? Jangan lupa ke toilet! 😂"];

    updateWaterUI();

    document.getElementById('add-water-btn').addEventListener('click', () => { if (currentGlasses < 15) simpanDataKeDatabase(currentGlasses + 1); });
    document.getElementById('reset-water-btn').addEventListener('click', () => { if(confirm("Yakin mereset target minum hari ini?")) simpanDataKeDatabase(0); });

    function simpanDataKeDatabase(jumlahBaru) {
        fetch('proses_air.php', {
            method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: `action=update_air&jumlah_gelas=${jumlahBaru}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') { currentGlasses = data.gelas; updateWaterUI(); } 
            else alert("Gagal menyimpan data!");
        }).catch(err => alert("Terjadi kesalahan sistem."));
    }

    function updateWaterUI() {
        countDisplay.innerHTML = `${currentGlasses} / ${maxGlasses}<br><small class="fw-medium text-muted" style="font-size: 1rem;">gelas</small>`;
        totalMlDisplay.innerText = `${currentGlasses * volumePerGlass} ml`;
        let fillPercentage = (currentGlasses / maxGlasses) * 100;
        fillDisplay.style.height = `${fillPercentage > 100 ? 100 : fillPercentage}%`;
        motivationText.style.color = currentGlasses >= maxGlasses ? '#198754' : 'var(--color-text)'; 
        motivationText.innerText = motivations[currentGlasses <= 8 ? currentGlasses : 9];
    }
</script>

<?php $conn->close(); include 'includes/footer.php'; ?>