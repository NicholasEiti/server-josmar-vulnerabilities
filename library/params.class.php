<?php
/**
 * Classe para leitura de parâmetros
 */
class Params
{
    static function getParam($param_name, $min_length = 0, $max_length = 255, $method='_GET'): string
    {
        global $$method;

        if (!isset($$method[$param_name])) 
            API::send_error('param_not_found', params: [$param_name]);

        $param = trim(($$method)[$param_name]);

        $name_len = strlen($param);

        if ($name_len > $max_length)
            API::send_error('param_less_than', params: [$param_name, $max_length]);

        if ($name_len < $min_length)
            API::send_error('param_more_than', params: [$param_name, $min_length]);

        return $param;
    }

    static function getIntParam($param_name, $method='_GET'): int
    {
        global $$method;

        if (!isset($$method[$param_name])) 
            API::send_error('param_not_found', params: [$param_name]);

        $param = trim(($$method)[$param_name]);

        if (!preg_match('/^[0-9]*$/', $param))
            API::send_error('param_as_int', params: [$param_name]);

        return (int) $param;
    }
}