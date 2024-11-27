<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('the homepage loads', function (): void {
    $response = get(route('index'));

    expect($response)
        ->status()->toBe(200)
        ->content()->toContain('Mark Railton')
        ->assertViewIs('index');
});
