<?php

namespace ArcherZdip\LaravelApiAuth\Console\Commands;

use ArcherZdip\LaravelApiAuth\Models\AppClient;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class PutAppAuth extends Command
{
    /**
     * Error message.
     */
    const MESSAGE_ERROR_PARAMS_MANY = 'The put params is to many. There can only be one condition';
    const MESSAGE_ERROR_APPID_DOES_NOT_EXIST = 'AppId does not exist.';
    const MESSAGE_ERROR_APP_TRASHED = 'This app has trashed';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "apikey:put {appid}
                            {--A|activate : Activate an App by appid}
                            {--F|deactivate : Deactivate an App by appid}
                            {--D|delete : Delete an App by appid}
                            {--R|refresh : refresh an app's secret by appid}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change an AppId status';

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
     * @throws \Exception
     *
     * @return mixed
     */
    public function handle()
    {
        $appid = $this->argument('appid');
        /** @var AppClient $appClient */
        $appClient = $this->validateAppId($appid);
        $name = $appClient->name;

        $options = $this->options();
        $options = Arr::where($options, function ($option) {
            if ($option) {
                return $option;
            }
        });

        // too many params
        if (count($options) > 1) {
            $this->error(self::MESSAGE_ERROR_PARAMS_MANY);
            die();
        }

        // is trashed
        if ($appClient->trashed()) {
            $this->error(self::MESSAGE_ERROR_APP_TRASHED);
            die();
        }

        if (Arr::has($options, 'activate')) {
            // activate app
            if ($appClient->active) {
                $this->info('App "'.$name.'" is already active');
                die();
            }
            $appClient->active = AppClient::ACTIVATE;
            $appClient->save();
            $this->info("Activate app succ, name: {$name}");
        } elseif (Arr::has($options, 'deactivate')) {
            // deactivate app
            if (!$appClient->active) {
                $this->info('App "'.$name.'" is already deactivate');
                die();
            }
            $appClient->active = AppClient::DEACTIVATE;
            $appClient->save();
            $this->info("Deactivate app succ, name: {$name}");
        } elseif (Arr::has($options, 'delete')) {
            // delete app
            $confirmMessage = "Are you sure you want to delete AppId:{$appid}, name:{$name} ?";

            if ($this->confirm($confirmMessage)) {
                $appClient->delete();
                $this->info('Deleted app succ, name: '.$name);
            }
            die();
        } elseif (Arr::has($options, 'refresh')) {
            // refresh this app secret
            $confirmMessage = "Are you sure you want to refresh this app secret, AppId:{$appid}, name:{$name} ?";

            if ($this->confirm($confirmMessage)) {
                $appClient->secret = AppClient::generateSecret();
                $appClient->save();
                $this->info('Refresh app secret succ, name: '.$name);
            } else {
                die();
            }
        }

        // get this record detail
        $headers = ['AppName', 'AppId', 'Secret', 'Status', 'CreateAt'];
        $status = $appClient->active ? 'active' : 'deactivated';
        $status = $appClient->trashed() ? 'deleted' : $status;

        $rows = [[
            $appClient->name,
            $appClient->appid,
            $appClient->secret,
            $status,
            $appClient->created_at,
        ]];

        $this->table($headers, $rows);
    }

    /**
     * Validate AppId.
     *
     * @param $appId
     *
     * @return mixed
     */
    protected function validateAppId(string $appId): AppClient
    {
        if (!AppClient::appIdExists($appId)) {
            $this->error(self::MESSAGE_ERROR_APPID_DOES_NOT_EXIST);
            die();
        }

        return AppClient::withTrashed()->where('appid', '=', $appId)->first();
    }
}
