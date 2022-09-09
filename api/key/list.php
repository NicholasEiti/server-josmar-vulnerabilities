<?php
/**
 * Listagem das chaves
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$key_drawer = Params::getListOfIntsParam('drawer', true);

$queries = [];
$params = [];

if ($key_drawer !== null) {
    $list = KeyDB::search('WHERE `drawer` IN (?' . str_repeat(', ?', count($key_drawer) - 1) . ') ORDER BY id', $key_drawer);
} else
    $list = KeyDB::search('ORDER BY id');

API::send_success('key_list', [ 'list' => $list, 'count' => count($list) ]);
