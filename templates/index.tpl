{extends file='layout/main.tpl'}

{block name='title'}Главная — Блог{/block}

{block name='content'}
    <section>
        <div>
            <h1>Это мой блог</h1>
            <p>Статьи по категориям, свежие публикации и полезные материалы</p>
        </div>
    </section>

    {foreach $categories as $category}
    <section class="category">
        <div class="category-header">
            <div>
                <h2 class="category-title">
                    <a href="/category/{$category.slug}">{$category.title}</a>
                </h2>
                {if $category.description}
                    <p class="category-desc">{$category.description}</p>
                {/if}
            </div>
            <a href="/category/{$category.slug}" class="btn btn--outline">
                Все статьи
            </a>
        </div>

        <div class="articles-grid">
            {foreach $category.articles as $article}
                <article class="card">
                    {if $article.image}
                        <a href="/article/{$article.slug}" class="card-image-content">
                            <img src="/assets/img/{$article.image}" alt="{$article.title}" class="card-image">
                        </a>
                    {/if}
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="/article/{$article.slug}">{$article.title}</a>
                        </h3>
                        <p class="card-desc">{$article.description}</p>
                        <div class="card-meta">
                            <span class="card-date">
                                {$article.published_at|date_format:"%d.%m.%Y"}
                            </span>
                            <span class="card-views">
                                {$article.views} просмотров
                            </span>
                        </div>
                    </div>
                </article>
            {/foreach}
        </div>
    </section>
{/foreach}
{/block}