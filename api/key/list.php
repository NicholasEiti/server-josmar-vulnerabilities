<?php
/**
 * Listagem das chaves
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$list = KeyDB::search('ORDER BY id');

API::send_success('key_list', [ 'list' => $list, 'count' => count($list) ]);