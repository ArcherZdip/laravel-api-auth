<?php


namespace ArcherZdip\LaravelApiAuth;

use Exception;
use Carbon\CarbonImmutable;
use ArcherZdip\LaravelApiAuth\Models\AppClient;

class ApiAuth
{
    /**
     * @var string $token
     */
    protected $token;

    /**
     * @var array $validKeys
     */
    protected $validKeys = ['appid', 'token', 'exp'];

    public function __construct(string $token)
    {
        $this->token = $token;

        $this->verifySign();
    }

    /**
     * Check token is valid
     *
     * @param string $token
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
     * Get Appid by token
     *
     * @param string $token
     * @return string
     */
    public static function getAppId(string $token): string
    {
        return explode('.', base64_urlsafe_decode($token))[0] ?? '';
    }

    /**
     * Generate token, for test token is valid
     *
     * @param string $appid
     * @param int|null $exp
     * @return string
     * @throws Exception
     */
    public static function generateToken(string $appid, int $exp = null): string
    {
        if ($exp === null) {
            $exp = time();
        }

        $appClient = AppClient::getSecretByAppId($appid);
        if (!$appClient) {
            throw new Exception("The AppId is not exists");
        }
        $secret = $appClient->secret;
        $sign = sha1($appid . $secret . $exp);

        return base64_urlsafe_encode(implode('.', [$appid, $sign, $exp]));
    }

    /**
     * Check appid
     *
     * @param string $appid
     * @return AppClient $appClient
     * @throws Exception
     */
    protected function checkAppId(string $appid): ?AppClient
    {
        $appClient = AppClient::getSecretByAppId($appid);
        if (!$appClient) {
            throw new Exception("The AppId is not exists");
        }

        return $appClient;
    }

    /**
     * verify sign
     *
     * @return null
     * @throws Exception
     */
    protected function verifySign()
    {
        [$appid, $sign, $exp] = $this->tokenDecode($this->token);

        // check timeout
        $now = CarbonImmutable::now()->timestamp;
        $tokenTimeout = (int)config('apikey.token_timeout', 0);
        if ($tokenTimeout !== 0 && $exp + $tokenTimeout > $now) {
            throw new Exception('Token is timeout.');
        }

        if ($this->createSign($appid, $exp) !== $sign) {
            throw new Exception('Token is error.');
        }

        return null;
    }

    /**
     * create sign
     *
     * @param string $appid
     * @param int $exp
     * @return string
     * @throws Exception
     */
    protected function createSign(string $appid, int $exp): string
    {
        $secret = $this->checkAppId($appid)->secret;
        return sha1($appid . $secret . (string)$exp);
    }

    /**
     * Token decode
     *
     * @param string $token
     * @return array|null
     * @throws Exception
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