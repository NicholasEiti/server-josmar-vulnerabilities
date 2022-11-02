<?php

require_once "../library/library.php";

$AVAILABLE_PAGES = [
    'drawers'   => ['tag' => 'drawer',  'title' => 'Visualizar armário'         ],
    'requests'  => ['tag' => 'request', 'title' => 'Visualizar pedido de chave' ]
];

$tag = $_GET['tag'];

$access = new Access;

if (!isset($AVAILABLE_PAGES[$tag])) {
    Fabric::generateHead("Erro 404", styleFiles: [ "/static/styles/main-logged-in.css" ]);
    Fabric::generateStart($access);
    ?><script>showError('error_404')</script><?php
    Fabric::generateFooter();
    exit;
}

$access->loggedPage();

Fabric::generateHead($AVAILABLE_PAGES[$tag]['title'], [
    "/static/scripts/list-block-api.js",
    "/static/scripts/show-block-api.js"
], [
    "/static/styles/main-logged-in.css",
    "/static/styles/list-block-api.css",
    "/static/styles/show-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateShowBlock($AVAILABLE_PAGES[$tag]['tag']);
Fabric::generateFooter();
