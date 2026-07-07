<?php

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

test('the api-internal rate limiter is registered at 300 requests per minute per ip', function () {
    $limiter = RateLimiter::limiter('api-internal');

    expect($limiter)->not->toBeNull();

    $request = Request::create('/api/users', 'GET', server: ['REMOTE_ADDR' => '127.0.0.1']);
    $limit = $limiter($request);

    expect($limit->maxAttempts)->toBe(300)
        ->and($limit->decaySeconds)->toBe(60);
});

test('the dev-login rate limiter is registered at 10 requests per minute per ip', function () {
    $limiter = RateLimiter::limiter('dev-login');

    expect($limiter)->not->toBeNull();

    $request = Request::create('/dev-login', 'POST', server: ['REMOTE_ADDR' => '127.0.0.1']);
    $limit = $limiter($request);

    expect($limit->maxAttempts)->toBe(10)
        ->and($limit->decaySeconds)->toBe(60);
});

test('exceeding the dev-login rate limit logs a rate_limit_hit activity entry', function () {
    $request = Request::create('/dev-login', 'POST', server: ['REMOTE_ADDR' => '127.0.0.1']);
    $limit = RateLimiter::limiter('dev-login')($request);

    $response = ($limit->responseCallback)($request, []);

    expect($response->getStatusCode())->toBe(429);

    $log = ActivityLog::query()->where('event', 'rate_limit_hit.dev_login')->first();

    expect($log)->not->toBeNull()
        ->and($log->user_id)->toBeNull();
});
