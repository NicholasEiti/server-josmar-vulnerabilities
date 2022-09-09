<?php
/**
 * Ler um armário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$drawer_id = Params::getIntParam('id');

$drawer = DrawerDB::searchById($drawer_id);

if ($drawer === false)
    API::send_error('drawer_not_found');

API::send_success('drawer_got', ['drawer' => $drawer]);
