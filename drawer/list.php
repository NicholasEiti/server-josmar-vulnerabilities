<?php
/**
 * Listagem do armário
 * /drawer/list
*/

require_once "../library.php";

API::requestMethodMustBe('GET');

API::send_success('Success to generate list.', [
    'list' => DBRequest::search('drawers', 'ORDER BY id')
]);
