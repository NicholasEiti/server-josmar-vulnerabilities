<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Alterar pedido de chave", [
    "/static/edit-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/edit-form-api.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateEditForm('request');
Fabric::generateFooter();
