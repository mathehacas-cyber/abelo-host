<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{block name='title'}Блог{/block}</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>

<header class="header">
    <div class="container">
        <a href="/" class="logo">Блог</a>
        <nav class="nav">
            <a href="/">Главная</a>
        </nav>
    </div>
</header>

<main class="main">
    <div class="container">
        {block name='content'}{/block}
    </div>
</main>

<footer class="footer">
    <div class="container">

    </div>
</footer>

</body>
</html>