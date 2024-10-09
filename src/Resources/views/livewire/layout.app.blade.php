<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
        <title>{{ $title ?? 'Page Title' }}</title>
        <style>
            [x-cloak=''],
            [x-cloak='x-cloak'],
            [x-cloak='1'] {
                display: none !important;
            }
            @media (max-width: 1023px) {
                [x-cloak='-lg'] {
                    display: none !important;
                }
            }
            @media (min-width: 1024px) {
                [x-cloak='lg'] {
                    display: none !important;
                }
            }
        </style>
        <link href="http://localhost/css/filament/forms/forms.css?v=3.2.107.0" rel="stylesheet" data-navigate-track/>
        <link href="http://localhost/css/filament/support/support.css?v=3.2.107.0" rel="stylesheet" data-navigate-track/>
        <link href="https://cdn.jsdelivr.net/npm/@event-calendar/build@3.4.0/event-calendar.min.css" rel="stylesheet" data-navigate-track/>  
        <style>
            :root {
                --danger-50:254, 242, 242;  --danger-100:254, 226, 226;  --danger-200:254, 202, 202;  --danger-300:252, 165, 165;  --danger-400:248, 113, 113;  --danger-500:239, 68, 68;  --danger-600:220, 38, 38;  --danger-700:185, 28, 28;  --danger-800:153, 27, 27;  --danger-900:127, 29, 29;  --danger-950:69, 10, 10;  --gray-50:250, 250, 250;  --gray-100:244, 244, 245;  --gray-200:228, 228, 231;  --gray-300:212, 212, 216;  --gray-400:161, 161, 170;  --gray-500:113, 113, 122;  --gray-600:82, 82, 91;  --gray-700:63, 63, 70;  --gray-800:39, 39, 42;  --gray-900:24, 24, 27;  --gray-950:9, 9, 11;  --info-50:239, 246, 255;  --info-100:219, 234, 254;  --info-200:191, 219, 254;  --info-300:147, 197, 253;  --info-400:96, 165, 250;  --info-500:59, 130, 246;  --info-600:37, 99, 235;  --info-700:29, 78, 216;  --info-800:30, 64, 175;  --info-900:30, 58, 138;  --info-950:23, 37, 84;  --primary-50:240, 253, 244;  --primary-100:220, 252, 231;  --primary-200:187, 247, 208;  --primary-300:134, 239, 172;  --primary-400:74, 222, 128;  --primary-500:34, 197, 94;  --primary-600:22, 163, 74;  --primary-700:21, 128, 61;  --primary-800:22, 101, 52;  --primary-900:20, 83, 45;  --primary-950:5, 46, 22;  --success-50:240, 253, 244;  --success-100:220, 252, 231;  --success-200:187, 247, 208;  --success-300:134, 239, 172;  --success-400:74, 222, 128;  --success-500:34, 197, 94;  --success-600:22, 163, 74;  --success-700:21, 128, 61;  --success-800:22, 101, 52;  --success-900:20, 83, 45;  --success-950:5, 46, 22;  --warning-50:255, 251, 235;  --warning-100:254, 243, 199;  --warning-200:253, 230, 138;  --warning-300:252, 211, 77;  --warning-400:251, 191, 36;  --warning-500:245, 158, 11;  --warning-600:217, 119, 6;  --warning-700:180, 83, 9;  --warning-800:146, 64, 14;  --warning-900:120, 53, 15;  --warning-950:69, 26, 3;     }
        </style>
        <link href="http://localhost/css/filament/filament/app.css?v=3.2.107.0" rel="stylesheet" data-navigate-track/>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root {
                --font-family: 'Inter';
                --sidebar-width: 20rem;
                --collapsed-sidebar-width: 4.5rem;
                --default-theme-mode: system;
            }
        </style>
        <script>
            const theme = localStorage.getItem('theme') ?? 'system'
            if (
                theme === 'dark' ||
                (theme === 'system' &&
                    window.matchMedia('(prefers-color-scheme: dark)')
                        .matches)
            ) {
                document.documentElement.classList.add('dark')
            }
        </script>
        <style >[wire\:loading][wire\:loading], [wire\:loading\.delay][wire\:loading\.delay], [wire\:loading\.inline-block][wire\:loading\.inline-block], [wire\:loading\.inline][wire\:loading\.inline], [wire\:loading\.block][wire\:loading\.block], [wire\:loading\.flex][wire\:loading\.flex], [wire\:loading\.table][wire\:loading\.table], [wire\:loading\.grid][wire\:loading\.grid], [wire\:loading\.inline-flex][wire\:loading\.inline-flex] {display: none;}[wire\:loading\.delay\.none][wire\:loading\.delay\.none], [wire\:loading\.delay\.shortest][wire\:loading\.delay\.shortest], [wire\:loading\.delay\.shorter][wire\:loading\.delay\.shorter], [wire\:loading\.delay\.short][wire\:loading\.delay\.short], [wire\:loading\.delay\.default][wire\:loading\.delay\.default], [wire\:loading\.delay\.long][wire\:loading\.delay\.long], [wire\:loading\.delay\.longer][wire\:loading\.delay\.longer], [wire\:loading\.delay\.longest][wire\:loading\.delay\.longest] {display: none;}[wire\:offline][wire\:offline] {display: none;}[wire\:dirty]:not(textarea):not(input):not(select) {display: none;}:root {--livewire-progress-bar-color: #2299dd;}[x-cloak] {display: none !important;}</style>
    </head>
    <body class="fi-body fi-panel-admin min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white">
        {{ $slot }}
    </body>
</html>