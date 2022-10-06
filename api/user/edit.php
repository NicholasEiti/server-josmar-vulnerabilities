<?php
/**
 * Alterar Usuário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('POST');

$jwtInstance = API::verifyToken(method: '_POST');

$user_id = Params::getIntParam('id', false, '_POST');

if ($jwtInstance->payload['id'] != $user_id and $jwtInstance->payload['level'] <= ADMIN_MIN_LEVEL)
    API::send_error('api_do_not_have_access');

$user_name      = Params::getParam('name', 5, 20, true, '_POST');
$user_password  = Params::getParam('password', 5, 20, true, '_POST');
$user_email     = Params::getRegexParam('email', EMAIL_PATTERN, true, '_POST');
$user_level     = Params::getEnumParam('level', UserDB::$ENUM_LEVELS, true, '_POST');

$params = [];

$user = UserDB::searchById($user_id);

if ($user === false)
    API::send_error('user_not_found');

if ($user_name !== null and $user['name'] !== $user_name) {
    if (UserDB::count('WHERE name = ?', [$user_name]) !== 0)
        API::send_error('user_name_in_use');

    $params['name'] = $user_name;
}

if ($user_email !== null and $user['email'] !== $user_email) {
    $user_email = UserDB::formatEmail($user_email);

    if (UserDB::count('WHERE email = ?', [$user_email]) !== 0)
        API::send_error('user_email_in_use');

    $params['email'] = $user_email;
}

if ($user_password !== null)
    $params['password'] = UserDB::formatPassword($user_password);

if ($user_level !== null) {
    if ($user_level > $jwtInstance->payload['level'] or $user['level'] > $jwtInstance->payload['level'])
        API::send_error('api_do_not_have_access');

    $params['level'] = $user_level;
}

if (count($params) === 0)
    API::send_error('user_nothing_edited');

if (!UserDB::update($user_id, $params))
    API::send_error('user_error_on_edit');

API::send_success('user_edited');
