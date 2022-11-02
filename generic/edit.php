<?php

require_once "../library/library.php";

$AVAILABLE_PAGES = [
    'keys'      => ['tag' => 'key',     'title' => 'Alterar chave'          ],
    'users'     => ['tag' => 'user',    'title' => 'Alterar usuário'        ],
    'drawers'   => ['tag' => 'drawer',  'title' => 'Alterar armário'        ],
    'requests'  => ['tag' => 'request', 'title' => 'Alterar pedido de chave']
];

$tag = $_GET['tag'];

$access = new Access;

if (!isset($AVAILABLE_PAGES[$tag])) {
    Fabric::generateHead("Erro 404", styleFiles: [ "/static/main-logged-in.css" ]);
    Fabric::generateStart($access);
    ?><script>showError('error_404')</script><?php
    Fabric::generateFooter();
    exit;
}

$access->loggedPage();

Fabric::generateHead($AVAILABLE_PAGES[$tag]['title'], [
    "/static/form-block-api.js",
], [
    "/static/main-logged-in.css",
    "/static/form-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateEditForm($AVAILABLE_PAGES[$tag]['tag']);
Fabric::generateFooter();
