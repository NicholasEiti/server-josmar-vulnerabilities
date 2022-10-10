<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Alterar armário", [
    "/static/edit-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/edit-form-api.css"
]);

Fabric::generateStart($access);
Fabric::generateEditForm('drawer');
Fabric::generateFooter();
