<?php

class API {
    static private function _send($data): never
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    static function send_error(string $msg, array $error_info = []): never
    {
        $error_info['msg'] = $msg;

        static::_send([
            'error' => $error_info,
            'status' => 'error'
        ]);
    }

    static function send_success(string $msg, array $success_info = []): never
    {
        $success_info['msg'] = $msg;

        static::_send([
            'success' => $success_info,
            'status' => 'success'
        ]);
    }
}