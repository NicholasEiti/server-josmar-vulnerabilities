<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem dos armários", [
    "/static/list-block-api.js"
], [
    "/static/main-logged-in.css"
]);

Fabric::generateLoggedInStart($access);

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$id = 0 < $id ? $id : null;

?>
<div class='show-block' id='show-block'></div>

<script>
    let token = getToken();
    let id = <?= $id ?>;
    let showBlock = document.getElementById('show-block');

    showBlock.innerHTML = 'Carregando...';

    requestAPI('drawer_get', {token, id}, function(response) {
        showBlock.innerHTML = ''
        showBlock.appendChild(drawShowDrawer(showBlock, response.drawer))
    });
    
    function drawShowDrawer(showBlock, drawer) {    
        let itemText = document.createElement('p');
        itemText.classList.add('show-block-text');
        itemText.textContent = drawer.name;
        showBlock.appendChild(itemText);
    
        let itemIcons = document.createElement('div')
        itemIcons.classList.add('show-block-icons')
    
        let editLink = document.createElement('a');
        editLink.setAttribute("href", "/drawers/" + drawer.id + "/edit");
        editLink.appendChild(generateIcon('edit', 'show-block-icon'))
        itemIcons.appendChild(editLink);
    
        let deleteLink = document.createElement('a');
        deleteLink.setAttribute("href", "/drawers/" + drawer.id + "/delete");
        deleteLink.appendChild(generateIcon('delete', 'show-block-icon'));
        itemIcons.appendChild(deleteLink);

        showBlock.appendChild(itemIcons);
    }
</script>
<?php

Fabric::generateFooter();
