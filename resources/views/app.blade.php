<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliotheka</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<div id="app">
    <app-header></app-header>
    <router-view></router-view>
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
