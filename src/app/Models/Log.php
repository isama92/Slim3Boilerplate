<?php
/*
|--------------------------------------------------------------------------
| Log
|--------------------------------------------------------------------------
|
| Log model
|
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Log extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'created_at',
        'event',
        'details',
        'user_id',
        'ip',
        'continent_code',
        'country_code',
        'region_code',
        'zip',
        'city',
        'latitude',
        'longitude',
        'http_user_agent',
        'browser_name',
        'browser_version',
        'os',
    ];

    public function user()
    {
        return $this->belongsTo('\App\Models\User')->withTrashed();
    }


}
