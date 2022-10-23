<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Alterar usuário", [
    "/static/edit-form-api.js",
], [
    "/static/main-logged-in.css",
    "/static/edit-form-api.css"
]);

Fabric::generateStart($access);
Fabric::generateEditForm('key');
Fabric::generateFooter();
