<?php
/**
 * Listagem dos usuários
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken(ADMIN_MIN_LEVEL);

$list = UserDB::search('ORDER BY id');

if ($list === False)
    $list = [];
else
    foreach ($list as &$row)
        unset($row['password']);

$count = $list === false ? 0 : count($list);

API::send_success('user_list', [ 'list' => $list, 'count' => $count ]);
