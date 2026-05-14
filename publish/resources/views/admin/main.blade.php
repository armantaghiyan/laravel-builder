<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel + Vue 3 + TypeScript</title>

    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body>
<div id="app"></div>
</body>
</html>
