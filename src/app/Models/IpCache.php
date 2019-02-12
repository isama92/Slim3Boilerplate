<?php
/*
|--------------------------------------------------------------------------
| IpCache
|--------------------------------------------------------------------------
|
| IpCache Model
|
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class IpCache extends Model
{
    protected $table = 'ip_cache';
    public $timestamps = false;

    protected $fillable = [
        'ip',
        'continent_code',
        'country_code',
        'region_code',
        'zip',
        'city',
        'latitude',
        'longitude',
        'expires_date',
    ];
}
