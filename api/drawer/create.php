<?php
/**
 * Criar armário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

$drawer_name = Params::getParam('name', min_length: 5, max_length: 10);

if (DrawerDB::count('WHERE name = ?', [$drawer_name]) !== 0)
    API::send_error('drawer_name_in_use');

if (!DrawerDB::insert([ 'name' => $drawer_name ]))
    API::send_error('drawer_error_on_create');

API::send_success('Drawer created.', [ 'id' => DrawerDB::get_last_id() ]);
