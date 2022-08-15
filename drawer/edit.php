<?php
/**
 * Alterar armário
 */

require_once "../library/library.php";

API::requestMethodMustBe('POST');

$drawer_id = Params::getIntParam('id', method: "_POST");
$drawer_name = Params::getParam('name', min_length: 5, max_length: 10, method: "_POST");

$drawer = DrawerDB::searchById($drawer_id);

if ($drawer === false)
    API::send_error('drawer_not_found');

if ($drawer['name'] === $drawer_name)
    API::send_error('drawer_already_has_this_name');

if (DrawerDB::count('WHERE name = ?', [$drawer_name]) !== 0)
    API::send_error('drawer_name_in_use');

if (!DrawerDB::update($drawer_id, [ 'name' => $drawer_name ]))
    API::send_error('drawer_error_on_edit');

API::send_success('drawer_edited');
