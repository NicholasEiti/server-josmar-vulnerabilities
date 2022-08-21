<?php
/**
 * Listagem das chaves
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken(ADMIN_MIN_LEVEL);

$list = KeyDB::search('ORDER BY id');

API::send_success('key_list', [ 'list' => $list ]);