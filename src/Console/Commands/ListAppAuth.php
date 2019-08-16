<?php

namespace ArcherZdip\LaravelApiAuth\Console\Commands;

use ArcherZdip\LaravelApiAuth\Models\AppClient;
use Illuminate\Console\Command;

class ListAppAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:list {--D|deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "List all AppId and Secret";

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
        $lists = $this->option('deleted')
            ? AppClient::withTrashed()->orderBy('name')->get()
            : AppClient::orderBy('name')->get();

        if ($lists->count() === 0) {
            $this->info('There are no APPID');
            die();
        }

        $headers = ['AppName', 'AppId', 'Secret', 'Status', 'CreateAt'];

        $rows = $lists->map(function ($list) {
            $status = $list->active ? 'active' : 'deactivated';
            $status = $list->trashed() ? 'deleted' : $status;

            return [
                $list->name,
                $list->appid,
                $list->secret,
                $status,
                $list->created_at
            ];
        });

        $this->table($headers, $rows);
    }
}
