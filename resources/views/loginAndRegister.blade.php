<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliotheka</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <app-header></app-header>
    <router-view></router-view>
    <app-footer></app-footer>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    window.onload = function() {
        const script = document.createElement('script');
        script.src = 'https://www.google.com/recaptcha/api.js';
        script.async = true;
        script.defer = true;
        script.onload = function() {
            document.dispatchEvent(new Event('grecaptchaLoaded'));
        };
        document.head.appendChild(script);
    };
</script>
</body>
</html>
