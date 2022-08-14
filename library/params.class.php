<?php
/**
 * Classe para leitura de parâmetros
 */
class Params
{
    static function getParam(string $param_name, int $min_length = 0, int $max_length = 255, bool $optional = false, string $method='_GET'): string|null
    {
        global $$method;

        if (!isset($$method[$param_name])) {
            if ($optional)
                return null;
            else
                API::send_error('param_not_found', params: [$param_name]);
        }

        $param = trim(($$method)[$param_name]);

        $name_len = strlen($param);

        if ($name_len > $max_length)
            API::send_error('param_less_than', params: [$param_name, $max_length]);

        if ($name_len < $min_length)
            API::send_error('param_more_than', params: [$param_name, $min_length]);

        return $param;
    }

    static function getIntParam(string $param_name, bool $optional = false, string $method='_GET'): int|null
    {
        global $$method;

        if (!isset($$method[$param_name])) {
            if ($optional)
                return null;
            else
                API::send_error('param_not_found', params: [$param_name]);
        }

        $param = trim(($$method)[$param_name]);

        if (!preg_match('/^[0-9]*$/', $param))
            API::send_error('param_as_int', params: [$param_name]);

        return (int) $param;
    }

    static function getEnumParam(string $param_name, array $enum_values, bool $optional = false, string $method='_GET'): mixed
    {
        global $$method;

        if (!isset($$method[$param_name])) {
            if ($optional)
                return null;
            else
                API::send_error('param_not_found', params: [$param_name]);
        }

        $param = trim(($$method)[$param_name]);

        if (!isset($enum_values[$param]))
            API::send_error('param_enum_not_match', params: [$param_name]);
        
        return $enum_values[$param];
    }

    static function getRegexParam(string $param_name, string $filter, bool $optional = false, string $method='_GET'): string|null
    {
        global $$method;

        if (!isset($$method[$param_name])) {
            if ($optional)
                return null;
            else
                API::send_error('param_not_found', params: [$param_name]);
        }

        $param = trim(($$method)[$param_name]);

        if (!preg_match($filter, $param))
            API::send_error('param_filter_not_match', params: [$param_name]);

        return $param;
    }

    static function getDateParam(string $param_name, string $format, string $timezone=DEFAULT_TIMEZONE, bool $optional = false, string $method='_GET'): DateTime|null
    {
        global $$method;

        if (!isset($$method[$param_name])) {
            if ($optional)
                return null;
            else
                API::send_error('param_not_found', params: [$param_name]);
        }

        $param = trim(($$method)[$param_name]);

        $param = DateTime::createFromFormat($format, $param, new DateTimeZone($timezone));

        if ($param === False)
            API::send_error('param_wrong_format', params: [$param_name, $format]);

        return $param;
    }
}