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
        $start = microtime(true);
        $token = $request->header(self::AUTH_HEADER) ?? '';
        $urlToken = $request->input('token');
        $token = $urlToken ?: $token;

        if (ApiAuth::isValid($token)) {
            $response = $next($request);
            $this->logAccessEvent($request, $token, $start);

            return $response;
        }
        throw new UnauthorizedException();
    }

    /**
     * Log an API KEY access event
     *
     * @param Request $request
     * @param string $token
     * @param $startTime
     */
    protected function logAccessEvent(Request $request, string $token, $startTime)
    {
        // Log access event
        if (config('apikey.logger.is_taken')) {
            $appid = ApiAuth::getAppId($token);

            $attributes = [
                'appid'         => $appid,
                'ip_address'    => $request->ip(),
                'url'           => $request->fullUrl(),
                'params'        => $request->all(),
                'response_time' => (microtime(true) - $startTime) * 1000 . 'ms',
                'type'          => $request->method(),
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
