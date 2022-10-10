<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem dos usuários", [
    "/static/list-block-api.js"
], [
    "/static/main-logged-in.css",
    "/static/list-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateListBlock('user');
Fabric::generateFooter();
