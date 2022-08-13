<?php
/**
 * API class - send responses
 */
class API {
    static private function _send($data): never
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    static function send_error_without_code(string $msg, array $error_info = []): never
    {
        $error_info['msg'] = $msg;

        static::_send([
            'error' => $error_info,
            'status' => 'error'
        ]);
    }

    static function send_error(string $code, array $error_info = [], array $params = null) {
        global $api_error_msgs;

        $error_info['code'] = $code;

        if ($params !== null)
            $error_info['msg'] = $api_error_msgs[$code](...$params);
        else
            $error_info['msg'] = $api_error_msgs[$code];

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

    static function requestMethodMustBe(string|array $methods) {
        if (
            is_array($methods) ?
            !in_array($_SERVER['REQUEST_METHOD'], $methods) :
            $_SERVER['REQUEST_METHOD'] !== $methods
        )
            static::send_error("unexpected_request_method");
    }
}
