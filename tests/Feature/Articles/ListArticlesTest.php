<?php

declare(strict_types=1);

use App\Models\Article;

use function Pest\Laravel\get;

test('a visitor can view a list of published articles', function (): void {
    $publishedArticles = Article::factory()->published()->count(4)->create();
    $notPublishedArticles = Article::factory()->notPublished()->count(2)->create();

    $response = get(route('articles.list'));

    expect($response)
        ->status()->toBe(200)
        ->content()->toContain('Blog Articles');

    $publishedArticles->each(
        fn ($article) => expect($response->content())->toContain($article->title)
    );

    $notPublishedArticles->each(
        fn ($article) => expect($response->content())->not()->toContain($article->title)
    );

    expect($response->content())
        ->not()->toContain('Newer')
        ->not()->toContain('Older');
});

test('pagination is not shown for fewer than 10 published articles', function (): void {
    Article::factory()->published()->count(4)->create();

    $response = get(route('articles.list'));

    expect($response)
        ->status()->toBe(200)
        ->content()
        ->toContain('Blog Articles')
        ->not()->toContain('Newer')
        ->not()->toContain('Older');
});

test('pagination is shown for more than 10 published articles', function (): void {
    Article::factory()->published()->count(12)->create();

    $response = get(route('articles.list'));

    expect($response)
        ->status()->toBe(200)
        ->content()
        ->toContain('Blog Articles')
        ->toContain('Newer')
        ->toContain('Older');
});
