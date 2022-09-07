<?php

require_once "../library/library.php";

$access = new Access;

Fabric::generateHead("Erro 404");

Fabric::generateLogoutStart();

?><h1>Página de erro 404</h1><?php

Fabric::generateFooter();
