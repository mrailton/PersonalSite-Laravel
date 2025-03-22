<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use JsonException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    #[Test]
    public function login_page_renders(): void
    {
        $this->get(route('auth.login'))
            ->assertOk()
            ->assertSee('Sign in to your account')
            ->assertViewIs('auth.login');
    }

    /**
     * @throws JsonException
     */
    #[Test]
    public function user_can_login(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'))
            ->assertSessionHasNoErrors();
    }

    #[Test]
    public function user_can_not_login_with_incorrect_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
