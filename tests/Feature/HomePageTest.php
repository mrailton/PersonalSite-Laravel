<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    #[Test]
    public function homepage_loads(): void
    {
        $response = $this->get(route('index'));

        $response->assertStatus(200)
            ->assertSee('Mark Railton')
            ->assertViewIs('index');
    }
}
