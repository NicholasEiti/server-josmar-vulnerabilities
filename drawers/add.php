<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Página principal", styleFiles: [
    "/static/main-logged-in.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateInput('name', [
    'label' => 'Nome do novo armário',
    'type' => 'string',
    'min_length' => 5,
    'max_length' => 50
]);
Fabric::generateFooter();
