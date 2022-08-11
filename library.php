<?php

define('__ROOT__', dirname(__FILE__));

set_error_handler(function ($err_no, $err_str, $err_file, $err_line) {
    header('Content-Type: application/json');
    echo json_encode([
        'error' => [
            'level' => $err_no,
            'msg' => $err_str,
            'filename' => $err_file,
            'line' => $err_line
        ]
    ]);
    exit;
});

require_once __ROOT__ . "/database.class.php";

$configs = (object) parse_ini_file(__ROOT__ . "/configurations.ini");

$GLOBAL_PDO = DBRequest::startPDO(
    $configs->db_name,
    $configs->db_host,
    $configs->db_charset,
    $configs->db_login,
    $configs->db_password
);
