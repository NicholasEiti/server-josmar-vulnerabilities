<?php
/**
 * Listagem do armário
 * /drawer/list
*/

require_once "../library.php";

$list = DBRequest::search('drawers', 'ORDER BY id');

API::send_success('Success to generate list.', [
    'list' => $list
]);
