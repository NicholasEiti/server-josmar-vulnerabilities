<?php
/**
 * Ler um armário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$request_id = Params::getIntParam('id');

$request = RequestDB::searchById($request_id, [
    'user' => [ 'name' => 'user_name' ],
    'key' => [ 'name' => 'key_name' ]
]);

if ($request === false)
    API::send_error('request_not_found');

$request['status'] = array_flip(RequestDB::$ENUM_STATUS)[$request['status']];

API::send_success('request_got', ['request' => $request]);
