<?php
/**
 * Listagem dos usuários
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken(ADMIN_MIN_LEVEL);

$quant = Params::getIntParam('quant', optional: true);
$offset = Params::getIntParam('offset', optional: true);

$dynamicSearch = UserDB::dynamicListSearch([], [], '`users`.`id`', $quant, $offset);

if ($dynamicSearch['list'] !== false && count($dynamicSearch['list']) !== 0)
    foreach ($dynamicSearch['list'] as &$user)
        unset($user['password']);

API::send_success('user_list', $dynamicSearch);
