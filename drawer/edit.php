<?php
/**
 * Alterar armário
 * /drawer/edit
*/

require_once "../library.php";

API::requestMethodMustBe('POST');

$drawer_id = Params::getIDParam('id', method: "_POST");
$drawer_name = Params::getParam('name', min_length: 5, max_length: 10, method: "_POST");

$drawer = DBRequest::searchById('drawers', $drawer_id);

if ($drawer === false)
    API::send_error('Drawer not found.');

if ($drawer['name'] === $drawer_name)
    API::send_error('Drawer already has this name.');

if (DBRequest::search('drawers', 'WHERE name = ?', [$drawer_name]) !== False)
    API::send_error('Name is already in use.');

if (!DBRequest::update('drawers', $drawer_id, [ 'name' => $drawer_name ]))
    API::send_error('Something wrong on edit drawer, try again.');

API::send_success('Drawer edited.');
