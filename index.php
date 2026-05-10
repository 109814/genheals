<?php 
$title = "Home"; // Variabel ini akan ditangkap oleh header.php untuk tag <title>
include 'includes/header.php'; 
?>

<!-- KONTEN UTAMA HOME -->
<main>
    <div class="container mt-5 pt-4">
        <div class="row align-items-center">
            
            <!-- Teks Kiri -->
            <div class="col-lg-6 mb-5 fade-in-up">
                <span class="badge mb-3 px-3 py-2" style="background-color: var(--color-surface); color: var(--color-primary); border-radius: 20px;">#HealthyLifestyle</span>
                <h1 class="fw-bold mb-4" style="font-size: 3.5rem; color: var(--color-primary); line-height: 1.2;">
                    Langkah Kecil,<br>Dampak Besar.
                </h1>
                <p class="lead mb-4" style="color: var(--color-text); font-size: 1.1rem;">
                    Bebaskan dirimu dari rutinitas yang monoton. Mulai bangun kebiasaan sehat yang menyenangkan untuk tubuh dan pikiranmu setiap hari.
                </p>
                <a href="dashboard.php" class="btn btn-primary-custom btn-lg px-5 shadow">Mulai Tantangan</a>
            </div>
            
            <!-- Card Menu Kanan -->
            <div class="col-lg-5 offset-lg-1">
                
                <a href="#" class="text-decoration-none fade-in-up delay-1 d-block mb-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3" style="background-color: white; transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(250, 66, 126, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)'">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex justify-content-center align-items-center me-4" style="width: 70px; height: 70px; background-color: var(--color-surface); color: var(--color-primary);">
                                <i class="bi bi-person-arms-up fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1" style="color: var(--color-text);">Senam Badan</h5>
                                <p class="text-muted small mb-0">Jaga kebugaran otot dengan intensitas ringan.</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="#" class="text-decoration-none fade-in-up delay-2 d-block">
                    <div class="card border-0 shadow-sm rounded-4 p-3" style="background-color: white; transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(250, 66, 126, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)'">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex justify-content-center align-items-center me-4" style="width: 70px; height: 70px; background-color: var(--color-bg); color: var(--color-secondary);">
                                <i class="bi bi-droplet-fill fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1" style="color: var(--color-text);">Pelacak Air</h5>
                                <p class="text-muted small mb-0">Pantau hidrasi tubuhmu setiap saat.</p>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>