<?php
/**
 * Criar armário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken(ADMIN_MIN_LEVEL);

$drawer_name = Params::getParam('name', min_length: 5, max_length: 50);

if (DrawerDB::count('WHERE name = ?', [$drawer_name]) !== 0)
    API::send_error('drawer_name_in_use');

if (!DrawerDB::insert([ 'name' => $drawer_name ]))
    API::send_error('drawer_error_on_create');

API::send_success('drawer_created', [ 'id' => DrawerDB::get_last_id() ]);
