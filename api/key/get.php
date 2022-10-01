<?php
/**
 * Ler um armário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$key_id = Params::getIntParam('id');

$key = KeyDB::searchById($key_id, [
    'drawer' => [ 'name' => 'drawer_name' ],
]);

if ($key === false)
    API::send_error('key_not_found');

API::send_success('key_got', ['key' => $key]);
