<?php

define('__ROOT__', dirname(__FILE__));

function errorHandler($err_no, $err_str, $err_file, $err_line) {
    API::send_error([
        'level' => $err_no,
        'msg' => $err_str,
        'filename' => $err_file,
        'line' => $err_line
    ]);
}

set_error_handler('errorHandler', E_ALL | E_STRICT);

require_once __ROOT__ . "/api.class.php";
require_once __ROOT__ . "/database.class.php";

$configs = (object) parse_ini_file(__ROOT__ . "/configurations.ini");

$GLOBAL_PDO = DBRequest::startPDO(
    $configs->db_name,
    $configs->db_host,
    $configs->db_charset,
    $configs->db_login,
    $configs->db_password
);
