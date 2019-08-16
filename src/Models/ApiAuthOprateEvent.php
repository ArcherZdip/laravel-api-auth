<?php


namespace ArcherZdip\LaravelApiAuth\Models;


use Illuminate\Database\Eloquent\Model;

class ApiAuthOprateEvent extends Model
{
    protected static $nameRegex = '/^[a-z0-9-]{1,255}$/';

    /** @var string $table */
    protected $table = 'api_auth_oprate_events';

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
}