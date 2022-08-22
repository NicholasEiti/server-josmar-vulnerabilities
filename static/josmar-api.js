const ERROR_STATUS = 1;
const SUCCESS_STATUS = 0;

const MSG_ERRORS = {
    api_system_unexpected_error: 'Erro interno do sistema, tente novamente mais tarde.',
    api_login_not_found: 'Não foi possível encontrar sua conta, tente novamente.',
    api_wrong_password: 'Senha incorreta, tente novamente.'
};

const API_URLS = {
    auth: '/api/auth'
};

function requestAPI(method, url, data, callback) {
    let xmlHttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

    xmlHttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            callback(this.responseText);
        }
    }

    if (method === "GET") {
        xmlHttp.open(method, url + "?" + new URLSearchParams(data).toString(), true);
        xmlHttp.send();
    }
    else {
        xmlHttp.open(method, url, true);
        xmlHttp.send(data);
    }
}

function logout() {
    document.cookie = "api_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

    window.location.href = "/";
}

function login(token) {
    document.cookie = "api_token=" + token + ";path=/";
    window.location.href = "/home";
}
