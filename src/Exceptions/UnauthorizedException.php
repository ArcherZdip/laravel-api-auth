<?php

namespace ArcherZdip\LaravelApiAuth\Exceptions;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class UnauthorizedException.
 */
class UnauthorizedException extends HttpException
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_UNAUTHORIZED;

    /**
     * @var string
     */
    protected $message = 'Unauthorized';

    public function __construct(string $message = null, \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        if (!$message) {
            $message = $this->message;
        }

        parent::__construct($this->statusCode, $message, $previous, $headers, $code);
    }
}
