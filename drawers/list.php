<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem dos armários", [
    "/static/list-block-api.js"
], [
    "/static/main-logged-in.css"
]);

$quant = isset($_GET['quant']) ? (int) $_GET['quant'] : null;
$quant = 0 < $quant ? $quant : null;

Fabric::generateLoggedInStart($access);
Fabric::generateListBlock('drawer', $quant);
Fabric::generateFooter();
