<?php

define('APP_PATH', __DIR__.'/');

define('APP_DEBUG', true);

require(APP_PATH.'marriagnet/Marriagnet.php');

$config = require(APP_PATH.'config/config.php');

(new Marriagnet($config))->run();
