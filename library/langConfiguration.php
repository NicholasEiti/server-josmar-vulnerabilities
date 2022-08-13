<?php
/**
 * File to config language
 */

$lang = DEFAULT_LANG;

if (isset($_GET['lang'])) {
    if (preg_match('/^[A-Za-z\-]*$/', $_GET['lang']) and file_exists(__ROOT__ . "/languages/lang.{$_GET['lang']}.php"))
        $lang = $_GET['lang'];
    else {
        require_once __ROOT__ . "/languages/lang.$lang.php";
        require_once __ROOT__ . "/library/api.class.php";

        API::send_error('lang_unexpected');
    }
}