<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ArticleListCard extends Component
{
    public function __construct(public Article $article)
    {
    }

    public function render(): View
    {
        return view('components.article-list-card');
    }
}
