<?php
/**
 * Listagem dos armários
 * /drawer/list
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

$list = DrawerDB::search('ORDER BY id');

API::send_success('drawer_list', [ 'list' => $list ]);