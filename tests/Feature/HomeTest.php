<?php

declare(strict_types=1);

test('the homepage loads', function (): void {
    $response = $this->get(route('index'));

    $response->assertStatus(200)->assertSee('Mark Railton')->assertViewIs('index');
});
