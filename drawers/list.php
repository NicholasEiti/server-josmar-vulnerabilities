<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem dos armários", [
    "/static/main-logged-in.js"
], [
    "/static/main-logged-in.css"
]);

Fabric::generateLoggedInStart($access);

?>
<div class='list-block' id='list-block'></div>
<script>
"use script";

const listBlock = document.getElementById('list-block');

document.addEventListener('DOMContentLoaded', function () {
    generateList(listBlock, 'drawer_list', drawDrawerItem)
});

function drawDrawerItem(drawer) {
    let item = document.createElement('div');

    item.classList.add('item-list');

    let itemText = document.createElement('p');
    itemText.classList.add('item-list-text');
    itemText.textContent = drawer.name;
    item.appendChild(itemText);

    let itemIcons = document.createElement('div')

    itemIcons.classList.add('item-list-icons')

    let editIcon = generateIcon('edit', 'item-list-icon');

    let editLink = document.createElement('a');
    editLink.setAttribute("href", "/drawers/" + drawer.id + "/edit");
    editLink.appendChild(editIcon)

    itemIcons.appendChild(editLink);

    let deleteIcon = generateIcon('delete', 'item-list-icon');

    let deleteLink = document.createElement('a');
    deleteLink.setAttribute("href", "/drawers/" + drawer.id + "/delete");

    deleteLink.appendChild(deleteIcon);

    itemIcons.appendChild(deleteLink);

    item.appendChild(itemIcons);

    listBlock.appendChild(item);
}
</script>
<?php

// quant = 10
// page = 1

Fabric::generateFooter();
