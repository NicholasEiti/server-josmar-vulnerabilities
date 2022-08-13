<?php
/**
 * Listagem dos usuários
 * /user/list
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

$list = UserDB::search('ORDER BY id');

API::send_success('user_list', [ 'list' => $list ]);
