<?php
/**
 * Listagem dos armários
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken(ADMIN_MIN_LEVEL);

$list = DrawerDB::search('ORDER BY id');

API::send_success('drawer_list', [ 'list' => $list ]);