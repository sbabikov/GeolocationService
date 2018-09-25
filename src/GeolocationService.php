<?php

namespace GeolocationService;

use GeolocationService\Geolocation;

/**
* GeolocationService class
* @author Sergii Babikov
*/
class GeolocationService
{   
    private $geolocationApiClient;
    
    /**
    * Constructor
    */
    public function __construct(GeolocationApiClient $geolocationApiClient)
    {
        $this->geolocationApiClient = $geolocationApiClient;
    }
    
    /**
    * Get geolocation information by IP
    * 
    * @param string $ip
    */
    public function get(string $ip)
    {
        if (!$this->isIp($ip)) {
            return '';
        }
        
        $geolocationInfo = $this->getGeolocationFromDb($ip);
        
        if (!empty($geolocationInfo)) {
            return $geolocationInfo;
        }
        
        $geolocationInfo = $this->getGeolocationFromApi($ip);
    
        $this->saveGeolocationInfoInDb($ip, $geolocationInfo);
        
        return $geolocationInfo;
    }
    
    /**
    * Get geolocation information from DB by IP
    * 
    * @param string $ip
    * @return string
    */
    private function getGeolocationFromDb(string $ip)
    {
        $geolocation = Geolocation::find_by_ip(sprintf("%u", ip2long($ip)));

        return empty($geolocation) ? '' : $geolocation->information;
    }
    
    /**
    * Get geolocation information from API by IP
    * 
    * @param string $ip
    * @return string
    */
    private function getGeolocationFromApi(string $ip)
    {
        return $this->geolocationApiClient->get($ip);
    }
    
    /**
    * Save geolocation information in DB
    * 
    * @param string $ip
    * @param string $geolocationInfo
    * @return void
    */
    private function saveGeolocationInfoInDb(string $ip, string $geolocationInfo)
    {
        $geolocation = new Geolocation();
        
        $geolocation->ip = sprintf("%u", ip2long($ip));
        $geolocation->information = $geolocationInfo;
        
        $geolocation->save();
    }
    
    /**
    * Validate IP address
    * 
    * @param string $string
    * @return boolean
    */
    private function isIp(string $string) {
        return filter_var($string, FILTER_VALIDATE_IP);
    }
    
    /**
    * Get current visitor IP address
    * @return string
    */
    public function getVisitorIp()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if($this->isIp((string) $client)) {
            $ip = $client;
        } elseif($this->isIp((string) $forward)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }
}
