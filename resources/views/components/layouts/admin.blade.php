@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - Nalar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-nalar-bg text-sm text-nalar-text antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[232px_minmax(0,1fr)]">
        <x-admin.sidebar />

        <div class="flex min-w-0 flex-col">
            <x-admin.topbar :title="$title" />
            <main class="flex-1 px-5 py-6 sm:px-7 lg:px-9 lg:py-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        (function () {
            var sidebar = document.getElementById('admin-sidebar');
            var backdrop = document.getElementById('sidebar-backdrop');
            var toggle = document.getElementById('sidebar-toggle');

            function setSidebar(open) {
                sidebar.classList.toggle('-translate-x-full', !open);
                backdrop.classList.toggle('hidden', !open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                document.body.classList.toggle('overflow-hidden', open && window.innerWidth < 1024);
            }
            toggle.addEventListener('click', function () { setSidebar(true); });
            backdrop.addEventListener('click', function () { setSidebar(false); });
            document.getElementById('sidebar-close').addEventListener('click', function () { setSidebar(false); });

            var menuBtn = document.getElementById('profile-button');
            var menu = document.getElementById('profile-menu');
            function setMenu(open) {
                menu.classList.toggle('hidden', !open);
                menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            }
            menuBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                setMenu(menu.classList.contains('hidden'));
            });
            document.addEventListener('click', function (e) {
                if (!menu.contains(e.target)) setMenu(false);
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { setSidebar(false); setMenu(false); }
            });
        })();
    </script>
</body>
</html>