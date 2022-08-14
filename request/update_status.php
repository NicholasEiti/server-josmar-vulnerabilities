<?php
/**
 * Alterar Pedido
 */

require_once "../library/library.php";

API::requestMethodMustBe('POST');

$request_id        = Params::getIntParam('id', method: '_POST');
$request_status    = Params::getEnumParam('status', $ENUM_REQUEST_STATUS, method: '_POST');

$params = [];

$request = RequestDB::searchById($request_id);

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
