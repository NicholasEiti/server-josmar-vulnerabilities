<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Criar armário", [
    "/static/add-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/add-form-api.css"
]);

Fabric::generateStart($access);
Fabric::generateAddForm('drawer');
Fabric::generateFooter();
