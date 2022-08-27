function drawNoResult(listElement) {
    let item = document.createElement('div');

    item.classList.add('item-list');

    let itemText = document.createElement('p');
    itemText.classList.add('item-list-text');
    itemText.textContent = `Não há armários cadastrados`;
    item.appendChild(itemText);

    listElement.appendChild(item);
}

function generateList(listElement, url_tag, drawItem) {
    let token = getToken();

    requestAPI(url_tag, { token }, function(response) {
        if (response.list == false) {
            drawNoResult(listElement);
        } else {
            for (const drawer of response.list) {
                drawItem(listElement, drawer);
            }
        }
    });
}