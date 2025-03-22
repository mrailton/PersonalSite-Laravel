<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    #[Test]
    public function user_can_logout(): void
    {
        $this->actingAs(User::factory()->create());

        $this->assertAuthenticated();

        $response = $this->post(route('auth.logout'));

        $response->assertStatus(302)
            ->assertRedirect(route('index'));

        $this->assertGuest();
    }
}
