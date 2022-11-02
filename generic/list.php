<?php

require_once "../library/library.php";

$AVAILABLE_PAGES = [
    'keys'      => ['tag' => 'key',     'title' => 'Listagem das chaves'            ],
    'users'     => ['tag' => 'user',    'title' => 'Listagem dos usuários'          ],
    'drawers'   => ['tag' => 'drawer',  'title' => 'Listagem dos armários'          ],
    'requests'  => ['tag' => 'request', 'title' => 'Listagem dos pedidos de chave'  ]
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
    "/static/scripts/list-block-api.js"
], [
    "/static/styles/main-logged-in.css",
    "/static/styles/list-block-api.css"
]);

Fabric::generateStart($access);
Fabric::generateListBlock($AVAILABLE_PAGES[$tag]['tag']);
Fabric::generateFooter();
