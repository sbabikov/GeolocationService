<?php

ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/config.php';

use GeolocationService\GeolocationService;
use GeolocationService\GeolocationApiClient;
use GeolocationService\Geolocation;

$app = new Silex\Application();

$geolocationService = new GeolocationService(
    new GeolocationApiClient()
);
    
$app->get('/geolocation/{ip}', function($ip) use($app, $geolocationService) {
    return $geolocationService->get($ip);
});

$app->get('/', function() use($app, $geolocationService) {
    $visitorIp = $geolocationService->getVisitorIp();
    
    ob_start();
    require_once __DIR__.'/../tpl/home.php';
    
    return ob_get_clean();
});

$app->run();
