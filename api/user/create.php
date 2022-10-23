<?php
/**
 * Criar Usuário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

API::verifyToken(ADMIN_MIN_LEVEL);

$user_name      = Params::getParam('name', min_length: 5, max_length: 20);
$user_password  = Params::getParam('password', min_length: 5, max_length: 20);
$user_email     = Params::getRegexParam('email', EMAIL_PATTERN);
$user_level     = Params::getEnumParam('level', UserDB::$ENUM_LEVELS);
$user_expire_time = Params::getIntParam('expire_time', true);

if (UserDB::count('WHERE name = ?', [$user_name]) !== 0)
    API::send_error('user_name_in_use');

$user_email = UserDB::formatEmail($user_email);

if (UserDB::count('WHERE email = ?', [$user_email]) !== 0)
    API::send_error('user_email_in_use');

$user_password = UserDB::formatPassword($user_password);

$params = [
    'name'      => $user_name,
    'password'  => $user_password,
    'email'     => $user_email,
    'level'     => $user_level
];

if ($user_expire_time !== null and $user_expire_time !== 0) {
    if (UserDB::MIN_EXPIRE_TIME >= $user_expire_time || $user_expire_time >= UserDB::MAX_EXPIRE_TIME)
        API::send_error('user_invalid_expire_time');

    $params['expiretime'] = $user_expire_time;
}

if (!UserDB::insert($params))
    API::send_error('user_error_on_create');

API::send_success('user_created', [ 'id' => UserDB::get_last_id() ]);
