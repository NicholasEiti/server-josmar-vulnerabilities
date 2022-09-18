<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Página principal", [
    "/static/delete-block-api.js"
], [
    "/static/main-logged-in.css",
    "/static/delete-block-api.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateRemove('drawer');
Fabric::generateFooter();
