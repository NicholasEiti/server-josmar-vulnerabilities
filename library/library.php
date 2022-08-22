<?php

define('MAIN_LOGOUT', '/');
define('MAIN_LOGGED_IN', '/home');

/**
 * Gerenciador simples acesso
*/
class Access
{
    static function mustBeLoggedIn(string $url=MAIN_LOGOUT): void
    {
        if (!isset($_COOKIE['api_token'])) {
            header("Location: $url");
            exit;
        }
    }

    static function mustBeLogout(string $url=MAIN_LOGGED_IN): void
    {
        if (isset($_COOKIE['api_token'])) {
            header("Location: $url");
            exit;
        }
    }
}

// ---

define('DEFAULT_LANGUAGE', 'pt-br');

class Fabric
{
    static function generateHead(string $title, array $scriptFiles = [], array $styleFiles = [], string $lang = DEFAULT_LANGUAGE)
    {
        $styleFiles[] = "https://fonts.googleapis.com/css2?family=Roboto&display=swap";
        $scriptFiles[] = "/static/josmar-api.js";

        ?><!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php foreach ($styleFiles as $styleFile): ?><link rel="stylesheet" href="<?= $styleFile ?>"><?php endforeach ?>
    <?php foreach ($scriptFiles as $scriptFile): ?><script src="<?=$scriptFile?>"></script><?php endforeach ?>
    <title><?= $title !== null ? "$title - " : "" ?>AdminJosmar</title>
</head><?php
    }

    static function generateLoggedInStart(int $level)
    {
        ?><body>
<ul class="navbar">
    <li class="navbar-item"><a href="/drawers/">Drawers</a></li>
    <li class="navbar-item"><a href="#keys">Keys</a></li>
    <li class="navbar-item"><a href="#requests">Requests</a></li>
    <li class="navbar-item"><a href="#users">Users</a></li>
    <li class="navbar-item-right"><a href="#logout" onclick="return logout(this);">Logout</a></li>
</ul>
<div class="container"></div>
<?php
    }

    static function generateLogoutStart()
    {
        echo "<body>";
    }

    static function generateFooter()
    {
        echo "</body></html>";
    }
}