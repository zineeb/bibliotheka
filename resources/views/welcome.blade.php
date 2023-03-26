<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Vue App</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <app-header></app-header>
    <home></home>
    <app-footer></app-footer>
</div>

<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>



