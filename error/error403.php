<?php

require_once "../library/library.php";

$access = new Access;

Fabric::generateHead("Erro 403", styleFiles: [ "/static/main-logged-in.css" ]);

Fabric::generateStart($access);

?><script>showError('error_403')</script><?php

Fabric::generateFooter();
