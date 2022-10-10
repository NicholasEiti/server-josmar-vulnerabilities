<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Criar chave", [
    "/static/add-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/add-form-api.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateAddForm('key');
Fabric::generateFooter();
