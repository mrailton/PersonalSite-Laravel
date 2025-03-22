<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    #[Test]
    public function user_can_view_dashboard(): void
    {
        $this->actingAs(new User());

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200)
            ->assertViewIs('admin.dashboard')
            ->assertSee('Dashboard');
    }

    #[Test]
    public function guest_can_not_view_dashboard(): void
    {
        $this->assertGuest();

        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('auth.login'))
            ->assertDontSee('Dashboard');
    }
}
