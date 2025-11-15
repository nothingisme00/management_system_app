<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            // Dark mode initialization
            if (localStorage.getItem('darkMode') === 'false') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="antialiased">
        {{ $slot }}

        <script>
            function toggleDarkMode() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');

                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('darkMode', 'false');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('darkMode', 'true');
                }

                updateThemeIcon();
            }

            function updateThemeIcon() {
                const darkIcon = document.getElementById('theme-toggle-dark-icon');
                const lightIcon = document.getElementById('theme-toggle-light-icon');
                const isDark = document.documentElement.classList.contains('dark');

                if (isDark) {
                    if (darkIcon) darkIcon.classList.remove('hidden');
                    if (lightIcon) lightIcon.classList.add('hidden');
                } else {
                    if (darkIcon) darkIcon.classList.add('hidden');
                    if (lightIcon) lightIcon.classList.remove('hidden');
                }
            }

            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('password-eye-icon');
                const eyeSlashIcon = document.getElementById('password-eye-slash-icon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                }
            }

            // Initialize theme icon on page load
            document.addEventListener('DOMContentLoaded', updateThemeIcon);

            // CRITICAL: Disable bfcache completely
            window.addEventListener('unload', function() {
                // This event listener disables bfcache in all browsers
            });

            // Detect if loaded from bfcache and force reload
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    // Add timestamp to force fresh load from server
                    window.location.href = window.location.pathname + '?_t=' + Date.now();
                }
            });

            // Clear form on load
            window.addEventListener('load', function() {
                const form = document.querySelector('form');
                if (form) {
                    form.reset();
                }
            });
        </script>
    </body>
</html>
