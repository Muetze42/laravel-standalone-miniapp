<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'App by Norman Huth') }}</title>
    <style>
        body {
            font-family: verdana, serif;
            max-width: 1200px;
            margin: 1rem auto;
        }
    </style>
</head>
<body>
@yield('content')
</body>
</html>
