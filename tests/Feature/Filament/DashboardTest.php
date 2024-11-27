<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;

test('the dashboard renders for an authenticated user', function (): void {
    $response = actingAs(User::factory()->create())->get('/admin');

    expect($response)
        ->status()->toBe(200)
        ->content()
        ->toContain('Mark Railton')
        ->toContain('Dashboard');
});
