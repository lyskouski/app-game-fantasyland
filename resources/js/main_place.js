window.openTab = function(tabName, element) {
    document.querySelectorAll('.tablinks').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
    document.querySelectorAll('.tabcontent').forEach(el => el.style.display = 'none');
    document.getElementById(tabName).style.display = "block";
}

window.updateCost = function(costId, number) {
    const costElement = document.getElementById(costId);
    const baseCost = parseInt(costElement.dataset.cost, 10);
    const newCost = baseCost * parseInt(number, 10);
    costElement.textContent = newCost;
}

window.filterTents = function(query) {
    const items = document.querySelectorAll('.tent_item');
    const lowerQuery = query.toLowerCase();
    items.forEach(item => {
        const name = item.textContent.toLowerCase();
        item.style.display = name.includes(lowerQuery) ? '' : 'none';
    });
}

window.loadPrice = function(goodId, shpId) {
    fetch(`/store/price?id=${goodId}&shop=${shpId}`)
        .then(response => response.json())
        .then(data => {
            const buyElement = document.getElementById(`b${shpId}`);
            buyElement.dataset.cost = data.buy;
            buyElement.closest('tr').dataset.buy = data.buy;
            buyElement.textContent = data.buy;
            const mbElement = document.getElementById(`mb${shpId}`);
            mbElement.value = data.id;
            if (parseInt(data.sell, 10) > 0) {
                const sellElement = document.getElementById(`s${shpId}`);
                sellElement.dataset.cost = data.sell;
                sellElement.closest('tr').dataset.sell = data.sell;
                sellElement.textContent = data.sell;
                const msElement = document.getElementById(`ms${shpId}`);
                msElement.value = data.id;
            } else {
                const el = document.getElementById(`i${shpId}`);
                el.innerHTML = '---';
            }
        })
        .catch(error => console.error('Error fetching price:', error));
}

window.submitBuyForm = function(form) {
    event.preventDefault();
    const formData = new FormData(form);
    fetch('/cgi/buy.php', {
        method: 'POST',
        body: formData
    })
    .catch(error => console.error('Error:', error));
    return false;
}

window.submitSellForm = function(form) {
    event.preventDefault();
    const formData = new FormData(form);
    fetch('/cgi/sell_good_to_shop.php', {
        method: 'POST',
        body: formData
    })
    .catch(error => console.error('Error:', error));
    return false;
}

window.sortByBuy = function(header) {
    const table = header.closest('table');
    const rows = Array.from(table.querySelectorAll('tr')).slice(1);
    rows.sort((a, b) => {
        const aCost = parseInt(a.dataset.buy || '0', 10);
        const bCost = parseInt(b.dataset.buy || '0', 10);
        return aCost - bCost;
    });
    rows.forEach(row => table.appendChild(row));
}

window.sortBySell = function(header) {
    const table = header.closest('table');
    const rows = Array.from(table.querySelectorAll('tr')).slice(1);
    rows.sort((a, b) => {
        const aCost = parseInt(a.dataset.sell || '0', 10);
        const bCost = parseInt(b.dataset.sell || '0', 10);
        return bCost - aCost;
    });
    rows.forEach(row => table.appendChild(row));
}
