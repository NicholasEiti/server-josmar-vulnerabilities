<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Criar pedido de chave", [
    "/static/add-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/add-form-api.css"
]);

Fabric::generateStart($access);
Fabric::generateAddForm('request');
Fabric::generateFooter();
