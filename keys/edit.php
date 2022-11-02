<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Alterar usuário", [
    "/static/form-block-api.js",
], [
    "/static/main-logged-in.css",
    "/static/form-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateEditForm('key');
Fabric::generateFooter();
