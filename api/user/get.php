<?php
/**
 * Ler um armário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken();

$user_id = Params::getIntParam('id');

$user = UserDB::searchById($user_id);

if ($user === false)
    API::send_error('user_not_found');

unset($user['password']);

$possible_levels = array_flip(UserDB::$ENUM_LEVELS);

if (!isset($possible_levels[$user['level']]))
    API::send_error('user_unexpected_level');

$user['level'] = $possible_levels[$user['level']];

API::send_success('user_got', ['user' => $user]);
