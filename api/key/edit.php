<?php
/**
 * Alterar chave
 */

require_once "../library/library.php";

Params::requestMethodMustBe('POST');

API::verifyToken(ADMIN_MIN_LEVEL, method: '_POST');

$key_id = Params::getIntParam('id', method: "_POST");

$key = KeyDB::searchById($key_id);

if ($key === false)
    API::send_error('key_not_found');

$key_name = Params::getParam('name', 5, 50, true, "_POST");
$key_drawer = Params::getIntParam('drawer', true, "_POST");
$key_position = Params::getIntParam('position', true, "_POST");

$params = [];

if ($key_name !== null) {
    $key_name = KeyDB::formatName($key_name);

    if ($key['name'] !== $key_name) {
        if (KeyDB::count('WHERE `name` = ?', [$key_name]) !== 0)
            API::send_error('key_name_in_use');
    
        $params['name'] = $key_name;
    }
}

$future_key_drawer = null;
$future_key_position = null;

if ($key_drawer !== null and $key['drawer'] != $key_drawer) {
    if (!DrawerDB::hasId($key_drawer))
        API::send_error('key_drawer_not_found');

    $params['drawer'] = $future_key_drawer = $key_drawer;
}

if ($key_position !== null and (
    $key['position'] == null or
    $key_position != $key['position']
)) {
    $params['position'] = $future_key_position = $key_position;
}

if ($future_key_drawer !== null or $future_key_position !== null) {
    if ($future_key_drawer === null)
        $future_key_drawer = (int) $key['drawer'];

    if ($future_key_position === null and $key['position'] !== null)
        $future_key_position = (int) $key['position'];

        
    if (
        $future_key_position !== null and
        KeyDB::count('WHERE `position` = ? and `drawer` = ?', [
            $future_key_position, $future_key_drawer
        ]) !== 0
    )
        API::send_error('key_position_in_use');
}

if (count($params) === 0)
    API::send_error('key_nothing_edited');

if (!KeyDB::update($key_id, $params))
    API::send_error('key_error_on_edit');

API::send_success('key_edited');
