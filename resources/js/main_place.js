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
            buyElement.textContent = data.buy;
            if (parseInt(data.sell, 10) > 0) {
                const sellElement = document.getElementById(`s${shpId}`);
                sellElement.dataset.cost = data.sell;
                sellElement.textContent = data.sell;
            } else {
                const el = document.getElementById(`i${shpId}`);
                el.innerHTML = '---';
            }
        })
        .catch(error => console.error('Error fetching price:', error));
}