<?php
/**
 * Alterar Pedido
 */

require_once "../library/library.php";

Params::requestMethodMustBe('POST');

$jwtInstance = API::verifyToken(method: '_POST');

$request_id        = Params::getIntParam('id', method: '_POST');
$request_status    = Params::getEnumParam('status', RequestDB::$ENUM_STATUS, method: '_POST');

$params = [];

$request = RequestDB::searchById($request_id);

if ($jwtInstance->payload['id'] != $request['user'] and $jwtInstance->payload['level'] <= ADMIN_MIN_LEVEL)
    API::send_error('api_do_not_have_access');

if ($request === false)
    API::send_error('request_not_found');

if ($request_status == $request['status'])
    API::send_error('request_already_this_status');

if ($request['status'] == RequestDB::$ENUM_STATUS['ended'])
    API::send_error('request_status_already_ended');

if ($request['status'] == RequestDB::$ENUM_STATUS['canceled'])
    API::send_error('request_status_already_canceled');

$available_status_paths = [
    RequestDB::$ENUM_STATUS['not_started'] => [
        RequestDB::$ENUM_STATUS['canceled'],
        RequestDB::$ENUM_STATUS['start_request']
    ],
    RequestDB::$ENUM_STATUS['start_request'] => [
        RequestDB::$ENUM_STATUS['canceled'],
        RequestDB::$ENUM_STATUS['start']
    ],
    RequestDB::$ENUM_STATUS['start'] => [
        RequestDB::$ENUM_STATUS['end_request']
    ],
    RequestDB::$ENUM_STATUS['end_request'] => [
        RequestDB::$ENUM_STATUS['start'],
        RequestDB::$ENUM_STATUS['ended']
    ]
];

if (!isset($available_status_paths[$request['status']]))
    API::send_error('request_cannot_go_to_this_status');

if (!in_array($request_status, $available_status_paths[$request['status']]))
    API::send_error('request_cannot_go_to_this_status');

if (!RequestDB::update($request_id, [ 'status' => $request_status ]))
    API::send_error('request_error_on_update_status');

API::send_success('request_updated_status');
