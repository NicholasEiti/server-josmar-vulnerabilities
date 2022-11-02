<?php

require_once "../library/library.php";

$AVAILABLE_PAGES = [
    'keys'      => ['tag' => 'key',     'title' => 'Remover chave'  ],
    'drawers'   => ['tag' => 'drawer',  'title' => 'Remover armário']
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
    "/static/scripts/delete-block-api.js"
], [
    "/static/styles/main-logged-in.css",
    "/static/styles/delete-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateRemove($AVAILABLE_PAGES[$tag]['tag']);
Fabric::generateFooter();
