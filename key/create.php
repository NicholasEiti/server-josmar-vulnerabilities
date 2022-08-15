<?php
/**
 * Criar chave
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

$key_name = Params::getParam('name', min_length: 5, max_length: 10);
$key_drawer = Params::getIntParam('drawer');

if (KeyDB::count('WHERE name = ?', [$key_name]) !== 0)
    API::send_error('key_name_in_use');

if (!DrawerDB::hasId($key_drawer))
    API::send_error('key_drawer_not_found');

if (!KeyDB::insert([ 'name' => $key_name, 'drawer' => $key_drawer ]))
    API::send_error('key_error_on_create');

$id = KeyDB::get_last_id();

API::send_success('key_created', [ 'id' => $id ]);
