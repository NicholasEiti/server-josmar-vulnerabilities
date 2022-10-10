<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem dos armários", [
    "/static/list-block-api.js"
], [
    "/static/main-logged-in.css",
    "/static/list-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateListBlock('drawer');
Fabric::generateFooter();
