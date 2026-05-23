{extends file='layout/main.tpl'}

{block name='title'}404 — Страница не найдена{/block}

{block name='content'}
    <div class="error-page">
        <span class="error-page__code">404</span>
        <h1 class="error-page__title">Страница не найдена</h1>
        <a href="/" class="btn btn--primary">На главную</a>
    </div>
{/block}