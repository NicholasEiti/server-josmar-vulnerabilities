<?php
/**
 * File to handler errors
 */

function errorHandler($err_no, $err_str, $err_file, $err_line) {
    global $configs;

    if ($configs->on_development)
        API::send_error_without_code($err_str, [
            'level' => $err_no,
            'filename' => $err_file,
            'line' => $err_line
        ]);
}

set_error_handler('errorHandler', E_ALL | E_STRICT);
