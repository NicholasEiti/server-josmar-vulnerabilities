<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem das chaves", [
    "/static/list-block-api.js"
], [
    "/static/main-logged-in.css",
    "/static/list-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateListBlock('key');
Fabric::generateFooter();
