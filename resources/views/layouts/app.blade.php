<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/imgs/logos/logoquinielas.png">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <!-- Slim Select (buscador en selects de equipos) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slim-select@2.8.1/dist/slimselect.min.css" />
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <script src="https://cdn.jsdelivr.net/npm/slim-select@2.8.1/dist/slimselect.min.js"></script>
        <script>
            function initTeamSelects() {
                document.querySelectorAll('select.team-select').forEach((el) => {
                    if (el.dataset.enhanced) return;

                    new SlimSelect({
                        select: el,
                        settings: {
                            showSearch: true,
                            searchPlaceholder: 'Buscar equipo...',
                        },
                    });

                    el.dataset.enhanced = '1';
                });
            }

            // Ejecutar al cargar la página
            initTeamSelects();

            // Reaplicar después de actualizaciones de Livewire si está disponible
            if (window.Livewire && Livewire.hook) {
                Livewire.hook('message.processed', () => {
                    initTeamSelects();
                });
            }
        </script>
    </body>
</html>
