<?php

declare(strict_types=1);

namespace App\View\Components\Layout;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Guest extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View
    {
        return view('components.layout.guest');
    }
}
