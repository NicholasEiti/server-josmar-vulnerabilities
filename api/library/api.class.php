<?php
/**
 * API class - send responses
 */

class API {
    const STATUS_ERROR = 1;
    const STATUS_SUCCESS = 0;

    static private function _send($data): never
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    static function send_error_without_code(string $msg, array $error_info = []): never
    {
        static::_send([
            'error'    => $error_info,
            'code_msg' => $msg,
            'code'     => 'without_error',
            'status'   => self::STATUS_ERROR
        ]);
    }

    static function send_error(string $code, array $error_info = [], array $params = null) {
        global $api_error_msgs;

        $data = [
            'code'      => $code,
            'code_msg'  => $api_error_msgs[$code],
            'status'    => self::STATUS_ERROR
        ];

        if ($params !== null) $data['code_msg'] = $data['code_msg'](...$params);

        static::_send(array_merge($data, $error_info));
    }

    static function send_success(string $code, array $success_info = [], array $params = null): never
    {
        global $api_success_msgs;

        $data = [
            'code'      => $code,
            'code_msg'  => $api_success_msgs[$code],
            'status'    => self::STATUS_SUCCESS
        ];

        if ($params !== null)
            $data['code_msg'] = $data['code_msg'](...$params);

        static::_send(array_merge($data, $success_info));
    }
}
