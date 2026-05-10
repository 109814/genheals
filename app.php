<?php

session_start();

ob_start();
?>


<div class="h-screen flex flex-col">
    <?php include 'components/navbar.php' ?>

    <div class="container mx-auto flex-1">
        <div class="grid items-center h-full grid-cols-2">
            <div class="flex flex-col gap-6">
                <div
                    class="rounded-4xl px-4 py-0.5 flex w-fit items-center justify-center font-semibold bg-pink-700/20 text-pink-700">
                    <span>#HealthyLifestyle</span>
                </div>
                <span class="text-6xl text-pink-700 font-bold">Langkah Kecil,
                    Dampak Besar.</span>
                <p>Bebaskan dirimu dari rutinitas yang monoton. Mulai bangun kebiasaan sehat yang menyenangkan untuk
                    tubuh dan pikiranmu setiap hari.</p>
                <a href="auth.php">
                    <button class="w-fit px-4 py-2 bg-pink-700 text-white rounded-4xl text-xl font-semibold">Mulai
                        Tantangan</button>
                </a>
            </div>
            <div class="ps-20 flex flex-col gap-6">
                <div
                    class="bg-white p-4 group hover:flex-col transition-all duration-300 rounded-2xl shadow-lg gap-4  flex items-center ">
                    <div
                        class="w-16 h-16 rounded-2xl group-hover:w-24 group-hover:h-24 transition-all duration-300 overflow-hidden">
                        <img src="images/app/OIP.jpg" class="object-cover h-full w-full" alt="">
                    </div>
                    <div><span class="font-semibold text-3xl">Senam Badan</span></div>
                    <div>
                        <p class="hidden group-hover:block">Latihan intensitas ringan untuk menjaga kebugaran otot dan
                            sendi.</p>
                    </div>
                </div>
                <div
                    class="bg-white p-4 group hover:flex-col transition-all duration-300 rounded-2xl shadow-lg gap-4  flex items-center ">
                    <div
                        class="w-16 h-16 rounded-2xl group-hover:w-24 group-hover:h-24 transition-all duration-300 overflow-hidden">
                        <img src="images/app/OIP (1).jpg" class="object-cover h-full w-full" alt="">
                    </div>
                    <div><span class="font-semibold text-3xl">Senam Wajah</span></div>
                    <div>
                        <p class="hidden group-hover:block">Gerakan simpel untuk merelaksasi otot wajah dan elastisitas
                            kulit.</p>
                    </div>
                </div>
                <div
                    class="bg-white p-4 group hover:flex-col transition-all duration-300 rounded-2xl shadow-lg gap-4  flex items-center ">
                    <div
                        class="w-16 h-16 rounded-2xl group-hover:w-24 group-hover:h-24 transition-all duration-300 overflow-hidden">
                        <img src="images/app/OIP (2).jpg" class="object-cover h-full w-full" alt="">
                    </div>
                    <div><span class="font-semibold text-3xl">Penghemat Air</span></div>
                    <div>
                        <p class="hidden group-hover:block">Pantau hidrasi tubuhmu. Jangan lupa minum 8 gelas air setiap
                            hari!</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
<?php
$content = ob_get_clean();

include 'layout/base.php';