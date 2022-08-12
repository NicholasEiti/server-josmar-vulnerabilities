<?php
/**
 * Criar armário
 * /drawer/create
*/

require_once "../library.php";

/**
 * Get regular name
 * 'test' => test
 * 'teste_asd' => teste_asd
 * '123' => 123
 * 'asd123asd' => asd123asd
 * '' => False
 * 123 => 123
 */
function getRegularName(string $name, $lenght=50): false|string
{
    $name_len = strlen($name);

    if ($name_len >= $lenght or $name_len <= 0)
        return False;

    return trim($name);
}

$drawer_name = getRegularName($_GET['name']);

if ($drawer_name === False) {
    API::send_error([
        'msg' => 'Unexpected name'
    ]);
}

if (DBRequest::search('drawers', 'WHERE name = ?', [$drawer_name]) !== False) {
    API::send_error([
        'msg' => 'Name is already in use'
    ]);
}

DBRequest::insert('drawers', [
    'name' => $drawer_name
]);

API::send_success([
    'msg' => 'Drawer created.',
    'drawer_id' => DBRequest::get_last_id('drawers')
]);
