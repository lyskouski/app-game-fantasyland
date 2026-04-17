var prices = { "1001": 7.5,"1002": 15,"1003": 7.5,"1004": 7.5,"1005": 7.5,"1006": 15,"1007": 15,"1008": 15,"1009": 15};
var priceBase = 1;

function round(x, n) {
    return Math.round(x * Math.pow(10, n)) / Math.pow(10, n)
}

window.setPrice = function() {
    const selType = document.getElementById('type');
    const curID = 1000 + parseInt(selType.options[selType.selectedIndex].value, 10);
    const montlhyPrice = prices[curID];
    const o = document.getElementById('price');
    const index = parseInt(document.getElementById('long').selectedIndex, 10);

    switch (index) {
        case 0:
            o.innerHTML = round(((montlhyPrice/37.5)*priceBase), 2);
            break;
        case 1:
            o.innerHTML = round(((montlhyPrice/7.5)*priceBase), 2);
            break;
        case 2:
            o.innerHTML = round(((montlhyPrice/3)*priceBase), 2);
            break;
        default:
            o.innerHTML = round(((montlhyPrice)*priceBase), 2);
            break;
    }

    document.getElementById('long_input').value = index;
    document.getElementById('gift_long_input').value = index;
}

window.updateDescription = function() {
    const o = document.getElementById('desc');
    const index = parseInt(document.getElementById('type').selectedIndex, 10);

    switch (index) {
        case 0:
            o.innerHTML = "авто перезапуск добычи, крафта, тренинга";
            break;
        case 1:
            o.innerHTML = "скрывает статус и местоположение персонажа";
            break;
        case 2:
            o.innerHTML = "мгновенное восстановление 1/2 здоровья после боя; 1/2 времени травм и блока при смерти";
            break;
        case 3:
            o.innerHTML = "возможность участвовать в марафоне 2 раза в сутки";
            break;
        case 4:
            o.innerHTML = "+50% опыта за победу";
            break;
        case 5:
            o.innerHTML = "+50% к любой добыче";
            break;
        case 6:
            o.innerHTML = "+50% к скорости крафта";
            break;
        default:
            o.innerHTML = "+50% к скорости тренинга последователей";
            break;
    }

    const type = document.getElementById('type').value;
    document.getElementById('type_input').value = type;
    document.getElementById('gift_type_input').value = type;
    setPrice();
}

document.addEventListener('DOMContentLoaded', updateDescription);
