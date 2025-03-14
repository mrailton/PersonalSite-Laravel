<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="shortcut icon" href="/img/favicon/favicon.ico">
    <link rel="me" href="https://phpc.social/@markrailton">

    @production
        <script defer src="https://cloud.umami.is/script.js" data-website-id="24aaadbb-aa9c-409c-a56d-0ed2415bb121"></script>
    @endproduction

    <title>Mark Railton</title>

    {{ $head ?? '' }}
</head>
<body>
<x-header />
<main class="py-12">
    {{ $slot }}
</main>
</body>
</html>
