<?php

define('APP_PATH', __DIR__.'/');

define('APP_DEBUG', true);

require(APP_PATH.'marriagnet/Marriagnet.php');
require(APP_PATH.'lib/firebase/jwt/JWT.php');

$config = require(APP_PATH.'config/config.php');

$GLOBALS['secretKey'] = $config['secretKey'];

//echo JWT::encode();

(new Marriagnet($config))->run();
