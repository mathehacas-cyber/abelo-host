{extends file='layout/main.tpl'}

{block name='title'}{$article.title} — Блог{/block}

{block name='content'}

    <article class="article">

        {if $article.image}
            <div class="article-cover">
                <img src="/assets/img/{$article.image}" alt="{$article.title}">
            </div>
        {/if}

        <div class="article-header">
            <div class="article-categories">
                {foreach $article.categories as $cat}
                    <a href="/category/{$cat.slug}" class="tag">{$cat.title}</a>
                {/foreach}
            </div>
            <h1 class="article-title">{$article.title}</h1>
            <div class="article-meta">
                <span>{$article.public_at|date_format:"%d.%m.%Y"}</span>
                <span>{$article.views} просмотров</span>
            </div>
        </div>

        <div class="article-desc">
            {$article.description}
        </div>

        <div class="article-content">
            {$article.content nofilter}
        </div>

    </article>

    {if $related}
        <section class="related">
            <h2 class="related-title">Похожие статьи</h2>
            <div class="articles-grid articles-grid--3">
                {foreach $related as $item}
                    <article class="card">
                        {if $item.image}
                            <a href="/article/{$item.slug}" class="card-image-wrap">
                                <img src="/assets/img/{$item.image}" alt="{$item.title}" class="card-image">
                            </a>
                        {/if}
                        <div class="card-body">
                            <h3 class="card-title">
                                <a href="/article/{$item.slug}">{$item.title}</a>
                            </h3>
                            <p class="card-desc">{$item.description}</p>
                            <div class="card-meta">
                                <span class="card-date">
                                    {$item.public_at|date_format:"%d.%m.%Y"}
                                </span>
                                <span class="card-views">
                                    {$item.views} просмотров
                                </span>
                            </div>
                        </div>
                    </article>
                {/foreach}
            </div>
        </section>
    {/if}

{/block}