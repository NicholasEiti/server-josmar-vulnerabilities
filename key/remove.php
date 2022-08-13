<?php
/**
 * Remover chave
 * /key/remove
*/

require_once "../library.php";

API::requestMethodMustBe('POST');

$key_id = Params::getIDParam('id', method: "_POST");

$key = DBRequest::searchById('keys', $key_id);

if ($key === false)
    API::send_error('key_not_found');

if (!DBRequest::deleteById('keys', $key_id))
    API::send_error('key_error_on_remove');

API::send_success('Key removed.', [
    'key' => $key
]);
