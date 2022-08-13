<?php
/**
 * Alterar armário
 * /drawer/edit
 */

require_once "../library/library.php";

API::requestMethodMustBe('POST');

$drawer_id = Params::getIDParam('id', method: "_POST");
$drawer_name = Params::getParam('name', min_length: 5, max_length: 10, method: "_POST");

$drawer = DBRequest::searchById('drawers', $drawer_id);

if ($drawer === false)
    API::send_error('drawer_not_found');

if ($drawer['name'] === $drawer_name)
    API::send_error('drawer_already_has_this_name');

if (DBRequest::search('drawers', 'WHERE name = ?', [$drawer_name]) !== False)
    API::send_error('drawer_name_in_use');

if (!DBRequest::update('drawers', $drawer_id, [ 'name' => $drawer_name ]))
    API::send_error('drawer_error_on_edit');

API::send_success('Drawer edited.');
