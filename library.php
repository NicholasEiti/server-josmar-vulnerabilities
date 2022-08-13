<?php

define('__ROOT__', dirname(__FILE__));

function errorHandler($err_no, $err_str, $err_file, $err_line) {
    API::send_error($err_str, [
        'level' => $err_no,
        'filename' => $err_file,
        'line' => $err_line
    ]);
}

set_error_handler('errorHandler', E_ALL | E_STRICT);

require_once __ROOT__ . "/library/api.class.php";
require_once __ROOT__ . "/library/database.class.php";
require_once __ROOT__ . "/library/params.class.php";

$configs = (object) parse_ini_file(__ROOT__ . "/library/configurations.ini");

$GLOBAL_PDO = DBRequest::startPDO(
    $configs->db_name,
    $configs->db_host,
    $configs->db_charset,
    $configs->db_login,
    $configs->db_password
);
