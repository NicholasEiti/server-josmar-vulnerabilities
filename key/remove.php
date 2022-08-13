<?php
/**
 * Remover chave
 * /key/remove
 */

require_once "../library/library.php";

API::requestMethodMustBe('POST');

$key_id = Params::getIDParam('id', method: "_POST");

$key = KeyDB::searchById($key_id);

if ($key === false)
    API::send_error('key_not_found');

if (!KeyDB::deleteById($key_id))
    API::send_error('key_error_on_remove');

API::send_success('Key removed.', [
    'key' => $key
]);
