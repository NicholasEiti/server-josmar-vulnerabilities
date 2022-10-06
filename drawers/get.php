<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem dos armários", [
    "/static/list-block-api.js",
    "/static/show-block-api.js"
], [
    "/static/main-logged-in.css",
    "/static/list-block-api.css",
    "/static/show-block-api.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateShowBlock('drawer');
Fabric::generateFooter();
