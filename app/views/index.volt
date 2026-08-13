<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }}</title>
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>

    <div class="app-layout">
        {{ partial('partials/sidebar') }}

        <div class="app-content">
            <main class="container">
                {{ flash.output() }}
                {{ content() }}
            </main>
        </div>
    </div>

</body>
</html>
