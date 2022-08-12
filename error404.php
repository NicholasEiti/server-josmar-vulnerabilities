<?php 
/***
 * Pagina Erro 404
*/

require_once "./library.php";

API::send_error([
    'msg' => 'Page not found'
]);
