<?php
/**
 * Classe para criação do html
*/

class Fabric
{
    static $defaultStyleFiles = [
        "https://fonts.googleapis.com/css2?family=Roboto&display=swap",
        "https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,700,1,-25"
    ];
    static $defaultScriptFiles = [
        "/static/josmar-api.js"
    ];

    static function generateHead(string $title, array $scriptFiles = [], array $styleFiles = [], string $lang = DEFAULT_LANGUAGE)
    {
        $styleFiles = array_merge(static::$defaultStyleFiles, $styleFiles);
        $scriptFiles = array_merge(static::$defaultScriptFiles, $scriptFiles);

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

    static function generateLoggedInStart(Access $access)
    {
        ?><body>
<ul class="navbar">
    <li class="navbar-item"><a href="/drawers/">Armários</a></li>
    <li class="navbar-item"><a href="/keys/">Chaves</a></li>
    <li class="navbar-item"><a href="/requests/">Pedidos de chave</a></li>
    <li class="navbar-item"><a href="/users/">Usuário</a></li>
    <li class="navbar-item-right">
        <a href="#logout" onclick="return doLogout(this);">
            <span>Logout</span>
            <span class="material-symbols-rounded">logout</span>
        </a>
    </li>
</ul>
<div class="container">
    <div id="msg-error" class="msg-error"></div>
<?php
    }

    static function generateLogoutStart()
    {
        ?><body>
            <div class="container">
                <div id="msg-error" class="msg-error"></div>
        <?php
    }

    static function generateFooter()
    {
        ?></div>
        </body>
        </html><?php
    }

    static function generateListBlock(string $tag) {
        $quant = isset($_GET['quant']) ? (int) $_GET['quant'] : null;
        $quant = 0 < $quant ? $quant : null;

        $page = isset($_GET['page']) ? (int) $_GET['page'] : null;
        $page = 0 < $page ? $page : null;

        ?><list-block tag="<?= $tag ?>"<?=$quant !== null ? " quant=\"$quant\"" : "" ?><?=$page !== null ? " page=\"$page\"" : "" ?>></list-block><?php
    }

    static function generateShowBlock(string $tag) {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $id = 0 < $id ? $id : null;

        ?><show-block tag="<?= $tag ?>" id="<?= $id ?>"></show-block><?php
    }

    static function generateAddForm(string $title, array $inputs) {
        ?>
        <form-add-block class='add-form-block'>
            <h1><?= $title ?></h1>
            <div class='add-form-block-content'>
                <?php
                foreach ($inputs as $input_name => $input_info) {
                    static::generateInput($input_name, $input_info);
                }
                ?>
            </div>
            <div class='add-form-block-submit'>
                <input type='button' value='Cancelar' class='add-form-block-submit-cancel' onclick='javascript: window.location.href = "/drawers/";'>
                <input type='submit' value='Adicionar' class='add-form-block-submit-add'>
            </div>
        </form-add-block>
        <?php
    }

    static function generateInput(string $input_name, array $input_info) {
        ?>
        <div class='input-block-element'>
            <label for='<?= $input_name ?>'><?= $input_info['label'] ?>:</label>
            <input name='<?= $input_name ?>' id='<?= $input_name ?>' placeholder='<?= $input_info['label'] ?>' class='input-block-input'></input_name>
        </div>
        <?php
    }
}