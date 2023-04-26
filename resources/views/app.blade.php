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
</body>
</html>
