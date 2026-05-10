<div class="h-20 shadow-xl  bg-white ">
    <div class="container ps-2 mx-auto flex items-center h-full justify-between">
        <span class="text-3xl  z-99 text-pink-700 font-bold">
            GenHeals
        </span>
        <div class="hidden md:flex items-center gap-8 font-semibold">
            <a href="" class="text-pink-400">Home</a>
            <a href="" class="hover:text-pink-400 transition duration-300">Artikel Sehat</a>
            <a href="" class="hover:text-pink-400 transition duration-300">Tantangan</a>
            <a href="" class="rounded-full px-4 py-2 bg-pink-700 text-white">My Account</a>
        </div>
        <div class="md:hidden block z-99">
            <button
                class="group  inline-flex w-12 h-12 text-slate-800 bg-white text-center items-center justify-center rounded cursor-pointer transition"
                aria-pressed="false"
                onclick="this.setAttribute('aria-pressed', !(this.getAttribute('aria-pressed') === 'true'));setNavOpen(!navOpen)">
                <span class="sr-only">Menu</span>
                <svg class="w-6 h-6  fill-pink-700! pointer-events-none" viewBox="0 0 16 16"
                    xmlns="http://www.w3.org/2000/svg">
                    <rect
                        class="origin-center  -translate-y-1.25 translate-x-[7px] transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.1)] group-aria-pressed:translate-x-0 group-aria-pressed:translate-y-0 group-aria-pressed:rotate-[315deg]"
                        y="7" width="9" height="2" rx="1"></rect>
                    <rect
                        class="origin-center  transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.8)] group-aria-pressed:rotate-45"
                        y="7" width="16" height="2" rx="1"></rect>
                    <rect
                        class="origin-center  translate-y-1.25 transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.1)] group-aria-pressed:translate-y-0 group-aria-pressed:rotate-[135deg]"
                        y="7" width="9" height="2" rx="1"></rect>
                </svg>
            </button>
        </div>
        <div id="nav-mobile"
            class="fixed w-[0%] transition-all duration-300 overflow-hidden  top-0 bottom-0 bg-white  right-0">
            <div class="w-full mt-15 md:hidden flex justify-end relative">
                <div
                    class="menuref overflow-x-hidden w-full text-center font-semibold  p-6 pt-6! flex h-full justify-between flex-col gap-6 overflow-auto ">
                    <a href="" class="text-pink-400">Home</a>
                    <a href="" class="hover:text-pink-400 whitespace-nowrap transition duration-300">Artikel Sehat</a>
                    <a href="" class="hover:text-pink-400 transition duration-300">Tantangan</a>
                    <div class="flex-none ">
                        <a href="" class="rounded-full px-4 py-2 bg-pink-700 whitespace-nowrap text-white w-auto">My Account</a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    let navOpen = false;

    function setNavOpen(value) {
        if (!navOpen) {
            document.getElementById('nav-mobile').classList.add('w-full!');
        } else {
            document.getElementById('nav-mobile').classList.remove('w-full!');
        }

        navOpen = value;
    }
</script>