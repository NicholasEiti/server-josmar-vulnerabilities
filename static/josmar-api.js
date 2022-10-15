const ERROR_STATUS = 1;
const SUCCESS_STATUS = 0;
const EMAIL_PATTERN = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

const MSG_ERRORS = {
    without_error: 'Erro interno do sistema, tente novamente mais tarde.',
    api_system_unexpected_error: 'Erro interno do sistema, tente novamente mais tarde.',
    database_connection_error: 'Erro interno do sistema, tente novamente mais tarde.',
    unexpected_server_error: 'Erro interno do sistema, tente novamente mais tarde.',
    lang_unexpected: 'Erro interno do sistema, tente novamente.',

    api_login_not_found: 'Não foi possível encontrar sua conta, tente novamente.',
    api_wrong_password: 'Senha incorreta, tente novamente.',
    api_do_not_have_access: 'Você não tem acesso a está página',

    
    error_404: 'Erro 404: Página não encontrada.',
    error_403: 'Erro 403: Página não encontrada',

    drawer_name_in_use: 'Este nome já esta em uso.',
    drawer_already_has_this_name: 'Nada foi alterado, tente novamente.',

    key_name_in_use: 'Está nome ja está em uso',
    key_position_in_use: 'Esta posição da chave já está em uso.',
    key_nothing_edited: 'Nada foi alterado, tente novamente.',

    user_unexpected_level: 'Erro interno do sistema ao ler o usuário, tente novamente mais tarde.',
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
    key_edit: {url: '/api/key/edit', method: 'POST'},
    key_get: {url: '/api/key/get', method: 'GET'},
    key_remove: {url: '/api/key/remove', method: 'POST'},

    request_add: {url: '/api/request/create', method: 'GET'},
    request_list: {url: '/api/request/list', method: 'GET'},
    request_get: {url: '/api/request/get', method: 'GET'},
    request_update_status: {url: '/api/request/update_status', method: 'POST'},

    user_get: {url: '/api/user/get', method: 'GET'},
    user_edit: {url: '/api/user/edit', method: 'POST'},
    user_list: {url: '/api/user/list', method: 'GET'},
    user_add: {url: '/api/user/create', method: 'GET'},
};

const API_REQUEST_STATUS = {
    'not_started':      'Usuário ainda não pegou a chave',
    'start_request':    'Usuário pediu abertura do armário',
    'started':          'Usuário está com a chave',
    'end_request':      'Usuário pediu para devolver chave',
    'ended':            'Usuário já devolveu a chave',
    'canceled':         'Usuário cancelou o pedido'
}

function requestAPI(url_tag, data, callback) {
    let xmlHttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

    xmlHttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let response;

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
