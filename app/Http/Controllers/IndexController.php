<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request): View
    {
        $articles = [];

        return view('index', ['articles' => $articles]);
    }
}
