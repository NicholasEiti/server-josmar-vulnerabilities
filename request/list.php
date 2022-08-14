<?php
/**
 * Listagem dos pedidos
 * /request/list
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

$list = RequestDB::search('ORDER BY id');

API::send_success('request_list', [ 'list' => $list ]);