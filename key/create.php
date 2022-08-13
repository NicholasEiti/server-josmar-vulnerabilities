<?php
/**
 * Criar chave
 * /key/create
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

$key_name = Params::getParam('name', min_length: 5, max_length: 10);
$key_drawer = Params::getIDParam('drawer');

if (DBRequest::search('keys', 'WHERE name = ?', [$key_name]) !== False)
    API::send_error('key_name_in_use');

if (DBRequest::searchById('drawers', $key_drawer) === False)
    API::send_error('key_drawer_not_found');

if (!DBRequest::insert('keys', [ 'name' => $key_name, 'drawer' => $key_drawer ]))
    API::send_error('key_error_on_create');

API::send_success('Key created.', [ 'id' => DBRequest::get_last_id('keys') ]);
