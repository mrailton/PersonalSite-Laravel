<?php

declare(strict_types=1);

namespace Articles;

use App\Models\Article;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    #[Test]
    public function a_visitor_can_view_a_list_of_published_articles(): void
    {
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
    }

    #[Test]
    public function pagination_options_only_show_where_there_are_more_than_10_published_articles(): void
    {
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
    }
}
