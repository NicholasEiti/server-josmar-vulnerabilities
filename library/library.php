<?php

define('__ROOT__', dirname(dirname(__FILE__)));

define('MAIN_LOGOUT', '/');
define('MAIN_LOGGED_IN', '/home');

define('DEFAULT_LANGUAGE', 'pt-br');
define('ADMIN_MIN_LEVEL', 15);

require __ROOT__ . '/library/access.class.php';
require __ROOT__ . '/library/fabric.class.php';
require __ROOT__ . '/library/josmarWT.class.php';
