<?php
/**
 * Classe para o JWT
 */

define('JWT_KEY', 'jwt_password');

class JosmarWT {
    static $default_header = [
       'alg' => 'HS256',
       'typ' => 'JWT'
    ];

    public ?array $payload = null;
    public ?array $header = null;
    public ?string $expire_after = null;
    public ?string $signature = null;
    public ?int $session_id = null;

    static function fromToken($token): JosmarWT|false
    {
        [
            $header,
            $payload,
            $signature
        ] = explode(".", $token);

        $header = static::_json_base64_decode($header);

        if ($header === false)
            return false;

        $payload = static::_json_base64_decode($payload);

        if ($payload === false)
            return false;

        $jwtInstance = new JosmarWT($payload);

        $jwtInstance->header = $header;
        $jwtInstance->signature = $signature;

        return $jwtInstance;
    }

    function __construct(array $payload, string $expire_after='PT1H')
    {
        $this->payload = $payload;
        $this->expire_after = $expire_after;
        $this->session_id = 10;
        $this->header = static::$default_header;
    }

    private static function _json_base64_encode(array $value): false|string
    {
        $value = json_encode($value);

        if ($value === false)
            return false;

        $value = base64_encode($value);

        return $value;
    }

    private static function _json_base64_decode(string $value): array|false
    {
        $value = base64_decode($value);

        if ($value === false)
            return false;
        
        $value = json_decode($value, true);

        if ($value === null)
            return false;

        return $value;
    }

    private static function _generate_time_expire(string $expire_after): int
    {
        $expire = new DateTime(timezone: new DateTimeZone(DEFAULT_TIMEZONE));
        $expire->add(new DateInterval($expire_after));

        return $expire->getTimestamp();
    }

    private static function _generate_signature(string $algo, string $header, string $payload): string
    {
        $signature = hash_hmac($algo, "$header.$payload", JWT_KEY, true);
        $signature = base64_encode($signature);
        return $signature;
    }

    function generate_token(): string
    {        
        $expire = static::_generate_time_expire($this->expire_after);

        $this->payload = array_merge($this->payload, [
            'exp' => $expire
        ]);

        $header = static::_json_base64_encode($this->header);

        if ($header === false)
            return false;

        $payload = static::_json_base64_encode($this->payload);

        if ($payload === false)
            return false;

        $this->signature = static::_generate_signature('sha256', $header, $payload);

        return "$header.$payload.$this->signature";
    }

    function verify(): bool {
        if ($this->payload === null or (isset($this->payload['exp']) and $this->payload['exp'] < strtotime('now')))
            return false;

        $header = static::_json_base64_encode($this->header);

        if ($header === false)
            return false;

        $payload = static::_json_base64_encode($this->payload);

        if ($payload === false)
            return false;

        $signature = static::_generate_signature('sha256', $header, $payload);

        return $signature == $this->signature;
    }
}
