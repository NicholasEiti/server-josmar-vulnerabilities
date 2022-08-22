<?php

require_once "../library/library.php";

Access::mustBeLoggedIn();

Fabric::generateHead("Drawe list", [
    "/static/main-logged-in.js"
], [
    "/static/main-logeed-in.css"
]);

Fabric::generateLoggedInStart(10);

Fabric::generateFooter();
