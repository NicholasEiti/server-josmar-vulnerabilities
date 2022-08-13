<?php
/**
 * Listagem das chaves
 * /key/list
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

API::send_success('Success to generate list.', [
    'list' => KeyDB::search('ORDER BY id')
]);
