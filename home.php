<?php
/** Página principal - logado*/

require_once "./library/library.php";

Access::mustBeLoggedIn();


Fabric::generateHead("Página principal", styleFiles: [
    "/static/main-logeed-in.css"
]);

Fabric::generateLoggedInStart(10);

Fabric::generateFooter();
