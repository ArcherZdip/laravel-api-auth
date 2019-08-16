<?php

namespace ArcherZdip\LaravelApiAuth\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppClient extends Model
{
    use SoftDeletes;

    const EVENT_NAME_CREATED     = 'created';
    const EVENT_NAME_ACTIVATED   = 'activated';
    const EVENT_NAME_DEACTIVATED = 'deactivated';
    const EVENT_NAME_DELETED     = 'deleted';

    /** @var int activate status */
    const ACTIVATE = 1;
    const DEACTIVATE = 0;

    protected static $nameRegex = '/^[a-z0-9-]{1,255}$/';

    /** @var string $table */
    protected $table = 'app_clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'appid',
        'secret',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function boot()
    {
        parent::boot();

        static::created(function(AppClient $appClient) {
            self::logApiAuthOprateEvent($appClient, self::EVENT_NAME_CREATED);
        });

        static::updated(function(AppClient $appClient) {

            $changed = $appClient->getDirty();

            if (isset($changed) && $changed['active'] === self::ACTIVATE) {
                self::logApiAuthOprateEvent($appClient, self::EVENT_NAME_ACTIVATED);
            }

            if (isset($changed) && $changed['active'] === self::DEACTIVATE) {
                self::logApiAuthOprateEvent($appClient, self::EVENT_NAME_DEACTIVATED);
            }

        });

        static::deleted(function(AppClient $appClient) {
            self::logApiAuthOprateEvent($appClient, self::EVENT_NAME_DELETED);
        });

    }

    /**
     * Get secret by appid
     *
     * @param $appid
     * @return mixed
     */
    public static function getSecretByAppId($appid)
    {
        return self::where([
            'active' => self::ACTIVATE,
            'appid'  => $appid
        ])->first();
    }

    /**
     * Generate AppId
     *
     * @return string
     */
    public static function generateAppId()
    {
        do {
            $appId = Str::random(config('apikey.appid_length', 16));
        } while (self::appIdExists($appId));

        return $appId;
    }

    /**
     * Generate secret
     *
     * @return string
     */
    public static function generateSecret()
    {
        return Str::random(config('apikey.secret_length', 64));
    }

    /**
     * Check name is valid format
     *
     * @param $name
     * @return bool
     */
    public static function isValidName($name)
    {
        return (bool)preg_match(self::$nameRegex, $name);
    }

    /**
     * Check name is or not exists
     *
     * @param $name
     * @return bool
     */
    public static function nameExists($name)
    {
        return self::where('name', '=', $name)->first() instanceof self;
    }

    /**
     * Check AppId is or not exists
     *
     * @param $secret
     * @return bool
     */
    public static function appIdExists($appId)
    {
        return self::where('appid', $appId)->withTrashed()->first() instanceof self;
    }

    /**
     * Log an app oprate admin event
     *
     * @param AppClient $appClient
     * @param string $eventName
     */
    protected static function logApiAuthOprateEvent(AppClient $appClient, $eventName)
    {
        $event = new ApiAuthOprateEvent();
        $event->app_client_id = $appClient->id;
        $event->ip_address = request()->ip();
        $event->event = $eventName;
        $event->save();
    }
}