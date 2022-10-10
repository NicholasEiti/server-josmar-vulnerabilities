<?php
/** Página principal - conectado */

require_once "./library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Página principal", styleFiles: [
    "/static/main-logged-in.css"
]);

Fabric::generateStart($access);

Fabric::generateFooter();
