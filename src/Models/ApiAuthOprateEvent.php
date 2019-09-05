<?php


namespace ArcherZdip\LaravelApiAuth\Models;


use Illuminate\Database\Eloquent\Model;

class ApiAuthOprateEvent extends Model
{
    protected static $nameRegex = '/^[a-z0-9-]{1,255}$/';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_client_id',
        'ip_address',
        'event',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * ApiAuthOprateEvent constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('apikey.table_name.api_auth_oprate_events'));
    }
}