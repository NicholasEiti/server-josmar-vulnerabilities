<?php
/**
 * Listagem das chaves
 * /key/list
*/

require_once "../library.php";

API::requestMethodMustBe('GET');

API::send_success('Success to generate list.', [
    'list' => DBRequest::search('keys', 'ORDER BY id')
]);
