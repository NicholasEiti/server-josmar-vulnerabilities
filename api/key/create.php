<?php
/**
 * Criar chave
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken(ADMIN_MIN_LEVEL);

$key_name = Params::getParam('name', min_length: 5, max_length: 10);
$key_drawer = Params::getIntParam('drawer');
$key_position = Params::getIntParam('position', true);

$key_name = KeyDB::formatName($key_name);

if (KeyDB::count('WHERE name = ?', [$key_name]) !== 0)
    API::send_error('key_name_in_use');

if (!DrawerDB::hasId($key_drawer))
    API::send_error('key_drawer_not_found');

$params = [ 'name' => $key_name, 'drawer' => $key_drawer ];

if ($key_position !== null) {
    $params['position'] = $key_position;
        
    if (KeyDB::count('WHERE `position` = ? and `drawer` = ?', [
        $key_position, $key_drawer
    ]) !== 0)
        API::send_error('key_position_in_use');
}

if (!KeyDB::insert($params))
    API::send_error('key_error_on_create');

$id = KeyDB::get_last_id();

API::send_success('key_created', [ 'id' => $id ]);
