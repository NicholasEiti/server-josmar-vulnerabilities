<?php
/**
 * Listagem dos pedidos
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

$jwtInstance = API::verifyToken();

$request_user = Params::getListOfIntsParam('user', true);

if ($request_user !== null)
    if ((count($request_user) != 1 || $jwtInstance->payload['id'] != $request_user[0]))
        API::send_error('api_do_not_have_access');

$request_key = Params::getListOfIntsParam('key', true);
$request_key_drawer = Params::getListOfIntsParam('key_drawer', true);
$request_status = Params::getListOfIntsParam('status', true);

$request_date_start = Params::getDateParam('date_start', '!Y-m-d H:i:s', optional: true);
$request_date_end   = Params::getDateParam('date_end', '!Y-m-d H:i:s', optional: true);

$quant = Params::getIntParam('quant', optional: true);
$offset = Params::getIntParam('offset', optional: true);

$queries = [];
$params = [];

if ($request_user !== null) {
    $queries[] = '`user` IN (?' . str_repeat(', ?', count($request_user) - 1) . ')';
    $params = array_merge($params, $request_user);
}

if ($request_key !== null) {
    $queries[] = '`key` IN (?' . str_repeat(', ?', count($request_key) - 1) . ')';
    $params = array_merge($params, $request_key);
}

if ($request_key_drawer !== null) {
    $queries[] = '`keys`.`drawer` IN (?' . str_repeat(', ?', count($request_key_drawer) - 1) . ')';
    $params = array_merge($params, $request_key_drawer);
}

if ($request_status !== null) {
    $queries[] = '`status` IN (?' . str_repeat(', ?', count($request_status) - 1) . ')';
    $params = array_merge($params, $request_status);
}

if ($request_date_start !== null and $request_date_end !== null) {
    $queries[] = "(
    (`date_expected_start` < ? and ? < `date_expected_end`) or
    (`date_expected_start` < ? and ? < `date_expected_end`) or
    (? <= `date_expected_start` and `date_expected_end` <= ?)
)";
    $params[] = $request_date_start->format('Y-m-d H:i:s');
    $params[] = $request_date_start->format('Y-m-d H:i:s');
    $params[] = $request_date_end->format('Y-m-d H:i:s');
    $params[] = $request_date_end->format('Y-m-d H:i:s');
    $params[] = $request_date_start->format('Y-m-d H:i:s');
    $params[] = $request_date_end->format('Y-m-d H:i:s');
} elseif ($request_date_start !== null) {
    $queries[] = '`date_expected_start` < ? and ? < `date_expected_end`';
    $params[] = $request_date_start->format('Y-m-d H:i:s');
    $params[] = $request_date_start->format('Y-m-d H:i:s');
} elseif ($request_date_end !== null) {
    $queries[] = '`date_expected_start` < ? and ? < `date_expected_end`';
    $params[] = $request_date_end->format('Y-m-d H:i:s');
    $params[] = $request_date_end->format('Y-m-d H:i:s');
}

$dynamicSearch = RequestDB::dynamicListSearch($queries, $params, '`requests`.`id`', $quant, $offset, [
    'user' => [ 'name' => 'user_name' ],
    'key' => [ 'name' => 'key_name', 'drawer' => 'key_drawer' ]
]);

if ($dynamicSearch['list'] && count($dynamicSearch['list']) !== 0) {
    $status_names = array_flip(RequestDB::$ENUM_STATUS);

    foreach ($dynamicSearch['list'] as &$request)
        $request['status'] = $status_names[$request['status']];
}

API::send_success('request_list', $dynamicSearch);
