<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem dos armários", [
    "/static/list-block-api.js"
], [
    "/static/main-logged-in.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateListBlock('user');
Fabric::generateFooter();
