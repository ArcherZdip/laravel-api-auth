<?php

namespace ArcherZdip\LaravelApiAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ArcherZdip\LaravelApiAuth\ApiAuth;
use ArcherZdip\LaravelApiAuth\Models\ApiAuthAccessEvent;
use ArcherZdip\LaravelApiAuth\Exceptions\UnauthorizedException;

class AuthorizeApiKeyMiddleware
{
    const AUTH_HEADER = 'Authorization';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header(self::AUTH_HEADER) ?? '';
        $token = explode(' ', $header)[1] ?? null;
        $urlToken = $request->input('token');
        $token = $urlToken ?: $token;

        if (ApiAuth::isValid($token)) {
            $this->logAccessEvent($request, $token);
            return $next($request);
        }
        throw new UnauthorizedException();
    }

    /**
     * Log an API KEY access event
     *
     * @param Request $request
     */
    protected function logAccessEvent(Request $request, string $token)
    {
        // Log access event
        if (config('apikey.logger.is_taken')) {
            $appid = ApiAuth::getAppId($token);

            $attributes = [
                'appid'      => $appid,
                'ip_address' => $request->ip(),
                'url'        => $request->fullUrl(),
                'params'     => $request->segments(),
                'type'       => $request->method(),
            ];

            // database
            if (config('apikey.logger.driver') === 'database') {
                $this->logAccessEventForDB($attributes);
            } elseif (config('apikey.logger.driver') === 'file') {
                $this->logAccessEventForFile($attributes);
            }
        }

    }

    /**
     * Log an access event for DB
     *
     * @param array $attributes
     */
    protected function logAccessEventForDB(array $attributes)
    {
        ApiAuthAccessEvent::forceCreate($attributes);
    }

    /**
     * Log an access event for file
     *
     * @param array $attributes
     */
    protected function logAccessEventForFile(array $attributes)
    {
        $message = '[ApiKey Log] Params: ';
        Log::info($message, $attributes);
    }
}
