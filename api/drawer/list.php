<?php
/**
 * Listagem dos armários
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$limit = Params::getIntParam('limit', optional: true);
$dynamicSearch = DrawerDB::dynamicListSearch(order: '`drawers`.`id`', limit: $limit);

API::send_success('drawer_list', $dynamicSearch);