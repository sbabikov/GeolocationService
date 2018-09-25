<?php

ActiveRecord\Config::initialize(function($cfg)
{
   $cfg->set_model_directory(__DIR__ . '/../src');
   $cfg->set_connections(
     array(
       'production' => 'mysql://test_user:secret@localhost/test'
     )
   );
});