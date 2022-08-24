<?php

require_once "../library/library.php";

$access = new Access;

$access->loggedPage();

Fabric::generateHead("Listagem dos armários", [
    "/static/main-logged-in.js"
], [
    "/static/main-logged-in.css",
    "https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,700,1,-25"
]);

Fabric::generateLoggedInStart($access);

?>
<div class='list-block' id='list-block'></div>
<script>
"use script";

const listBlock = document.getElementById('list-block');
const token = getToken();

document.addEventListener('DOMContentLoaded', function () {
    requestAPI('drawer_list', { token }, function(response) {
        if (response.list == false) {
            drawNoResult();
        } else {
            for (const drawer of response.list) {
                drawDrawerItem(drawer);
            }
        }
    });
});

function drawNoResult() {
    let item = document.createElement('div');

    item.classList.add('item-list');

    let itemText = document.createElement('p');
    itemText.classList.add('item-list-text');
    itemText.textContent = `Não há armários cadastrados`;
    item.appendChild(itemText);

    listBlock.appendChild(item);
}

function drawDrawerItem(drawer) {
    let item = document.createElement('div');

    item.classList.add('item-list');

    let itemText = document.createElement('p');
    itemText.classList.add('item-list-text');
    itemText.textContent = drawer.name;
    item.appendChild(itemText);

    let editIcon = generateIcon('edit');
    editIcon.classList.add('item-list-icon')
    item.appendChild(editIcon);

    let deleteIcon = generateIcon('delete');
    deleteIcon.classList.add('item-list-icon')
    item.appendChild(deleteIcon);

    listBlock.appendChild(item);
}

function generateIcon(icon_name) {
    let icon = document.createElement('span');
    icon.classList.add('material-symbols-rounded');

    icon.textContent = icon_name;

    return icon;
}
</script>
<?php

// quant = 10
// page = 1

Fabric::generateFooter();
