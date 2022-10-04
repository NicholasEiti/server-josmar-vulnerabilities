<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Página principal", [
    "/static/edit-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/edit-form-api.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateEditForm('request');
Fabric::generateFooter();
