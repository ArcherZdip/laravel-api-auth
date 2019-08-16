<?php

namespace ArcherZdip\LaravelApiAuth;

use ArcherZdip\LaravelApiAuth\Models\AppClient;
use Exception;

class ApiAuth
{
    /**
     * @var
     */
    protected $token;

    /**
     * @var array
     */
    protected $validKeys = ['appid', 'token', 'exp'];

    public function __construct(string $token)
    {
        $this->token = $token;

        $this->verifySign();
    }

    /**
     * Check token is valid.
     *
     * @param string $token
     *
     * @return bool
     */
    public static function isValid(string $token): bool
    {
        try {
            new static($token);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Get Appid by token.
     *
     * @param string $token
     *
     * @return string
     */
    public static function getAppId(string $token): string
    {
        return explode('.', base64_urlsafe_decode($token))[0] ?? '';
    }

    /**
     * Generate token, for test token is valid.
     *
     * @param string   $appid
     * @param int|null $exp
     *
     * @throws Exception
     *
     * @return string
     */
    public static function generateToken(string $appid, int $exp = null): string
    {
        if ($exp === null) {
            $exp = time();
        }

        $appClient = AppClient::getSecretByAppId($appid);
        if ($appClient->count() === 0) {
            throw new Exception('The AppId is not exists');
        }
        $secret = $appClient->secret;
        $sign = sha1($appid.$secret.$exp);

        return base64_urlsafe_encode(implode('.', [$appid, $sign, $exp]));
    }

    /**
     * Check appid.
     *
     * @param string $appid
     *
     * @throws Exception
     *
     * @return AppClient $appClient
     */
    protected function checkAppId(string $appid): ?AppClient
    {
        $appClient = AppClient::getSecretByAppId($appid);
        if ($appClient->count() === 0) {
            throw new Exception('The AppId is not exists');
        }

        return $appClient;
    }

    /**
     * verify sign.
     *
     * @throws Exception
     *
     * @return null
     */
    protected function verifySign()
    {
        [$appid, $sign, $exp] = $this->tokenDecode($this->token);

        if ($this->createSign($appid, $exp) !== $sign) {
            throw new Exception('Token is error.');
        }
    }

    /**
     * create sign.
     *
     * @param string $appid
     * @param int    $exp
     *
     * @throws Exception
     *
     * @return string
     */
    protected function createSign(string $appid, int $exp): string
    {
        $secret = $this->checkAppId($appid)->secret;

        return sha1($appid.$secret.$exp);
    }

    /**
     * Token decode.
     *
     * @param string $token
     *
     * @throws Exception
     *
     * @return array|null
     */
    protected function tokenDecode(string $token): ?array
    {
        $data = explode('.', base64_urlsafe_decode($token));
        if (count($this->validKeys) !== count($data)) {
            throw new Exception('token invalid');
        }

        return $data;
    }
}
