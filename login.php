<?php
require_once "./library/library.php";

$access = new Access;

$access->notLoggedPage();

Fabric::generateHead("Login", styleFiles: [
    '/static/styles/login-style.css'
]);

Fabric::generateStart($access);

?>
<form class="login-block" onsubmit="return tryLogin()" action="/home">
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
<script>
let loginInput = document.getElementById('login');
let passwordInput = document.getElementById('password');

function tryLogin() {
    var login = loginInput.value;
    var password = passwordInput.value;

    requestAPI('auth', { login, password }, ({ token, id, level }) => doLogin(token, id, level));

    return false;
}
</script>
<?php

Fabric::generateFooter();