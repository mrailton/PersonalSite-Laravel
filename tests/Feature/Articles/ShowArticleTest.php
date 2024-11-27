<?php

declare(strict_types=1);

use App\Models\Article;
use App\Models\User;

test('a visitor can view a published article', function (): void {
    $article = Article::factory()->published()->create();

    $this->get(route('articles.show', ['article' => $article]))
        ->assertStatus(200)
        ->assertSee($article->title)
        ->assertSee($article->published_at->format('jS F Y'));
});

test('a guest can not view a article that has not been published', function (): void {
    $article = Article::factory()->notPublished()->create();

    $this->get(route('articles.show', ['article' => $article]))
        ->assertStatus(404)
        ->assertDontSee($article->title);
});

test('an authenticated visitor can view a article that has not been published', function (): void {
    $article = Article::factory()->notPublished()->create();

    $this->actingAs(User::factory()->create())->get(route('articles.show', ['article' => $article]))
        ->assertStatus(200)
        ->assertSee($article->title);
});

test('a visitor can not view an article that does not exist', function (): void {
    $this->get(route('articles.show', ['article' => 'does-not-exist']))
        ->assertStatus(404);
});
