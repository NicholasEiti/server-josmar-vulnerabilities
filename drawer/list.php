<?php
/**
 * Listagem dos armários
 * /drawer/list
 */

require_once "../library/library.php";

API::requestMethodMustBe('GET');

API::send_success('Success to generate list.', [
    'list' => DrawerDB::search('ORDER BY id')
]);
