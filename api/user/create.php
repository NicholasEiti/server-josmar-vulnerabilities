<?php
/**
 * Criar Usuário
 */

require_once "../library/library.php";

Params::requestMethodMustBe('GET');

$user_name      = Params::getParam('name', min_length: 5, max_length: 20);
$user_password  = Params::getParam('password', min_length: 5, max_length: 20);
$user_email     = Params::getRegexParam('email', EMAIL_PATTERN);
$user_level     = Params::getEnumParam('level', $ENUM_USER_LEVELS);

if (UserDB::count('WHERE name = ?', [$user_name]) !== 0)
    API::send_error('user_name_in_use');

if (UserDB::count('WHERE email = ?', [$user_email]) !== 0)
    API::send_error('user_email_in_use');

$user_password = UserDB::formatPassword($user_password);
$user_email = UserDB::formatEmail($user_email);

if (!UserDB::insert([
    'name'      => $user_name,
    'password'  => $user_password,
    'email'     => $user_email,
    'level'     => $user_level
]))
    API::send_error('user_error_on_create');

API::send_success('user_created', [ 'id' => UserDB::get_last_id() ]);
