<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Página principal", [
    "/static/add-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/add-form-api.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateAddForm('Criar novo armário', ['name' => [
    'label' => 'Nome do novo armário',
    'type' => 'string',
    'min_length' => 5,
    'max_length' => 50
]]);
Fabric::generateFooter();
