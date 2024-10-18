<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body>
    <div class="relative flex flex-col">
        <svg id="menu-button" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 md:hidden fixed cursor-pointer z-50 top-4 left-4 mb-5" onclick="toggleNav()">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
        <nav id="nav" class="fixed z-50 left-4 md:left-0 h-full w-[180px] flex flex-col bg-purple-400 border-r shadow-sm md:mt-0 mt-10 rounded-md md:rounded-none">
            <img src="{{ asset('images/logo-polm.png') }}" class="p-3 w-fit h-[70px] object-cover">
            <div class="flex flex-row ml-2 gap-2 items-center p-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                    <path d="M10.5 1.875a1.125 1.125 0 0 1 2.25 0v8.219c.517.162 1.02.382 1.5.659V3.375a1.125 1.125 0 0 1 2.25 0v10.937a4.505 4.505 0 0 0-3.25 2.373 8.963 8.963 0 0 1 4-.935A.75.75 0 0 0 18 15v-2.266a3.368 3.368 0 0 1 .988-2.37 1.125 1.125 0 0 1 1.591 1.59 1.118 1.118 0 0 0-.329.79v3.006h-.005a6 6 0 0 1-1.752 4.007l-1.736 1.736a6 6 0 0 1-4.242 1.757H10.5a7.5 7.5 0 0 1-7.5-7.5V6.375a1.125 1.125 0 0 1 2.25 0v5.519c.46-.452.965-.832 1.5-1.141V3.375a1.125 1.125 0 0 1 2.25 0v6.526c.495-.1.997-.151 1.5-.151V1.875Z" />
                </svg>
                <span class="text-md font-medium text-white font-batik">Hi {{ Auth::user()->name }} !</span>
            </div>
            <div class="flex-1 overflow-y-auto p-1 pb-2 justify-between items-center">
                <ul class="flex flex-col gap-2">
                    <li class="nav-item menu-items">
                        <div id="sevenpilar">
                            <ul class="nav flex-column sub-menu">
                                <li>
                                    <a class="collapsible-header waves-effect arrow-r">
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <li class="nav-item menu-items ">
                        <a href="{{ route('dashboard') }}" class=" flex flex-row gap-3 items-center hover:bg-purple-300 hover:text-white py-1 px-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                                <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                                <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                            </svg>
                            <span class="text-md font-batik text-white font-medium">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item menu-items ">
                        <a href="{{ route('prestasi.index') }}" class=" flex flex-row gap-3 items-center hover:bg-purple-300 hover:text-white py-1 px-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                                <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 0 0-.584.859 6.753 6.753 0 0 0 6.138 5.6 6.73 6.73 0 0 0 2.743 1.346A6.707 6.707 0 0 1 9.279 15H8.54c-1.036 0-1.875.84-1.875 1.875V19.5h-.75a2.25 2.25 0 0 0-2.25 2.25c0 .414.336.75.75.75h15a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-2.25-2.25h-.75v-2.625c0-1.036-.84-1.875-1.875-1.875h-.739a6.706 6.706 0 0 1-1.112-3.173 6.73 6.73 0 0 0 2.743-1.347 6.753 6.753 0 0 0 6.139-5.6.75.75 0 0 0-.585-.858 47.077 47.077 0 0 0-3.07-.543V2.62a.75.75 0 0 0-.658-.744 49.22 49.22 0 0 0-6.093-.377c-2.063 0-4.096.128-6.093.377a.75.75 0 0 0-.657.744Zm0 2.629c0 1.196.312 2.32.857 3.294A5.266 5.266 0 0 1 3.16 5.337a45.6 45.6 0 0 1 2.006-.343v.256Zm13.5 0v-.256c.674.1 1.343.214 2.006.343a5.265 5.265 0 0 1-2.863 3.207 6.72 6.72 0 0 0 .857-3.294Z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-md font-batik text-white font-medium">Prestasi</span>
                        </a>
                    </li>
                    <li class="nav-item menu-items ">
                        <a href="{{ route('users.index') }}" class=" flex flex-row gap-3 items-center hover:bg-purple-300 hover:text-white py-1 px-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-md font-batik text-white font-medium">User</span>
                        </a>
                    </li>
                    <li class="nav-item menu-items ">
                        <a href="{{ route('mahasiswa.index') }}" class=" flex flex-row gap-3 items-center hover:bg-purple-300 hover:text-white py-1 px-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                                <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                                <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                                <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                            </svg>
                            <span class="text-md font-batik text-white font-medium">Mahasiswa</span>
                        </a>
                    </li>
                    <li class="nav-item menu-items">
                        <a href="{{ route('logout') }}" class="flex flex-row gap-3 items-center hover:bg-purple-300 hover:text-white py-1 px-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-6 h-6 text-white"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                <path fill="currentColor" d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                            </svg>
                            <span class="text-md font-batik text-white font-medium">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</body>

</html>
<script>
    // sidebar toggleMenu
    function toggleMenu() {
        var sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
    }

    // sidebar toogleContent
    function toggleContent(id, arrowIconId) {
        var content = document.getElementById(id);
        var arrowIcon = document.getElementById(arrowIconId);

        if (content.style.display === 'none' || content.style.display === '') {
            content.style.display = 'block';
            arrowIcon.classList.add('rotate-180');
        } else {
            content.style.display = 'none';
            arrowIcon.classList.remove('rotate-180');
        }
    }

    // sidebar scroll
    document.addEventListener('DOMContentLoaded', function() {
        const nav = document.querySelector('nav');
        nav.style.height = '100vh';
        nav.style.overflowY = 'auto';
    });


    let navOpen = false; // Menyimpan status navigasi terbuka atau tidak

    function toggleNav() {
        const nav = document.getElementById('nav');

        if (!navOpen) {
            nav.classList.remove('hidden');
            navOpen = true;
        } else {
            nav.classList.add('hidden');
            navOpen = false;
        }
    }

    // Optional: Menutup navigasi saat ukuran layar berubah (misal dari mobile ke desktop)
    window.addEventListener('resize', function() {
        const nav = document.getElementById('nav');
        const menuButton = document.getElementById('menu-button');

        if (window.innerWidth >= 768) {
            nav.classList.remove('hidden');
            navOpen = true;
        } else {
            nav.classList.add('hidden');
            navOpen = false;
        }
    });
</script>