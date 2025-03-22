<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use App\Models\Article;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    #[Test]
    public function visitor_can_view_list_of_published_articles(): void
    {
        $publishedArticles = Article::factory()->published()->count(4)->create();
        $notPublishedArticles = Article::factory()->notPublished()->count(2)->create();

        $response = $this->get(route('articles.list'));

        $response->assertStatus(200)
            ->assertSee('Blog Articles');

        foreach ($publishedArticles as $article) {
            $response->assertSee($article->title);
        }

        foreach ($notPublishedArticles as $article) {
            $response->assertDontSee($article->title);
        }

        $response->assertDontSee('Newer')
            ->assertDontSee('Older');
    }

    #[Test]
    public function pagination_is_not_shown_for_fewer_than_10_published_articles(): void
    {
        Article::factory()->published()->count(4)->create();

        $response = $this->get(route('articles.list'));

        $response->assertStatus(200)
            ->assertSee('Blog Articles')
            ->assertDontSee('Newer')
            ->assertDontSee('Older');
    }

    #[Test]
    public function pagination_is_shown_for_more_than_10_published_articles(): void
    {
        Article::factory()->published()->count(12)->create();

        $response = $this->get(route('articles.list'));

        $response->assertStatus(200)
            ->assertSee('Blog Articles')
            ->assertSee('Newer')
            ->assertSee('Older');
    }
}
