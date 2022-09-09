<?php

require_once "../library/library.php";

$access = new Access;

Fabric::generateHead("Erro 403");

Fabric::generateLogoutStart();

?><h1>Página de erro 403</h1><?php

Fabric::generateFooter();
