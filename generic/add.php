<?php

require_once "../library/library.php";

$AVAILABLE_PAGES = [
    'keys'      => ['tag' => 'key',     'title' => 'Criar chave'            ],
    'users'     => ['tag' => 'user',    'title' => 'Criar usuário'          ],
    'drawers'   => ['tag' => 'drawer',  'title' => 'Criar armário'          ],
    'requests'  => ['tag' => 'request', 'title' => 'Criar pedido de chave'  ]
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
    "/static/scripts/form-block-api.js",
], [
    "/static/styles/main-logged-in.css",
    "/static/styles/form-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateAddForm($AVAILABLE_PAGES[$tag]['tag']);
Fabric::generateFooter();
