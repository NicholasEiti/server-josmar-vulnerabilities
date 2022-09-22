<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Página principal", [
    "/static/add-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/add-form-api.css"
]);

Fabric::generateLoggedInStart($access);
Fabric::generateAddForm('request');
Fabric::generateFooter();
