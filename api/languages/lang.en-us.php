<?php
/**
 * File to include "en-us" language
 */

$api_error_msgs = [
    'database_connection_error' => 'Database connection error.',
    'unexpected_server_error' => 'Unexpected server error',
    
    'error_404' => 'Page not found.',
    'error_403' => 'Forbidden page.',
    
    'lang_unexpected' => 'Unexpected language.',
    
    'param_wrong_request_method' => 'Unexpected request method.',
    'param_not_found' => fn($param_name) => "Expected \"$param_name\" param.",
    'param_filter_not_match' => fn($param_name) => "Unexpected input from \"$param_name\" param.",
    'param_enum_not_match' => fn($param_name) => "Unexpected input from \"$param_name\" param.",
    'param_less_than' => fn($param_name, $max_length) => "\"$param_name\" parameter must be less than $max_length in length.",
    'param_more_than' => fn($param_name, $min_length) => "\"$param_name\" parameter must be more than $min_length in length.",
    'param_as_int' => fn($param_name) => "Expected \"$param_name\" as an int.",
    'param_wrong_format' => fn ($param_name, $format) => "\"$param_name\" does not follow expected date format, format: \"$format\"",

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
    'key_position_in_use' => 'Position is already in use.',
    'key_nothing_edited' => 'Nothing edited.',
    'key_error_on_edit' => 'Something wrong on edit key, try again.',

    'user_not_found' => 'User not found.',
    'user_unexpected_level' => 'Unexpected user level.',
    'user_name_in_use' => 'Name is already in use.',
    'user_email_in_use' => 'Email is already in use.',
    'user_error_on_create' => 'Something wrong on create user, try again.',
    'user_nothing_edited' => 'Nothing edited.',
    'user_error_on_edit' => 'Something wrong on edit user, try again.',
    'user_invalid_expire_time' => 'Invalid expire time.',

    'request_user_not_found' => 'User not found.',
    'request_key_not_found' => 'Key not found.',
    'request_date_end_before_start' => 'Start date must be before the end date.',
    'request_date_end_before_now' => 'Start date must be before now.',
    'request_key_already_in_use' => 'This key is already in use.',
    'request_error_on_create' => 'Something wrong on create request, try again.',

    'request_not_found'  => 'Request not found.',
    'request_already_this_status' => 'Request already has this status.',
    'request_status_already_ended' => 'Request already ended, you cannot change it.',
    'request_status_already_canceled' => 'Request already canceled, you cannot change it.',
    'request_cannot_go_to_this_status' => 'Request cannot go to this status.',
    'request_error_on_update_status' => 'Something wrong on update start of request, try again.',

    'api_login_not_found' => 'Unable to find account.',
    'api_wrong_password' => 'Incorrect password, try again.',

    'api_invalid_token' => 'Invalid Token.',
    'api_do_not_have_access' => 'You do not have access to this page.',
];

$api_success_msgs = [
    'drawer_created' => 'Drawer created.',
    'drawer_got' => 'Success to get drawer.',
    'drawer_list' => 'Success to generate list.',
    'drawer_edited' => 'Drawer edited.',
    'drawer_removed' => 'Drawer removed.',

    'key_created' => 'Key created.',
    'key_got' => 'Success to get key.',
    'key_list' => 'Success to generate list.',
    'key_edited' => 'Key edited.',
    'key_removed' => 'Key removed.',
    
    'user_created' => 'User created.',
    'user_list' => 'Success to generate list.',
    'user_edited' => 'User edited.',
    'user_got' => 'Success to get user.',

    'request_created' => 'Request created.',
    'request_got' => 'Success to get request.',
    'request_list' => 'Success to generate list.',
    'request_updated_status' => 'Request edited.',

    'api_auth' => 'Success in authentication.'
];