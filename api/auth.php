<?php
/**
 * Criar Usuário
 */

require_once "./library/library.php";

Params::requestMethodMustBe('GET');

$user_login      = Params::getParam('login');
$user_password  = Params::getParam('password');

if (str_contains($user_login, '@'))
    $user = UserDB::search('WHERE email = ? LIMIT 1', [ UserDB::formatEmail($user_login) ]);
else
    $user = UserDB::search('WHERE name = ? LIMIT 1', [ $user_login ]);

if ($user === false)
    API::send_error('api_login_not_found');

$user = $user[0];

if (!password_verify($user_password, $user['password']))
    API::send_error('api_wrong_password');

$expire_after = null;

if ($user['expiretime'] !== null)
    $expire_after = 'PT' . ((int)$user['expiretime']) . 'M';

$jwtInstance = new JosmarWT([
    'id' => $user['id'],
    'name' => $user['name'],
    'level' => $user['level']
], $expire_after);

API::send_success('api_auth', [
    'token' => $jwtInstance->generate_token(),
    'id' => $user['id']
]);
