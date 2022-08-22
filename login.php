<?php
require_once "./library/library.php";

Access::mustBeLogout();

Fabric::generateHead("Login", styleFiles: [
    '/static/login-style.css'
]);

Fabric::generateLogoutStart();

?>
<div class="container">
    <div id="msg-error" class="msg-error"></div>
    <form class="login-block" onsubmit="return submitLogin()" action="/home">
        <div class="input-brick">
            <label class='input-brick-label' for="login">Email ou Login:</label>
            <input class='input-brick-input' id="login"/>
        </div>
        <div class="input-brick">
            <label class='input-brick-label' for="password">Senha:</label>
            <input class='input-brick-input' id="password" type="password"/>
        </div>
        <small>Ao fazer o Login, você concordará com o uso de cookies.</small>
        <div class="submit-brick">
            <input class="submit-brick-input" type="submit" value="Login">
        </div>
    </form>
</div>
<script>
let msgError = document.getElementById('msg-error');
let login = document.getElementById('login');
let password = document.getElementById('password');

function showError(code_msg) {
    code_msg = MSG_ERRORS[code_msg];
    msgError.innerText = code_msg;
    msgError.style.display = "block";
}

function clearError() {
    msgError.innerText = "";
    msgError.style.display = null;
}

function submitLogin() {
    requestAPI('GET', API_URLS.auth, {
        login: login.value,
        password: password.value
    }, function(response) {
        try {
            response = JSON.parse(response);
        } catch (e) {
            return showError('api_system_unexpected_error');
        }

        if (response.status == SUCCESS_STATUS && response.code == 'api_auth') {
            clearError();

            login(response.token);

            return;
        } else {
            if (response.status != ERROR_STATUS) {
                response.code = 'api_system_unexpected_error';
            }

            return showError(response.code);
        }
    });

    return false;
}
</script>
<?php

Fabric::generateFooter();