<?php


namespace ArcherZdip\LaravelApiAuth\Models;


use Illuminate\Database\Eloquent\Model;

class ApiAuthAccessEvent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appid',
        'ip_address',
        'url',
        'params',
        'response_time',
        'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [
        'params' => 'array'
    ];

    /**
     * ApiAuthAccessEvent constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('apikey.table_name.api_auth_access_events'));
    }
}