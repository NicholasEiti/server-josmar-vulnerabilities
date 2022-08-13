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
    API::send_error('Drawer not found.');

$keys = DBRequest::search('keys', 'WHERE drawer = ?', [$drawer_id]);

if ($keys !== false) {
    $key_ids = array_map(fn ($key) => $key['id'], $keys);

    if (!DBRequest::deleteById('keys', $key_ids))
        API::send_error('Something wrong on remove keys, try again.');
}

if (!DBRequest::deleteById('drawers', $drawer_id))
    API::send_error('Something wrong on remove drawer, try again.');

API::send_success('Drawer removed.', [
    'drawer' => $drawer
]);
