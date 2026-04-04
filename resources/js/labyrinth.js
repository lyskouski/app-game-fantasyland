window.goTo = function (lt) {
    if (ge('cod').style.display == 'block') {
        lt += '&c=' + ge('codInput').value;
    }
    fetch(`/cgi/maze_move.php?dir=${lt}`)
        .then(response => response.text())
        .then(text => parse(text));
}

window.pickUp = function(id, add, qn) {
  fetch('/cgi/maze_pickup.php?item_id='+id+'&moo='+add+'&qn='+qn)
        .then(response => response.text())
        .then(text => parse(text));
}

window.doQuestAction = function(id) {
    fetch('/cgi/maze_qaction.php?id=' + id)
        .then(response => response.text())
        .then(text => {
            if (text.includes("location.href='no_combat.php';")) {
                window.location.href = '/cgi/mc_hid.php';
            } else {
                parse(text);
            }
        });
}

window.getSource = function() {
    const id = ge('source').dataset.id;
    fetch(`/cgi/technical_lab_info.php?maze_id=${id}`)
        .then(response => response.text())
        .then(text => {
            const source = text.split(' ');
            ge('source_drop').innerHTML = source[0];
            ge('source_mob').innerHTML = source[1];
        });
}
document.addEventListener('DOMContentLoaded', window.getSource);
setInterval(window.getSource, 60000);

function ge(dir) {
    return document.getElementById(dir);
}

function parse(text) {
    // Get out from labyrinth
    if (text.includes("location.href='no_combat.php';") || text.includes('location.href="no_combat.php";')) {
        window.location.href = '/cgi/no_combat.php';
        return;
    }
    // Show verification code
    if (text.includes('parent.no_combat.ShowCod()')) {
        ge('cod').style.display = 'block';
        ge('codInput').value = '';
        ge('codInput').focus();
    } else {
        ge('cod').style.display = 'none';
    }
    // Buttons
    const aMatches = text.matchAll(/a\s*\(\s*(\d+)\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*\)/g);
    for (const match of aMatches) {
        a(parseInt(match[1], 10), match[2], match[3], match[4]);
    }
    const bMatches = text.matchAll(/b\s*\(\s*(\d+)\s*\)/g);
    for (const match of bMatches) {
        b(parseInt(match[1], 10));
    }
    // Stamina
    const staminaMatch = text.match(/setStamina\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)/);
    if (staminaMatch) {
        setStamina(parseInt(staminaMatch[1], 10), parseInt(staminaMatch[2], 10));
    }
    // Position
    const mooMatch = text.match(/moo\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(false|true)\s*,\s*(false|true)\s*\)/);
    if (mooMatch) {
        moo(
            parseInt(mooMatch[1], 10),
            parseInt(mooMatch[2], 10),
            parseInt(mooMatch[3], 10),
            parseInt(mooMatch[4], 10),
            mooMatch[5] === 'true',
            mooMatch[6] === 'true'
        );
    }
    // Quest Action
    const questMatch = text.match(/ShowQuestAction\s*\(\s*'([^']*)'\s*,\s*(\d+)\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*\)/);
    ge('quest').innerHTML = '';
    if (questMatch) {
        ShowQuestAction(questMatch[1], parseInt(questMatch[2], 10), questMatch[3], questMatch[4]);
        // Extract quest description from HTML
        const questDescMatch = text.match(/ShowQuestAction.*?<b>([^<]+)<\/b>/);
        if (questDescMatch) {
            ge('quest').innerHTML = questDescMatch[1];
        }
    }
    // Pick up items
    const pkuMatches = text.matchAll(/pku\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*'([^']*)'\s*\)/g);
    let isFirst = true;
    ge('items').innerHTML = '';
    for (const match of pkuMatches) {
        const item = {
            id: parseInt(match[1], 10),
            count: parseInt(match[2], 10),
            name: match[3]
        }
        if (isFirst) {
            ge('btn2').src = 'https://www.fantasyland.ru/images/miscellaneous/pick_up.gif';
            ge('btn2').onclick = function() { fcs(2); pickUp(item.id, 0, item.count); };
            ge('btn2').title = item.name;
            isFirst = false;
        }
        const el = document.createElement('a');
        el.onclick = function() { pickUp(item.id, 0, item.count) };
        el.innerHTML = `(${item.count}) ${item.name}`;
        ge('items').appendChild(el);
        const space = document.createElement('span');
        space.innerHTML = '&nbsp;';
        ge('items').appendChild(space);
    }
    // Message
    const messageMatch = text.match(/Syst\s*\(\s*'([^']*)'\s*\)/);
    if (messageMatch) {
        const el = document.createElement('div');
        el.innerHTML = messageMatch[1];
        ge('message').appendChild(el);
        setTimeout(() => {
            ge('message').removeChild(el);
        }, 5000);
    }
}

function fcs(id) {
    ge('btn' + id).src = ge('btn' + id).src.replace('.gif', '_s.gif');
}

function a(id, str, num, img) {
    ge('btn' + id).src = 'https://www.fantasyland.ru/images/miscellaneous/' + img;
    ge('btn' + id).onclick = function() { fcs(id); goTo(num); };
    ge('btn' + id).title = str;
}

function b(id) {
    ge('btn' + id).src = 'https://www.fantasyland.ru/images/miscellaneous/go_default.gif';
    ge('btn' + id).onclick = null;
    ge('btn' + id).title = '';
}

var stamina = 0;
var maxStamina = 100;
var tm;

function updateStamina() {
    if (stamina > maxStamina) {
        stamina = maxStamina;
    }
    ge('stamina').innerHTML = stamina++;
    tm = setTimeout(updateStamina, 1000);
}

function setStamina(x, max) {
    stamina = Math.round(x / 10);
    maxStamina = Math.round(max / 10);
    clearTimeout(tm);
    updateStamina();
}

function moo(z, x, y, s, isTrap, preserveTrapOnMap=true) {
    ge('position').innerHTML = 'L-' + z + ' (' + x + ', ' + y + ')';
    ge('position').style.color = s ? 'white' : 'greenyellow';
    // TBD: traps on map
}

function ShowQuestAction(s, id, im, im_s) {
    if (s) {
        ge('btn6').src = 'https://www.fantasyland.ru/images/miscellaneous/' + im;
        ge('btn6').onclick = function() { fcs(6); doQuestAction(id) };
        ge('btn6').title = s;
    }
}
