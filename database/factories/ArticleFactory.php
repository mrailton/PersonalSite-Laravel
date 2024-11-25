<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(5, true),
            'content' => $this->faker->paragraphs(5, true),
            'published_at' => $this->faker->dateTimeBetween('-3 years', '+6 months'),
        ];
    }

    public function published(): self
    {
        return $this->afterCreating(function (Article $article): void {
            $article->update(['published_at' => $this->faker->dateTimeBetween('-3 years', '-1 hour')]);
        });
    }

    public function notPublished(): self
    {
        return $this->afterCreating(function (Article $article): void {
            $article->update(['published_at' => null]);
        });
    }
}
