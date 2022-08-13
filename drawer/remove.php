<?php
/**
 * Remover armário
 * /drawer/remove
*/

require_once "../library.php";

API::requestMethodMustBe('POST');

$drawer_id = Params::getIDParam('id', method: "_POST");

$drawer = DBRequest::searchById('drawers', $drawer_id);

if ($drawer === false)
    API::send_error('drawer_not_found');

$keys = DBRequest::search('keys', 'WHERE drawer = ?', [$drawer_id]);

if ($keys !== false) {
    $key_ids = array_map(fn ($key) => $key['id'], $keys);

    if (!DBRequest::deleteById('keys', $key_ids))
    API::send_error('drawer_error_on_remove_keys');
}

if (!DBRequest::deleteById('drawers', $drawer_id))
    API::send_error('drawer_error_on_remove');

API::send_success('Drawer removed.', [
    'drawer' => $drawer
]);
