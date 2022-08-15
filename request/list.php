<?php
/**
 * Listagem dos pedidos
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

$request_user = Params::getListOfIntsParam('user', true);
$request_key = Params::getListOfIntsParam('key', true);
$request_status = Params::getListOfIntsParam('status', true);

$request_date_start = Params::getDateParam('date_start', '!Y-m-d H:i:s', optional: true);
$request_date_end   = Params::getDateParam('date_end', '!Y-m-d H:i:s', optional: true);

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

if ($request_status !== null) {
    $queries[] = '`status` IN (?' . str_repeat(', ?', count($request_status) - 1) . ')';
    $params = array_merge($params, $request_status);
}

if ($request_date_start !== null) {
    $queries[] = '`date_expected_start` < ? and ? < `date_expected_end`';
    $params[] = $request_date_start->format('Y-m-d H:i:s');
    $params[] = $request_date_start->format('Y-m-d H:i:s');
}

if ($request_date_end !== null) {
    $queries[] = 'date_expected_start` < ? and ? < `date_expected_end`';
    $params[] = $request_date_end->format('Y-m-d H:i:s');
    $params[] = $request_date_end->format('Y-m-d H:i:s');
}

if ($request_date_start !== null and $request_date_end !== null) {
    $queries[] = '? < `date_expected_start` and `date_expected_end` < ?';
    $params[] = $request_date_start->format('Y-m-d H:i:s');
    $params[] = $request_date_end->format('Y-m-d H:i:s');
}

if (count($queries) != 0)
    $list = RequestDB::search('WHERE ' . implode(' and ', $queries) . ' ORDER BY id', $params);
else
    $list = RequestDB::search('ORDER BY id', $params);

API::send_success('request_list', [ 'list' => $list ]);
