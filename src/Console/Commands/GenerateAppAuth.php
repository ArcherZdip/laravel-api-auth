<?php

namespace ArcherZdip\LaravelApiAuth\Console\Commands;

use Illuminate\Console\Command;
use ArcherZdip\LaravelApiAuth\Models\AppClient;

class GenerateAppAuth extends Command
{
    /**
     * Error messages
     */
    const MESSAGE_ERROR_INVALID_NAME_FORMAT = 'Invalid name.  Must be a lowercase alphabetic characters, numbers and hyphens less than 255 characters long.';
    const MESSAGE_ERROR_NAME_ALREADY_USED = 'Name is unavailable.';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate a new appId";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->validateName($name);

        $attributes = [
            'name'   => $name,
            'appid'  => AppClient::generateAppId(),
            'secret' => AppClient::generateSecret(),
        ];

        /** @var AppClient $appClient */
        $appClient = AppClient::forceCreate($attributes);
        $headers = ['AppName', 'appId', 'secret', 'CreateAt'];
        $rows = [
            $appClient->name,
            $appClient->appid,
            $appClient->secret,
            $appClient->created_at
        ];
        $this->table($headers, [$rows]);
    }

    /**
     * validate name
     *
     * @param string $name
     * @return null
     */
    protected function validateName(string $name)
    {
        if (!AppClient::isValidName($name)) {
            $this->error(self::MESSAGE_ERROR_INVALID_NAME_FORMAT);
            exit();
        }

        if (AppClient::nameExists($name)) {
            $this->error(self::MESSAGE_ERROR_NAME_ALREADY_USED);
            exit();
        }

        return null;
    }
}
