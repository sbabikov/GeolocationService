<?php

namespace GeolocationService;

use \ActiveRecord\Model;

class Geolocation extends Model
{
    static $table_name = 'geolocation';
    static $primary_key = 'ip';
    static $connection = 'production';
    static $db = 'test';
}
