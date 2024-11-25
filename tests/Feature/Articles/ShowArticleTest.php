<?php

declare(strict_types=1);

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowArticleTest extends TestCase
{
    #[Test]
    public function a_visitor_can_view_a_published_article(): void
    {
        $article = Article::factory()->published()->create();

        $this->get(route('articles.show', ['article' => $article]))
            ->assertStatus(200)
            ->assertSee($article->title)
            ->assertSee($article->published_at->format('jS F Y'));
    }

    #[Test]
    public function a_guest_can_not_view_a_article_that_has_not_been_published(): void
    {
        $article = Article::factory()->notPublished()->create();

        $this->get(route('articles.show', ['article' => $article]))
            ->assertStatus(404)
            ->assertDontSee($article->title);
    }

    #[Test]
    public function an_authenticated_visitor_can_view_a_article_that_has_not_been_published(): void
    {
        $article = Article::factory()->notPublished()->create();

        $this->actingAs(User::factory()->create())->get(route('articles.show', ['article' => $article]))
            ->assertStatus(200)
            ->assertSee($article->title);
    }

    #[Test]
    public function a_visitor_can_not_view_an_article_that_does_not_exist(): void
    {
        $this->get(route('articles.show', ['article' => 'does-not-exist']))
            ->assertStatus(404);
    }
}
