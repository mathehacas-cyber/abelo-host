{extends file='layout/main.tpl'}

{block name='title'}500 — Ошибка сервера{/block}

{block name='content'}
    <div class="error-page">
        <span class="error-page__code">500</span>
        <h1 class="error-page__title">Что-то пошло не так</h1>
        <p class="error-page__text">{$message}</p>
        <a href="/" class="btn btn--primary">На главную</a>
    </div>
{/block}