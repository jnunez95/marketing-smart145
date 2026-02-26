<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPostmarkWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('API_KEY');
        $expectedKey = config('services.postmark.webhook_api_key');

        if (!$expectedKey || $apiKey !== $expectedKey) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
