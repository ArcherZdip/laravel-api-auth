<?php


namespace ArcherZdip\LaravelApiAuth\Test;


use Faker\Factory;
use ArcherZdip\LaravelApiAuth\ApiAuth;
use Illuminate\Support\Facades\Artisan;
use ArcherZdip\LaravelApiAuth\Models\AppClient;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var AppClient $appClient
     */
    protected $appClient;

    protected function setUp()
    {
        parent::setUp();

        $this->name = Factory::create()->md5;

        Artisan::call('apikey:generate', [
            'name' => $this->name
        ]);

        $this->appClient = AppClient::where('name', '=', $this->name)->first();
    }

    /**
     * test genereate token
     *
     * @throws \Exception
     */
    public function test_generate_token()
    {
        $token = ApiAuth::generateToken($this->appClient->appid, time());

        echo $token;
        $this->assertInternalType('string', $token);
    }

    /**
     * test get appid
     *
     * @throws \Exception
     */
    public function test_get_appid()
    {
        $appid = ApiAuth::getAppId($this->getGenerateToken());

        echo $appid;

        $this->assertInternalType('string', $appid);
    }

    /**
     * test token is valid
     *
     * @throws \Exception
     */
    public function test_token_is_valid()
    {
        $succToken = $this->getGenerateToken();
        $failToken = '123456';

        echo $succToken;

        $this->assertTrue(ApiAuth::isValid($succToken));

        $this->assertFalse(ApiAuth::isValid($failToken));
    }

    /**
     * Get token for test
     *
     * @return string
     * @throws \Exception
     */
    private function getGenerateToken()
    {
        return ApiAuth::generateToken($this->appClient->appid, time());
    }
}