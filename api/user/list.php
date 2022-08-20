<?php
/**
 * Listagem dos usuários
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

$list = UserDB::search('ORDER BY id');

if ($list === False)
    $list = [];
else
    foreach ($list as &$row)
        unset($row['password']);

API::send_success('user_list', [ 'list' => $list ]);
