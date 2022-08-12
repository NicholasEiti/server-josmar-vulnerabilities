<?php

class API {
    static private function _send($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    static function send_error($error_info) {
        static::_send([
            'error' => $error_info,
            'status' => 'error'
        ]);
    }
}