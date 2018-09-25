<?php

namespace GeolocationService;

/**
* GeolocationApiClient class
* @author Sergii Babikov
*/
class GeolocationApiClient
{
    const API_URL = 'ipinfo.io/{ip}/geo';
    
    /**
    * Get geolocation information by IP
    * 
    * @param string $ip
    */
    public function get(string $ip)
    {
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, str_replace('{ip}', $ip, $this::API_URL)); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);
        
        return $output;
    }
}
