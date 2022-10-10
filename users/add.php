<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Criar usuário", [
    "/static/add-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/add-form-api.css"
]);

Fabric::generateStart($access);
Fabric::generateAddForm('user');
Fabric::generateFooter();
