<?php

session_start();

ob_start();

?>


<div class="h-screen relative flex flex-col">
    <?php include 'components/navbar.php' ?>

    <div class="container mx-auto flex-1">
        <div class="h-full flex items-center justify-center ">
            <div class="grid h-120 max-w-230 rounded-4xl overflow-hidden bg-white grid-cols-5">
                <div class="col-span-2 relative overflow-hidden">
                    <div class="absolute flex flex-col justify-between top-0 bottom-0 left-0 right-0 bg-black/20 p-6">
                        <div> <a href="home.php"> <button
                                    class=" py-1 px-4 font-semibold rounded-4xl text-white bg-black/40">Kembali</button>
                            </a> </div>
                        <div class="text-white">
                            <span class="text-3xl font-semibold">Tantangan Pilates</span>
                            <p>Perkuat otot inti dan perbaiki postur tubuh dengan gerakan Pilates yang presisi.</p>
                        </div>
                    </div>
                    <img class="w-full h-full object-cover"
                        src="https://genheals.alwaysdata.net/UYEYY%20PROJECT%20GENHEALS/PROJECT%20GENHEALS/SL/GenHeals/img/pilatess.png"
                        alt="">
                </div>
                <div class="col-span-3 p-8">
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold">Daftar Latihan</span>
                        <span class="text-stone-500">7 Gerakan Tersedia</span>
                    </div>
                    <div class="mt-4 flex flex-col h-83 gap-4 overflow-y-auto">
                        <!-- foreach here -->
                        <div class="flex items-center gap-4 justify-between border rounded-3xl border-stone-200 p-4 ">
                            <div class="flex gap-3">
                                <div
                                    class="w-12 h-12 bg-stone-300 text-4xl flex items-center justify-center rounded-full">
                                    <i class="bx bx-pulse"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Core Activation</span>
                                    <span>00:45 • Fokus Perut</span>
                                </div>

                            </div>
                            <button onclick="show(1)"
                                class="w-12 h-12 bg-pink-700 text-4xl flex items-center justify-center text-white rounded-full">
                                <i class="bx bx-caret-down -rotate-90"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-4 justify-between border rounded-3xl border-stone-200 p-4 ">
                            <div class="flex gap-3">
                                <div
                                    class="w-12 h-12 bg-stone-300 text-4xl flex items-center justify-center rounded-full">
                                    <i class="bx bx-pulse"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Core Activation</span>
                                    <span>00:45 • Fokus Perut</span>
                                </div>

                            </div>
                            <button onclick="show(1)"
                                class="w-12 h-12 bg-pink-700 text-4xl flex items-center justify-center text-white rounded-full">
                                <i class="bx bx-caret-down -rotate-90"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-4 justify-between border rounded-3xl border-stone-200 p-4 ">
                            <div class="flex gap-3">
                                <div
                                    class="w-12 h-12 bg-stone-300 text-4xl flex items-center justify-center rounded-full">
                                    <i class="bx bx-pulse"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Core Activation</span>
                                    <span>00:45 • Fokus Perut</span>
                                </div>

                            </div>
                            <button onclick="show(1)"
                                class="w-12 h-12 bg-pink-700 text-4xl flex items-center justify-center text-white rounded-full">
                                <i class="bx bx-caret-down -rotate-90"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-4 justify-between border rounded-3xl border-stone-200 p-4 ">
                            <div class="flex gap-3">
                                <div
                                    class="w-12 h-12 bg-stone-300 text-4xl flex items-center justify-center rounded-full">
                                    <i class="bx bx-pulse"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Core Activation</span>
                                    <span>00:45 • Fokus Perut</span>
                                </div>

                            </div>
                            <button onclick="show(1)"
                                class="w-12 h-12 bg-pink-700 text-4xl flex items-center justify-center text-white rounded-full">
                                <i class="bx bx-caret-down -rotate-90"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-4 justify-between border rounded-3xl border-stone-200 p-4 ">
                            <div class="flex gap-3">
                                <div
                                    class="w-12 h-12 bg-stone-300 text-4xl flex items-center justify-center rounded-full">
                                    <i class="bx bx-pulse"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Core Activation</span>
                                    <span>00:45 • Fokus Perut</span>
                                </div>

                            </div>
                            <button onclick="show(1)"
                                class="w-12 h-12 bg-pink-700 text-4xl flex items-center justify-center text-white rounded-full">
                                <i class="bx bx-caret-down -rotate-90"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-4 justify-between border rounded-3xl border-stone-200 p-4 ">
                            <div class="flex gap-3">
                                <div
                                    class="w-12 h-12 bg-stone-300 text-4xl flex items-center justify-center rounded-full">
                                    <i class="bx bx-pulse"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Core Activation</span>
                                    <span>00:45 • Fokus Perut</span>
                                </div>

                            </div>
                            <button onclick="show(1)"
                                class="w-12 h-12 bg-pink-700 text-4xl flex items-center justify-center text-white rounded-full">
                                <i class="bx bx-caret-down -rotate-90"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-4 justify-between border rounded-3xl border-stone-200 p-4 ">
                            <div class="flex gap-3">
                                <div
                                    class="w-12 h-12 bg-stone-300 text-4xl flex items-center justify-center rounded-full">
                                    <i class="bx bx-pulse"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold">Core Activation</span>
                                    <span>00:45 • Fokus Perut</span>
                                </div>

                            </div>
                            <button onclick="show(1)"
                                class="w-12 h-12 bg-pink-700 text-4xl flex items-center justify-center text-white rounded-full">
                                <i class="bx bx-caret-down -rotate-90"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="1"
        class="fixed top-0 px-4 bg-black/80 transition-all duration-300 opacity-0 backdrop-blur-none hidden justify-center items-center bottom-0 left-0 right-0 overflow-hidden">
        <div class="bg-white h-120 gap-3 p-3 rounded-2xl relative   text-center w-230 flex flex-col ">
            <span class="font-semibold">Core Activation</span>
            <button onclick="hide(1)" class="text-2xl cursor-pointer text-black absolute flex justify-end w-full pe-5"><span><i
                        class="bx bx-x"></i></span></button>
            <div class="w-full h-full">
                <iframe class="w-full h-full rounded-2xl overflow-hidden"
                    src="https://www.youtube.com/embed/rv9jf4NIArY?si=u4fk2mwIb-Td1khF" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>

</div>

<script>

    function show(id) {
        document.getElementById(id).classList.add('flex!');
        document.getElementById(id).classList.add('backdrop-blur-sm!');
        document.getElementById(id).classList.add('opacity-100!');
    }

    function hide(id) {
        document.getElementById(id).classList.remove('flex!');
        document.getElementById(id).classList.remove('backdrop-blur-sm!');
        document.getElementById(id).classList.remove('opacity-100!');
    }

</script>
<?php
$content = ob_get_clean();

include 'layout/base.php';