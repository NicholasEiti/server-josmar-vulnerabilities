<?php

if (isset($_COOKIE['api_token'])) {
    header('Location: /home');
    exit;
}

?><!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/static/main-style.css">
    <script src="/static/main-script.js"></script>
    <title>AdminJosmar</title>
</head>
<body>
    <div class="container">
        <div id="msg-error" class="msg-error"></div>
        <form class="login-block" onsubmit="return submitLogin(this)" action="/home">
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
    
    function submitLogin(form) {
        requestAPI('GET', '/api/auth', {
            login: login.value,
            password: password.value
        }, function(response) {
            try {
                response = JSON.parse(response);
            } catch (e) {
                showError('api_system_unexpected_error');
            }
    
            if (response.status == SUCCESS_STATUS && response.code == 'api_auth') {
                clearError();

                document.cookie = "api_token=" + response.token + ";path=/";
                window.location.href = "/home";
            } else {
                if (response.status != ERROR_STATUS) {
                    response.code = 'api_system_unexpected_error';
                }

                showError(response.code);
            }
        });

        console.log(form.action)

        return false;
    }
    </script>
</body>
</html>