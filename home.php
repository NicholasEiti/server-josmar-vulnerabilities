<?php
/** Página principal - logado*/

require_once "./library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Página principal", styleFiles: [
    "/static/main-logged-in.css"
]);

Fabric::generateLoggedInStart($access);

Fabric::generateFooter();
