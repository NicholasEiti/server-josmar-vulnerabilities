<?php
/**
 * Criar armário
 * /drawer/create
*/

require_once "../library.php";

API::requestMethodMustBe('GET');

$drawer_name = Params::getParam('name', min_length: 5, max_length: 10);

if (DBRequest::search('drawers', 'WHERE name = ?', [$drawer_name]) !== False)
    API::send_error('Name is already in use.');

if (!DBRequest::insert('drawers', [ 'name' => $drawer_name ]))
    API::send_error('Something wrong on create drawer, try again.');

API::send_success('Drawer created.', [ 'id' => DBRequest::get_last_id('drawers') ]);
