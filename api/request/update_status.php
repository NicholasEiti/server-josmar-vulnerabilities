<?php
/**
 * Alterar Pedido
 */

require_once "../library/library.php";

Params::requestMethodMustBe('POST');

$jwtInstance = API::verifyToken(method: '_POST');

$request_id        = Params::getIntParam('id', method: '_POST');
$request_status    = Params::getEnumParam('status', $ENUM_REQUEST_STATUS, method: '_POST');

$params = [];

$request = RequestDB::searchById($request_id);

if ($jwtInstance->payload['id'] != $request['user'] and $jwtInstance->payload['level'] <= ADMIN_MIN_LEVEL)
    API::send_error('api_do_not_have_access');

if ($request === false)
    API::send_error('request_not_found');

if ($request_status == $request['status'])
    API::send_error('request_already_this_status');

if ($request['status'] == $ENUM_REQUEST_STATUS['ended'])
    API::send_error('request_status_already_ended');

if ($request['status'] == $ENUM_REQUEST_STATUS['canceled'])
    API::send_error('request_status_already_canceled');

if (
    $request['status'] == $ENUM_REQUEST_STATUS['not_started'] and
    $request_status == $ENUM_REQUEST_STATUS['ended']
)
    API::send_error('request_status_can_not_ended');

if (
    $request['status'] == $ENUM_REQUEST_STATUS['started'] and
    $request_status != $ENUM_REQUEST_STATUS['ended']
)
    API::send_error('request_status_must_ended');

if (!RequestDB::update($request_id, [ 'status' => $request_status ]))
    API::send_error('request_error_on_update_status');

API::send_success('request_updated_status');
