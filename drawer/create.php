<?php
/**
 * Criar armário
 * /drawer/create
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

$drawer_name = Params::getParam('name', min_length: 5, max_length: 10);

if (DBRequest::search('drawers', 'WHERE name = ?', [$drawer_name]) !== False)
    API::send_error('drawer_name_in_use');

if (!DBRequest::insert('drawers', [ 'name' => $drawer_name ]))
    API::send_error('drawer_error_on_create');

API::send_success('Drawer created.', [ 'id' => DBRequest::get_last_id('drawers') ]);
