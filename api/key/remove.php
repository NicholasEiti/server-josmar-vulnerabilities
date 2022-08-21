<?php
/**
 * Remover chave
 */

require_once "../library/library.php";

Params::requestMethodMustBe('POST');

API::verifyToken(ADMIN_MIN_LEVEL, method: '_POST');

$key_id = Params::getIntParam('id', method: "_POST");

$key = KeyDB::searchById($key_id);

if ($key === false)
    API::send_error('key_not_found');

if (!KeyDB::deleteById($key_id))
    API::send_error('key_error_on_remove');

API::send_success('key_removed', [ 'key' => $key ]);
