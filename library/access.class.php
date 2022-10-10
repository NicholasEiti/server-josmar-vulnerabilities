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
        $this->logged = null;
        $this->jwtInstance = null;
        $this->session = null;
    }

    function isLogged(): bool
    {
        if ($this->logged !== null)
            return $this->logged;

        if ($this->token === null) {
            $this->logged = false;
            return false;
        }

        $this->jwtInstance = JosmarWT::fromToken($this->token);

        if ($this->jwtInstance === false or !$this->jwtInstance->verify())
        {
            $this->logged = false;
            return false;
        }

        $this->session = $this->jwtInstance->payload;
        $this->logged = true;

        return true;
    }

    function loggedPage(string $url_if_not=MAIN_LOGOUT)
    {
        if (!$this->isLogged()) {
            header("Location: $url_if_not");
            exit;
        }

        return true;
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