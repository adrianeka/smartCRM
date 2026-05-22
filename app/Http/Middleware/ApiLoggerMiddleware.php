<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiLog;

class ApiLoggerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
         dd('middleware jalan');
        $response = $next($request);

        try {
            ApiLog::create([
                'user_id' => Auth::id(),
                'method' => $request->method(),
                'endpoint' => $request->path(),
                'status_code' => $response->getStatusCode(),
                'ip_address' => $request->ip(),
                'request_body' => json_encode($request->all()),
                'response_body' => $response->getContent(),
                'error_message' => $response->getStatusCode() >= 400
                    ? $response->getContent()
                    : null,
            ]);
        } catch (\Exception $e) {
            logger()->error('API Log Failed: ' . $e->getMessage());
        }

        return $response;
    }
}
