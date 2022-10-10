<?php
/**
 * Listagem dos armários
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$quant = Params::getIntParam('quant', optional: true);
$offset = Params::getIntParam('offset', optional: true);

$dynamicSearch = DrawerDB::dynamicListSearch(order: '`drawers`.`id`', limit: $quant, offset: $offset);

API::send_success('drawer_list', $dynamicSearch);