<?php

/**
 * Classe para leitura de parâmetros
*/
class Params
{
    static function getParams($param_name, $min_length = 0, $max_length = 255, $method='_GET'): string
    {
        global $$method;

        if (!isset($$method[$param_name])) 
            API::send_error("Expected \"$param_name\" param.");

        $param = trim(($$method)[$param_name]);

        $name_len = strlen($param);

        if ($name_len > $max_length)
            API::send_error("\"$param_name\" parameter must be less than $max_length in length.");

        if ($name_len < $min_length)
            API::send_error("\"$param_name\" parameter must be more than $min_length in length.");

        return $param;
    }
}