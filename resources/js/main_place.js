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
