<?php
/**
 * Listagem das chaves
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

$list = KeyDB::search('ORDER BY id');

API::send_success('key_list', [ 'list' => $list ]);