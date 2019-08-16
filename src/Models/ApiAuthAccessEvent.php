<?php


namespace ArcherZdip\LaravelApiAuth\Models;


use Illuminate\Database\Eloquent\Model;

class ApiAuthAccessEvent extends Model
{
    /** @var string $table */
    protected $table = 'api_auth_access_events';

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

}