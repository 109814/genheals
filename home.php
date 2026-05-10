<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GenHeals - Latihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .header-dark { background-color: #1a0a0a; color: white; padding: 20px 0; }
        .nav-tabs .nav-link { color: #555; font-weight: bold; border: none; border-bottom: 3px solid transparent; }
        .nav-tabs .nav-link.active { color: #800000; border-bottom: 3px solid #800000; background: transparent; }
        .card-latihan { background-color: #f5cfcf; border: none; border-radius: 15px; overflow: hidden; transition: transform 0.3s ease; }
        .card-latihan:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .btn-mulai { background-color: #800000; color: white; font-weight: bold; border-radius: 0 0 15px 15px; }
        .btn-mulai:hover { background-color: #6b0f0f; color: white; }
        
        /* CSS Untuk Pelacak Air */
        .water-container { width: 200px; height: 200px; border-radius: 50%; border: 8px solid #e0eaf5; position: relative; overflow: hidden; margin: 0 auto; background-color: white; }
        .water-fill { position: absolute; bottom: 0; left: 0; width: 100%; height: 0%; background-color: #4da6ff; transition: height 0.5s ease-in-out; }
    </style>
</head>
<body>

    <div class="header-dark mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h3 class="fw-bold m-0">GenHeals</h3>
            <a href="account.php" class="text-white text-decoration-none">Profil</a>
        </div>
    </div>

    <div class="container">
        <h2 class="fw-bold mb-4">TANTANGAN</h2>

        <ul class="nav nav-tabs justify-content-center mb-5" id="latihanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tantangan-tab" data-bs-toggle="tab" data-bs-target="#tantangan" type="button" role="tab">Tantangan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="wajah-tab" data-bs-toggle="tab" data-bs-target="#wajah" type="button" role="tab">Perawatan Wajah</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="fokus-tab" data-bs-toggle="tab" data-bs-target="#fokus" type="button" role="tab">Fokus Tubuh</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="air-tab" data-bs-toggle="tab" data-bs-target="#air" type="button" role="tab">Pelacak Air</button>
            </li>
        </ul>

        <div class="tab-content" id="latihanTabsContent">
            
            <div class="tab-pane fade show active" id="tantangan" role="tabpanel">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card card-latihan text-center h-100">
                            <div class="card-body p-4">
                                <span class="badge bg-light text-dark position-absolute top-0 start-0 m-3 rounded-pill">PILATES</span>
                                <div style="height: 150px; display: flex; align-items: center; justify-content: center;">🏃‍♀️</div>
                            </div>
                            <button class="btn btn-mulai w-100 py-3">MULAI</button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card card-latihan text-center h-100">
                            <div class="card-body p-4">
                                <span class="badge bg-light text-dark position-absolute top-0 start-0 m-3 rounded-pill">YOGA</span>
                                <div style="height: 150px; display: flex; align-items: center; justify-content: center;">🧘‍♀️</div>
                            </div>
                            <button class="btn btn-mulai w-100 py-3">MULAI</button>
                        </div>
                    </div>
                    </div>
            </div>

            <div class="tab-pane fade" id="air" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <h4 class="fw-bold mb-4">Pelacak Air Harian</h4>
                    <div class="water-container mb-4 shadow-sm">
                        <div class="water-fill" id="waterFill"></div>
                    </div>
                    <h3 class="fw-bold text-primary" id="waterCountText">0 / 8 gelas</h3>
                    <p class="text-muted" id="waterMotivation">Ayo mulai minum gelas pertamamu hari ini!</p>
                    
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <button class="btn btn-primary rounded-circle shadow" style="width: 60px; height: 60px; font-size: 24px;" onclick="addWater()">+</button>
                        <button class="btn btn-outline-secondary rounded-pill px-4" onclick="resetWater()">Reset</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let currentGlasses = 0;
        const maxGlasses = 8;
        const waterFill = document.getElementById('waterFill');
        const waterCountText = document.getElementById('waterCountText');
        const waterMotivation = document.getElementById('waterMotivation');

        function addWater() {
            if (currentGlasses < maxGlasses) {
                currentGlasses++;
                updateWaterUI();
            }
        }

        function resetWater() {
            currentGlasses = 0;
            updateWaterUI();
        }

        function updateWaterUI() {
            // Hitung persentase tinggi air
            const percentage = (currentGlasses / maxGlasses) * 100;
            waterFill.style.height = percentage + '%';
            waterCountText.innerText = currentGlasses + ' / 8 gelas';

            // Ubah teks motivasi
            if (currentGlasses === 0) {
                waterMotivation.innerText = "Ayo mulai minum gelas pertamamu hari ini!";
            } else if (currentGlasses > 0 && currentGlasses < 4) {
                waterMotivation.innerText = "Bagus! Tetap terhidrasi.";
            } else if (currentGlasses >= 4 && currentGlasses < 8) {
                waterMotivation.innerText = "Setengah jalan menuju target!";
            } else {
                waterMotivation.innerText = "Luar biasa! Target hidrasimu tercapai hari ini.";
            }
        }
    </script>
</body>
</html>