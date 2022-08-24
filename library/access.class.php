<?php
/**
 * Classe para controle de acesso
*/

class Access
{
    const TOKEN_COOKIE_NAME = 'api_token';

    function __construct()
    {
        $this->token = $_COOKIE[static::TOKEN_COOKIE_NAME] ?? null;
    }

    function loggedPage(string $url_if_not=MAIN_LOGOUT)
    {
        if ($this->token === null) {
            header("Location: $url_if_not");
            exit;
        }

        $this->jwtInstance = JosmarWT::fromToken($this->token);

        if ($this->jwtInstance === false or !$this->jwtInstance->verify())
        {
            header("Location: $url_if_not");
            exit;
        }

        $this->session = $this->jwtInstance->payload;
    }

    function notLoggedPage(string $url_if_not=MAIN_LOGGED_IN): void
    {
        if ($this->token !== null) {
            $this->jwtInstance = JosmarWT::fromToken($this->token);

            if ($this->jwtInstance !== false and $this->jwtInstance->verify()) {
                header("Location: $url_if_not");
                exit;
            }

            unset($_COOKIE[static::TOKEN_COOKIE_NAME]);
            setcookie(static::TOKEN_COOKIE_NAME, '', time() - 3600, '/');
        }
    }

    function userLevelIsBiggerThan($minLevel): bool
    {
        return $this->session['level'] >= $minLevel;
    }
}