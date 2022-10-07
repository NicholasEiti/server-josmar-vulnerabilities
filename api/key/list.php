<?php
/**
 * Listagem das chaves
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$key_drawer = Params::getListOfIntsParam('drawer', true);
$quant = Params::getIntParam('quant', optional: true);

$queries = [];
$params = [];

if ($key_drawer !== null) {
    $queries = ['`drawer` IN (?' . str_repeat(', ?', count($key_drawer) - 1) . ')'];
    $params = $key_drawer;
}

$dynamicSearch = KeyDB::dynamicListSearch($queries, $params, '`keys`.`id`', $quant);

API::send_success('key_list', $dynamicSearch);
