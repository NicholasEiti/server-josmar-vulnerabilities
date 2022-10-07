<?php
/**
 * Criar pedido de chave
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

$jwtInstance = API::verifyToken();

$request_user = Params::getIntParam('user', true);

if ($request_user === null)
    $request_user = (int) $jwtInstance->payload['id'];
elseif ($jwtInstance->payload['id'] != $request_user and $jwtInstance->payload['level'] <= ADMIN_MIN_LEVEL)
    API::send_error('api_do_not_have_access');

$request_key                 = Params::getIntParam('key');
$request_date_expected_start = Params::getDateParam('date_start', '!Y-m-d H:i:s');
$request_date_expected_end   = Params::getDateParam('date_end', '!Y-m-d H:i:s');

if (!UserDB::hasId($request_user))
    API::send_error('request_user_not_found');

if (!KeyDB::hasId($request_key))
    API::send_error('request_key_not_found');

if ($request_date_expected_end <= $request_date_expected_start)
    API::send_error('request_date_end_before_start');

if ($request_date_expected_end <= new DateTime())
    API::send_error('request_date_end_before_now');


if (RequestDB::count('WHERE `key` = :key_id and `status` not in (:ended_status, :canceled_status) and (
    (`date_expected_start` < :date_end and `date_expected_end` > :date_end) or
    (`date_expected_start` < :date_start and `date_expected_end` > :date_start) or
    (`date_expected_start` >= :date_start and `date_expected_end` <= :date_end)
)', [
    ':ended_status'     => RequestDB::$ENUM_STATUS['ended'],
    ':canceled_status'  => RequestDB::$ENUM_STATUS['canceled'],
    ':key_id'       => $request_key,
    ':date_start'   => $request_date_expected_start->format('Y-m-d H:i:s'),
    ':date_end'     => $request_date_expected_end->format('Y-m-d H:i:s')
]) !== 0)
    API::send_error('request_key_already_in_use');

if (!RequestDB::insert([
    'user'                  => $request_user,
    'key'                   => $request_key,
    'status'                => RequestDB::$ENUM_STATUS['not_started'],
    'date_expected_start'   => $request_date_expected_start->format('Y-m-d H:i:s'),
    'date_expected_end'     => $request_date_expected_end->format('Y-m-d H:i:s')
]))
    API::send_error('request_error_on_create');

API::send_success('request_created', [ 'id' => RequestDB::get_last_id() ]);
