<?php

use Illuminate\Support\Facades\RateLimiter;

test('the api-internal rate limiter is registered at 300 requests per minute per ip', function () {
    $limiter = RateLimiter::limiter('api-internal');

    expect($limiter)->not->toBeNull();

    $request = Illuminate\Http\Request::create('/api/users', 'GET', server: ['REMOTE_ADDR' => '127.0.0.1']);
    $limit = $limiter($request);

    expect($limit->maxAttempts)->toBe(300)
        ->and($limit->decaySeconds)->toBe(60);
});
