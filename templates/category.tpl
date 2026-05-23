{extends file='layout/main.tpl'}

{block name='title'}{$category.title} — Блог{/block}

{block name='content'}

    <section class="page">
        <h1 class="page-title">{$category.title}</h1>
        {if $category.description}
            <p class="page-desc">{$category.description}</p>
        {/if}
    </section>

    <div class="toolbar">
        <span class="toolbar-label">Сортировка:</span>
        <div class="toolbar-sort">
            <a href="?sort=date"
               class="btn btn--sm {if $sort === 'date'}btn--active{else}btn--outline{/if}">
                По дате
            </a>
            <a href="?sort=views"
               class="btn btn--sm {if $sort === 'views'}btn--active{else}btn--outline{/if}">
                По просмотрам
            </a>
        </div>
    </div>

    {if $articles}
        <div class="articles-grid">
            {foreach $articles as $article}
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
                                {$article.public_at|date_format:"%d.%m.%Y"}
                            </span>
                            <span class="card-views">
                                {$article.views} просмотров
                            </span>
                        </div>
                    </div>
                </article>
            {/foreach}
        </div>

        <nav class="pagination">
            {if $currentPage > 1}
                <a href="?sort={$sort}&page={$currentPage-1}" class="pagination__btn">&larr;</a>
            {/if}

            {section name=p loop=$totalPages}
                <a href="?sort={$sort}&page={$smarty.section.p.index+1}"
                   class="pagination-btn {if $smarty.section.p.index+1 === $currentPage}pagination-btn--active{/if}">
                    {$smarty.section.p.index+1}
                </a>
            {/section}

            {if $currentPage < $totalPages}
                <a href="?sort={$sort}&page={$currentPage+1}" class="pagination-btn">&rarr;</a>
            {/if}
        </nav>

    {else}
        <p class="empty">В этой категории пока нет статей.</p>
    {/if}

{/block}