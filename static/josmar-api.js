const ERROR_STATUS = 1;
const SUCCESS_STATUS = 0;

const MSG_ERRORS = {
    api_system_unexpected_error: 'Erro interno do sistema, tente novamente mais tarde.',
    api_login_not_found: 'Não foi possível encontrar sua conta, tente novamente.',
    api_wrong_password: 'Senha incorreta, tente novamente.'
};

const API_URLS_CONFIGS = {
    auth: {url: '/api/auth', method: 'GET'},
    drawer_list: {url: '/api/drawer/list', method: 'GET'}
};

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
        xmlHttp.send(data);
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
    code_msg = MSG_ERRORS[code_msg];

    var errorElement = document.getElementById('msg-error');
    errorElement.innerText = code_msg;
    errorElement.style.display = "block";
}

function clearError() {
    var errorElement = document.getElementById('msg-error');
    errorElement.innerText = "";
    errorElement.style.display = null;
}

function generateIcon(icon_name, tokens, onclick) {
    let icon = document.createElement('span');
    icon.classList.add('material-symbols-rounded');

    if (tokens != undefined) {
        icon.classList.add(tokens)
    }

    icon.textContent = icon_name;

    return icon;
}
