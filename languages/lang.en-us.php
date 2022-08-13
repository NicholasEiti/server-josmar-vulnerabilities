<?php
/**
 * File to include "en-us" language
 */

$api_error_msgs = [
    'database_connection_error' => 'Database connection error.',
    'unexpected_server_error' => 'Unexpected server error',
    'unexpected_request_method' => 'Unexpected request method.',

    'error_404' => 'Page not found.',
    
    'lang_unexpected' => 'Unexpected language.',

    'param_not_found' => fn($param_name) => "Expected \"$param_name\" param.",
    'param_less_than' => fn($param_name, $max_length) => "\"$param_name\" parameter must be less than $max_length in length.",
    'param_more_than' => fn($param_name, $min_length) => "\"$param_name\" parameter must be more than $min_length in length.",
    'param_as_int' => fn($param_name) => "Expected \"$param_name\" as an int.",

    'drawer_not_found' => 'Drawer not found.',
    'drawer_name_in_use' => 'Name is already in use.',
    'drawer_already_has_this_name' => 'Drawer already has this name.',
    'drawer_error_on_create' => 'Something wrong on create drawer, try again.',
    'drawer_error_on_edit' => 'Something wrong on edit drawer, try again.',
    'drawer_error_on_remove_keys' => 'Something wrong on remove keys, try again.',
    'drawer_error_on_remove' => 'Something wrong on remove drawer, try again.',

    'key_not_found' => 'Key not found.',

    'key_name_in_use' => 'Name is already in use.',
    'key_drawer_not_found' => 'Drawer not found.',
    'key_error_on_create' => 'Something wrong on create key, try again.',
    'key_error_on_remove' => 'Something wrong on remove key, try again.',
];

$api_success_msgs = [
    'drawer_created' => 'Drawer created.',
    'drawer_edited' => 'Drawer edited.',
    'drawer_removed' => 'Drawer removed.',
    'drawer_list' => 'Success to generate list.',

    'key_created' => 'Key created.',
    'key_list' => 'Success to generate list.',
    'key_removed' => 'Key removed.',

    'user_list' => 'Success to generate list.',
    'user_created' => 'User created.',
];