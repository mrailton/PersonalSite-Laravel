<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use App\Models\Article;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowArticleTest extends TestCase
{
    #[Test]
    public function visitor_can_view_a_published_article(): void
    {
        $article = Article::factory()->published()->create();

        $response = $this->get(route('articles.show', ['article' => $article]));

        $response->assertStatus(200)
            ->assertSee($article->title)
            ->assertSee($article->published_at->format('jS F Y'));
    }


    #[Test]
    public function guest_cannot_view_an_unpublished_article(): void
    {
        $article = Article::factory()->notPublished()->create();

        $response = $this->get(route('articles.show', ['article' => $article]));

        $response->assertStatus(404)
            ->assertDontSee($article->title);
    }
}
