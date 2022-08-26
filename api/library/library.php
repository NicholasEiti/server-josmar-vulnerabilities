<?php
/**
 * File to config and import the josmar-API
 */

define('__ROOT__', dirname(dirname(__FILE__)));

define('DEFAULT_LANG', 'en-us');
define('DEFAULT_TIMEZONE', 'America/Sao_Paulo');

$configs = (object) parse_ini_file(__ROOT__ . "/library/configurations.ini");

require_once __ROOT__ . "/library/errorHandler.php";
require_once __ROOT__ . "/library/langConfiguration.php";
require_once __ROOT__ . "/languages/lang.$lang.php";
require_once __ROOT__ . "/library/api.class.php";
require_once __ROOT__ . "/library/database.class.php";
require_once __ROOT__ . "/library/params.class.php";
require_once __ROOT__ . "/../library/josmarWT.class.php";

$GLOBAL_PDO = startPDO(
    $configs->db_name,
    $configs->db_host,
    $configs->db_charset,
    $configs->db_login,
    $configs->db_password
);
