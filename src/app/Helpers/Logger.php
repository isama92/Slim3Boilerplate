<?php
/*
|--------------------------------------------------------------------------
| Logger
|--------------------------------------------------------------------------
|
| Logger
|
*/

namespace App\Helpers;

use App\Models\Log;
use App\Models\IpCache;


class Logger
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function log($event, $details = null)
    {
        $log = new Log();

        $ip = $this->getIp();
        $geoInfo = $this->getGeoIpInfo($ip);
        $browserInfo = $this->getBrowserinfo();

        $log->fill([
            'event' => $event,
            'details' => $details,
            'ip' => $geoInfo->ip,
            'continent_code' => $geoInfo->continent_code,
            'country_code' => $geoInfo->country_code,
            'region_code' => $geoInfo->region_code,
            'zip' => $geoInfo->zip,
            'city' => $geoInfo->city,
            'latitude' => $geoInfo->latitude,
            'longitude' => $geoInfo->longitude,
            'http_user_agent' => $browserInfo->userAgent,
            'browser_name' => $browserInfo->name,
            'browser_version' => $browserInfo->version,
            'os' => $browserInfo->platform,
        ]);

        if($this->container->auth->check()) {
            $user = $this->container->auth->user();
            $log->user()->associate($user);
        }

        $log->save();
    }

    private function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    private function getBrowserinfo()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";
        $ub = null;

        // PLATFORM
        if (preg_match('/linux/i', $u_agent))
            $platform = 'linux';
        elseif (preg_match('/macintosh|mac os x/i', $u_agent))
            $platform = 'mac';
        elseif (preg_match('/windows|win32/i', $u_agent))
            $platform = 'windows';

        // BROWSER NAME
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // BROWSER VERSION
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

        if(!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        $i = count($matches['browser']);
        if($i != 1)
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub))
                $version = $matches['version'][0];
            else
                $version = $matches['version'][1];
        else
            $version = $matches['version'][0];

        // check if we have a number
        if ($version == null || $version == '')
            $version = '?';

        $res = new \stdClass();
        $res->userAgent = $u_agent;
        $res->name = $bname;
        $res->version = $version;
        $res->platform = $platform;
        $res->pattern = $pattern;
        return $res;
    }

    private function getGeoIpInfo($ip)
    {
        $api_key = $this->container->settings['ipstack']['API_KEY'];
        $data = false;

        if($ip) {
            $cache = IpCache::where('ip', $ip)->first();
            if($cache) {
                $expires_date = new \DateTime($cache->expires_date);
                $now = new \DateTime();
                if($expires_date->diff($now)->days > 7)
                    $cache->delete();
                else
                    $data = $cache;
            }

            if(!$data) {
                $data = json_decode(file_get_contents('http://api.ipstack.com/' . $ip . '?access_key=' . $api_key));
                $cache = new IpCache;
                $cache->fill([
                    'ip' => $data->ip,
                    'continent_code' => $data->continent_code,
                    'country_code' => $data->country_code,
                    'region_code' => $data->region_code,
                    'zip' => $data->zip,
                    'city' => $data->city,
                    'latitude' => $data->latitude,
                    'longitude' => $data->longitude,
                    'expires_date' => date('Y-m-d H:i:s'),
                ]);
                $cache->save();
            }
        }

        return $data;
    }
}
