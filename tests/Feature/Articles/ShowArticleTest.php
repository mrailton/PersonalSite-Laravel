<?php

declare(strict_types=1);

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('a visitor can view a published article', function (): void {
    $article = Article::factory()->published()->create();

    $response = get(route('articles.show', ['article' => $article]));

    expect($response)
        ->status()->toBe(200)
        ->content()
        ->toContain($article->title)
        ->toContain($article->published_at->format('jS F Y'));
});

test('a guest can not view a article that has not been published', function (): void {
    $article = Article::factory()->notPublished()->create();

    $response = get(route('articles.show', ['article' => $article]));

    expect($response)
        ->status()->toBe(404)
        ->content()->not()->toContain($article->title);
});

test('a visitor can not view an article that does not exist', function (): void {
    $response = get(route('articles.show', ['article' => 'does-not-exist']));

    expect($response)
        ->status()->toBe(404);
});
