<?php
/**
 * Criar chave
 * /key/create
*/

require_once "../library.php";

API::requestMethodMustBe('GET');

$key_name = Params::getParam('name', min_length: 5, max_length: 10);
$key_drawer = Params::getIDParam('drawer');

if (DBRequest::search('keys', 'WHERE name = ?', [$key_name]) !== False)
    API::send_error('Name is already in use.');

if (DBRequest::searchById('drawers', $key_drawer) === False)
    API::send_error('Drawer not found.');

if (!DBRequest::insert('keys', [ 'name' => $key_name, 'drawer' => $key_drawer ]))
    API::send_error('Something wrong on create key, try again.');

API::send_success('Key created.', [ 'id' => DBRequest::get_last_id('keys') ]);
