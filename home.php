<?php
/** Página principal - conectado */

require_once "./library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Página principal", styleFiles: [
    "/static/styles/main-logged-in.css",
    "/static/styles/download-app-block.css"
]);

Fabric::generateStart($access);

Fabric::generateDownloadBlock();

Fabric::generateFooter();
