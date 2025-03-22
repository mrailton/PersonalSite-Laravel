<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\Articles;

use App\Models\Article;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    #[Test]
    public function a_user_can_see_a_list_of_articles(): void
    {
        $articles = Article::factory()->count(5)->create();
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('admin.articles.list'));

        $response->assertStatus(200)
            ->assertSee($articles[0]->title);
    }
}
