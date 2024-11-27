<?php

declare(strict_types=1);

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('a list of articles is rendered', function (): void {
    $articles = Article::factory()->count(3)->create();

    $response = actingAs(User::factory()->create())->get('/admin/articles');

    expect($response)
        ->status()->toBe(200);

    $articles->each(
        fn ($article) => expect($response->content())->toContain($article->title)
    );
});
