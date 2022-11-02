<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Criar chave", [
    "/static/form-block-api.js",
], [
    "/static/main-logged-in.css",
    "/static/form-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateAddForm('key');
Fabric::generateFooter();
