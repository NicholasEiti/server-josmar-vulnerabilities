<?php

require_once "../library/library.php";

$access = new Access;

Fabric::generateHead("Erro 404", styleFiles: [ "/static/styles/main-logged-in.css" ]);

Fabric::generateStart($access);

?><script>showError('error_404')</script><?php

Fabric::generateFooter();
