<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Visualizar pedido de chave", [
    "/static/show-block-api.js"
], [
    "/static/main-logged-in.css",
    "/static/show-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateShowBlock('request');
Fabric::generateFooter();
