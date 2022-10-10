<?php

require_once "../library/library.php";

$access = new Access;
$access->loggedPage();

Fabric::generateHead("Listagem dos pedidos de chave", [
    "/static/list-block-api.js"
], [
    "/static/main-logged-in.css",
    "/static/list-block-api.css"
]);
Fabric::generateLoggedInStart($access);
Fabric::generateListBlock('request');
Fabric::generateFooter();
