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

if (!DBRequest::deleteById('drawers', $drawer_id))
    API::send_error('Something wrong on edit drawer, try again.');

API::send_success('Drawer removed.', [
    'drawer' => $drawer
]);
