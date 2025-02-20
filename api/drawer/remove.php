<?php
/**
 * Remover armário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('POST');

API::verifyToken(ADMIN_MIN_LEVEL, method: '_POST');

$drawer_id = Params::getIntParam('id', method: "_POST");

$drawer = DrawerDB::searchById($drawer_id);

if ($drawer === false)
    API::send_error('drawer_not_found');

$keys = KeyDB::search('WHERE drawer = ?', [$drawer_id]);

if ($keys !== false) {
    $key_ids = array_map(fn ($key) => $key['id'], $keys);

    if (!KeyDB::deleteById($key_ids))
        API::send_error('drawer_error_on_remove_keys');
}

if (!DrawerDB::deleteById($drawer_id))
    API::send_error('drawer_error_on_remove');

API::send_success('drawer_removed', [ 'drawer' => $drawer ]);
