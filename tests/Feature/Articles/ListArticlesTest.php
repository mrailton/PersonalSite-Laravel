<?php

declare(strict_types=1);

use App\Models\Article;

test('a visitor can view a list of published articles', function (): void {
    $publishedArticles = Article::factory()->published()->count(4)->create();
    $notPublishedArticles = Article::factory()->notPublished()->count(2)->create();

    $this->get(route('articles.list'))
        ->assertStatus(200)
        ->assertSee('Blog Articles')
        ->assertSee($publishedArticles[0]->title)
        ->assertSee($publishedArticles[2]->title)
        ->assertDontSee($notPublishedArticles[0]->title)
        ->assertDontSee($notPublishedArticles[1]->title)
        ->assertDontSee('Newer')
        ->assertDontSee('Older');
});

test('pagination options only show where there are more than 10 published articles', function (): void {
    Article::factory()->published()->count(4)->create();

    $this->get(route('articles.list'))
        ->assertStatus(200)
        ->assertSee('Blog Articles')
        ->assertDontSee('Newer')
        ->assertDontSee('Older');

    Article::factory()->published()->count(8)->create();

    $this->get(route('articles.list'))
        ->assertStatus(200)
        ->assertSee('Blog Articles')
        ->assertSee('Newer')
        ->assertSee('Older');
});
