<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem dos armários", [
    "/static/show-block-api.js"
], [
    "/static/main-logged-in.css",
    "/static/show-block-api.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateShowBlock('request');
Fabric::generateFooter();
