const ERROR_STATUS = 1;
const SUCCESS_STATUS = 0;

const MSG_ERRORS = {
    without_error: 'Erro interno do sistema, tente novamente mais tarde.',
    api_system_unexpected_error: 'Erro interno do sistema, tente novamente mais tarde.',
    database_connection_error: 'Erro interno do sistema, tente novamente mais tarde.',
    unexpected_server_error: 'Erro interno do sistema, tente novamente mais tarde.',
    lang_unexpected: 'Erro interno do sistema, tente novamente.',

    api_login_not_found: 'Não foi possível encontrar sua conta, tente novamente.',
    api_wrong_password: 'Senha incorreta, tente novamente.',
    api_do_not_have_access: 'Você não tem acesso a está página',

    
    // error_404: 'Page not found.',
    // error_403: 'Forbidden page.',

    
    // param_wrong_request_method: 'Unexpected request method.',
    // param_not_found: (param_name) => `Expected ${param_name} param.`,
    // param_filter_not_match: (param_name) => `Unexpected input from ${param_name} param.`,
    // param_enum_not_match: (param_name) => `Unexpected input from ${param_name} param.`,
    // param_less_than: (param_name, max_length) => `${param_name} parameter must be less than ${max_length} in length.`,
    // param_more_than: (param_name, min_length) => `${param_name} parameter must be more than ${min_length} in length.`,
    // param_as_int: (param_name) => `Expected ${param_name} as an int.`,
    // param_wrong_format:  (param_name, format) => `${param_name} does not follow expected date format, format: '${format}'`,

    // drawer_not_found: 'Drawer not found.',
    drawer_name_in_use: 'Este nome já esta em uso.',
    drawer_already_has_this_name: 'Nada foi alterado, tente novamente.',
    // drawer_error_on_create: 'Something wrong on create drawer, try again.',
    // drawer_error_on_edit: 'Something wrong on edit drawer, try again.',
    // drawer_error_on_remove_keys: 'Something wrong on remove keys, try again.',
    // drawer_error_on_remove: 'Something wrong on remove drawer, try again.',

    // key_not_found: 'Key not found.',

    // key_name_in_use: 'Name is already in use.',
    // key_drawer_not_found: 'Drawer not found.',
    // key_error_on_create: 'Something wrong on create key, try again.',

    // user_name_in_use: 'Name is already in use.',
    // user_email_in_use: 'Email is already in use.',
    // user_error_on_create: 'Something wrong on create user, try again.',

    // user_nothing_edited: 'Nothing edited.',
    // user_error_on_edit: 'Something wrong on edit user, try again.',

    // request_user_not_found: 'User not found.',
    // request_key_not_found: 'Key not found.',
    // request_date_end_before_start: 'Start date must be before the end date.',
    // request_date_end_before_now: 'Start date must be before now.',
    // request_key_already_in_use: 'This key is already in use.',
    // request_error_on_create: 'Something wrong on create request, try again.',

    // request_not_found: 'Request not found.',
    // request_already_this_status: 'Request already has this status.',
    // request_status_already_ended: 'Request already ended, you can\'t change it.',
    // request_status_already_canceled: 'Request already canceled, you can\'t change it.',
    // request_status_can_not_ended: 'Request can\'t end like this, you need started.',
    // request_status_must_ended: 'Request must end, not anything else.',
    // request_error_on_update_status: 'Something wrong on update start of request, try again.',

    // api_login_not_found: 'Unable to find account.',
    // api_wrong_password: 'Incorrect password, try again.',

    // api_invalid_token: 'Invalid Token.',
    // api_do_not_have_access: 'You don\'t have access to this page.',

    // drawer_created: 'Drawer created.',
    // drawer_edited: 'Drawer edited.',
    // drawer_removed: 'Drawer removed.',
    // drawer_list: 'Success to generate list.',
    // drawer_got: 'Success to get drawer.',

    // key_created: 'Key created.',
    // key_list: 'Success to generate list.',
    // key_removed: 'Key removed.',
    
    // user_list: 'Success to generate list.',
    // user_created: 'User created.',
    // user_edited: 'User edited.',

    // request_list: 'Success to generate list.',
    // request_created: 'Request created.',
    // request_updated_status: 'Request edited.',

    // api_auth: 'Success in authentication.'
};

const API_URLS_CONFIGS = {
    auth: {url: '/api/auth', method: 'GET'},

    drawer_list: {url: '/api/drawer/list', method: 'GET'},
    drawer_get: {url: '/api/drawer/get', method: 'GET'},
    drawer_add: {url: '/api/drawer/create', method: 'GET'},
    drawer_edit: {url: '/api/drawer/edit', method: 'POST'},
    drawer_remove: {url: '/api/drawer/remove', method: 'POST'},

    key_list: {url: '/api/key/list', method: 'GET'},
    key_add: {url: '/api/key/create', method: 'GET'},
    key_get: {url: '/api/key/get', method: 'GET'},
    key_remove: {url: '/api/key/remove', method: 'POST'},

    request_list: {url: '/api/request/list', method: 'GET'},
    request_get: {url: '/api/request/get', method: 'GET'},
    user_list: {url: '/api/user/list', method: 'GET'}
};

const API_REQUEST_STATUS = {
    1: 'Usuário ainda não pegou a chave',
    2: 'Usuário pediu abertura do armário',
    3: 'Usuário está com a chave',
    4: 'Usuário pediu para devolver chave',
    5: 'Usuário já devolveu a chave',
    6: 'Usuário cancelou o pedido'
}

function requestAPI(url_tag, data, callback) {
    let xmlHttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

    xmlHttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            try {
                response = JSON.parse(this.responseText);
            } catch (e) {
                return showError('api_system_unexpected_error');
            }

            if (response.status === ERROR_STATUS) {
                return showError(response.code);
            } else if (response.status == SUCCESS_STATUS) {
                callback(response);
            } else {
                return showError('api_system_unexpected_error');
            }
        }
    }

    let url_config = API_URLS_CONFIGS[url_tag];


    if (url_config == undefined) {
        return showError('api_system_unexpected_error');
    }

    if (url_config.method === "GET") {
        xmlHttp.open(url_config.method, url_config.url + "?" + new URLSearchParams(data).toString(), true);
        xmlHttp.send();
    }
    else {
        xmlHttp.open(url_config.method, url_config.url, true);
        xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlHttp.send(new URLSearchParams(data).toString());
    }
}

function doLogout() {
    document.cookie = "api_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

    window.location.href = "/";
}

function doLogin(token) {
    document.cookie = "api_token=" + token + ";path=/";
    window.location.href = "/home";
}

function getToken() {
    const parts = `; ${document.cookie}`.split('; api_token=');

    if (parts.length === 2)
        return parts.pop().split(';').shift();
}

function showError(code_msg) {
    let msg = MSG_ERRORS[code_msg];

    if (msg === undefined) {
        msg = `Erro Interno - código de erro inesperado`
        console.error(code_msg);
    }

    var errorElement = document.getElementById('msg-error');
    errorElement.innerText = msg;
    errorElement.style.display = "block";
}

function showNoCodeError(msg) {
    var errorElement = document.getElementById('msg-error');
    errorElement.innerText = msg;
    errorElement.style.display = "block";
}

function clearError() {
    var errorElement = document.getElementById('msg-error');
    errorElement.innerText = "";
    errorElement.style.display = null;
}

function generateIcon(icon_name, tokens) {
    let icon = document.createElement('span');
    icon.classList.add('material-symbols-rounded');

    if (tokens != undefined) {
        icon.classList.add(tokens)
    }

    icon.textContent = icon_name;

    return icon;
}
