<?php
/**
 * Listagem dos usuários
 * /user/list
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

$list = UserDB::search('ORDER BY id');

foreach ($list as &$row) unset($row['password']);

API::send_success('user_list', [ 'list' => $list ]);
