<?php
/**
 * Ler um armário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$request_id = Params::getIntParam('id');

$request = RequestDB::searchById($request_id);

if ($request === false)
    API::send_error('request_not_found');

API::send_success('request_got', ['request' => $request]);
