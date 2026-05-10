<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GenHeals - <?= isset($title) ? $title : 'Healthy Lifestyle'; ?></title>
    
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        /* =========================================
           1. PALET WARNA (Sesuai Gambar Palet Baru)
           ========================================= */
        :root {
            /* Warna 1 (Paling Atas): Magenta Tua - Untuk header, teks tebal, & elemen dominan */
            --color-primary: #C51F5D;    
            
            /* Warna 2: Pink Cerah - Untuk tombol utama, hover, & aksen interaktif */
            --color-secondary: #FF4B82;  
            
            /* Warna 3: Pink Pastel - Untuk badge, card ringan, & background kotak */
            --color-surface: #FFB6C1;    
            
            /* Warna 4 (Paling Bawah): Putih Kemerahan/Pink Sangat Muda - Untuk background utama web */
            --color-bg: #FFF0F5;         
            
            /* Tambahan: Abu-abu gelap agar teks tetap nyaman dibaca (kontras bagus) */
            --color-text: #2D3748;       
        }

        /* =========================================
           2. FONDASI LAYOUT (Footer selalu di bawah)
           ========================================= */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        main { flex: 1; } /* Mendorong footer ke bawah */

        /* =========================================
           3. NAVBAR & OFFCANVAS (Responsive Sidebar)
           ========================================= */
        .custom-navbar { background-color: white; box-shadow: 0 2px 15px rgba(197, 31, 93, 0.1); }
        .navbar-brand { color: var(--color-primary) !important; font-weight: 800; font-size: 1.5rem; letter-spacing: 1px; }
        .nav-link { font-weight: 500; color: var(--color-text) !important; transition: color 0.3s; }
        .nav-link:hover, .nav-link.active { color: var(--color-secondary) !important; }
        
        .btn-primary-custom { 
            background-color: var(--color-primary); 
            color: white; 
            border-radius: 25px; 
            font-weight: 600; 
            transition: all 0.3s;
            border: none;
        }
        .btn-primary-custom:hover { 
            background-color: var(--color-secondary); 
            transform: translateY(-2px); 
            color: white;
            box-shadow: 0 5px 15px rgba(255, 75, 130, 0.4);
        }

        /* Animasi */
        .fade-in-up { animation: fadeInUp 0.8s ease-out forwards; opacity: 0; transform: translateY(20px); }
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <!-- NAVBAR (Menjadi Sidebar di Mobile) -->
    <nav class="navbar navbar-expand-lg custom-navbar sticky-top py-3">
        <div class="container">
            <a class="navbar-brand" href="index.php">GenHeals</a>
            
            <!-- Tombol Toggle untuk Mobile -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
                <i class="bi bi-list fs-2" style="color: var(--color-primary);"></i>
            </button>

            <!-- Menu Desktop & Isi Sidebar Mobile -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSidebar">
                <div class="offcanvas-header bg-light">
                    <h5 class="offcanvas-title fw-bold" style="color: var(--color-primary);">Menu GenHeals</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav ms-auto align-items-center gap-3">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Artikel Sehat</a></li>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Tantangan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Tantangan</a></li>
                        <li class="nav-item ms-lg-3">
                            <a href="account.php" class="btn btn-primary-custom px-4 py-2 shadow-sm">My Account</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>