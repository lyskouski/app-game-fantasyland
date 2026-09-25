var combatId = -100;

window.getInitialState = function() {
    fetch(`/cgi/armylist_yours.php`)
            .then(response => response.text())
            .then(text => extractContent(text))
            .then(content => bindContent('fight_opponents', content));
    fetch(`/cgi/armylist_enemy.php`)
            .then(response => response.text())
            .then(text => extractContent(text))
            .then(content => bindContent('fight_enemies', content));
    // TODO: logs... fetch(`/cgi/combat_panel.php`); and from `/cgi/combat_ref.php`
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', window.getInitialState);
} else {
    window.getInitialState();
}

window.checkState = function() {
    fetch(`/cgi/combat_ref.php?lid=${combatId}`)
            .then(response => response.text())
            .then(text => applyCombatContext(text))
};

setInterval(window.checkState, 5000);

window.useScroll = function(scrollId) {
    fetch(`/cgi/combat_scroll_ins.php?id=${scrollId}&show=1`)
        .then(checkState);
};

window.useArmy = function(armyId) {
    fetch(`/cgi/combat_ins.php?id=${armyId}&show=1`)
        .then(checkState);
};

window.showArmy = function(id) {
    if (!id) {
        document.querySelectorAll(".army-type").forEach(el => el.style.display = '');
    } else {
        document.querySelectorAll(".army-type").forEach(el => el.style.display = 'none');
        document.querySelectorAll(`.army-type_${id}`).forEach(el => el.style.display = '');
    }
};

// const seenTurnIds = new Set();

function prependTurnLog(id, html) {
    //if (seenTurnIds.has(id)) {
    //    return;
    //}
    //seenTurnIds.add(id);
    const content = document.getElementById('combat_log_content');
    const entry = document.createElement('div');
    entry.className = 'combat_log_entry';
    entry.innerHTML = html;
    content.insertBefore(entry, content.firstChild);
}

function extractContent(text) {
    var startMatch = text.match(/var allinfo\s*=\s*\[/);
    return startMatch ? extractLegacyArray(text, startMatch.index + startMatch[0].length - 1) : [];
}

function extractLegacyArray(text, startIndex) {
    const arrayStart = text.indexOf('[', startIndex);
    if (arrayStart === -1) {
        return [];
    }

    let depth = 0;
    let arrayEnd = arrayStart;
    for (; arrayEnd < text.length; arrayEnd++) {
        if (text[arrayEnd] === '[') {
            depth++;
        } else if (text[arrayEnd] === ']') {
            depth--;
            if (depth === 0) {
                arrayEnd++;
                break;
            }
        }
    }

    const json = convertLegacyQuotes(text.substring(arrayStart, arrayEnd));
    try {
        return JSON.parse(json);
    } catch (e) {
        return [];
    }
}

function convertLegacyQuotes(text) {
    return text.replace(/"(?:[^"\\]|\\.)*"|'([^']*)'/g, function(match, value) {
        return value === undefined ? match : '"' + value.replace(/"/g, '\\"') + '"';
    });
}

function bindContent(id, content) {
    const container = document.getElementById(id);
    container.innerHTML = '';
    if (!content || content.length === 0 || content[0].length === 0) {
        return;
    }
    // Apply user info
    let users = content;
    while (users && Array.isArray(users[0]) && Array.isArray(users[0][0])) {
        users = users[0];
    }
    users.forEach(info => addUserinfo(container, info));
    // Actualize scrolls state
    if (content[1]) {
        updateScrollsState(content[1]);
    }
    // Update army
    if (content[2]) {
        content[2].forEach(info => {
            const army = document.getElementById(`army_${info[0]}`);
            if (army) {
                if (info[1] === 0) {
                    army.style.display = 'none';
                }
                const countElement = army.querySelector(".main_middle__count");
                if (countElement) {
                    countElement.innerHTML = info[1];
                }
            }
        });
    }
}

function addUserinfo(container, info) {
    const template = document.querySelector("#fight_template").content;
    const clone = document.importNode(template, true);
    clone.querySelector('details').id = 'usr_' + info[0];
    clone.querySelector(".name").innerHTML = info[1];
    let sex = clone.querySelector(".gender");
    if (info[2].length === 1) {
        sex.src = `https://www.fantasyland.ru/images/miscellaneous/info_${info[2]}.gif`;
    } else {
        sex.style.display = 'none';
    }
    clone.querySelector(".level").innerHTML = info[3];
    clone.querySelector(".h_actual").innerHTML = info[4];
    clone.querySelector(".h_full").innerHTML = info[5];
    var hp = info[5] ? Math.floor(100 * info[4] / info[5]) : 0;
    if (hp < 0) {
        hp = 0;
    }
    const bar = clone.querySelector(".bar");
    bar.value = hp;
    bar.style.setProperty('--bar-color', hp < 30 ? 'red' : hp < 60 ? 'orange' : 'green');

    clone.querySelector(".drak").innerHTML = `${info[6]}/${info[7]}`;
    clone.querySelector(".ric").innerHTML = `${info[8]}/${info[9]}`;
    clone.querySelector(".dam").innerHTML = `${info[10]}/${info[11]}`;
    clone.querySelector(".haos").innerHTML = `${info[12]}/${info[13]}`;
    clone.querySelector(".svet").innerHTML = `${info[14]}/${info[15]}`;
    clone.querySelector(".kold").innerHTML = `${info[16]}/${info[17]}`;
    clone.querySelector(".astrl").innerHTML = `${info[25]}/${info[26]}`;

    let effects = {
        19: ['.luckSh1', '.luckSh2', '.luck'],
        20: ['.regSh1', '.regSh2', '.reg'],
        21: ['.learnSh1', '.learnSh2', '.learn'],
        22: ['.concSh1', '.concSh2', '.conc'],
        23: ['.ppSh1', '.ppSh2', '.pp'],
        24: ['.m_protSh1', '.m_protSh2', '.m_prot'],
        27: ['.pnSh1', '.pnSh2', '.pn']
    }
    for (const [id, cls] of Object.entries(effects)) {
        if (info[id]) {
            clone.querySelector(cls[0]).style.display = '';
            clone.querySelector(cls[1]).style.display = '';
            clone.querySelector(cls[2]).innerHTML = info[id];
        }
    }

    bindEffects(clone.querySelector('.eff'), info);
    container.appendChild(clone);
}

function updateScrollsState(scrollsState) {
    let scrolls = document.querySelectorAll(".scroll_item");
    if (scrolls && scrolls.length > 0) {
        if (scrolls[0].getAttribute('data-id') === null) {
            let j = 0;
            for (let i = 0; i < scrollsState.length; i++) {
                if (!scrollsState[i]) {
                    continue;
                }
                if (scrolls[j]) {
                    scrolls[j].setAttribute('data-id', i);
                    j++;
                }
            }
        }
        scrolls.forEach(scroll => {
            if (scrollsState[scroll.getAttribute('data-id')]) {
                scroll.style.display = '';
            } else {
                scroll.style.display = 'none';
            }
        });
    }
}

function bindEffects(eff, info) {
    const effectList = info[18][0];
    for (let i = 0; i < effectList.length; i++) {
        const raw = effectList[i];
        const turnsLeft = raw.slice(2).sort((a, b) => a - b);
        const effect = [raw[0], raw[1], turnsLeft];
        var countText = ''
        if (effect[2].length > 1) {
            countText = 'x' + effect[2].length
        }
        var leftText = ''
        if (effect[2][0] !== 100) {
            leftText = " (осталось ходов: " + (effect[2].join(',')) + ")"
        }

        const img = document.createElement('img');
        img.height = '20';
        img.width = '20';
        img.src = 'https://www.fantasyland.ru/images/effects/' + effect[0];
        img.title = effect[1] + leftText;
        eff.appendChild(img);

        const el = document.createElement('span');
        el.style['vertical-align'] = '5px';
        el.innerHTML = countText;
        eff.appendChild(el);
        eff.appendChild(document.createElement('br'));
    }
    const extra = document.createElement('span');
    extra.innerHTML = info[18][1];
    eff.appendChild(extra);
}

function applyCombatContext(text) {
    // Parse army state
    const armysMatch = text.match(/armys\s*=\s*(\[[\s\S]*?\]);/);
    if (armysMatch) {
        const json = convertLegacyQuotes(armysMatch[1]);
        try {
            const armys = JSON.parse(json);
            armys.flat(1).forEach(updateOpponentState);
        } catch (e) {
            // skip step if JSON parsing fails
        }
    }
    // Adjust to a single array
    text = text.replaceAll('update([[', 'update([[[').replaceAll(']]);', ']]]);');
    // Parse opponents state
    const oppMatch = text.match(/parent\.your_army\.update\s*\(/);
    if (oppMatch) {
        const opponents = extractLegacyArray(text, oppMatch.index + oppMatch[0].length);
        bindContent('fight_opponents', opponents);
    }
    // Parse enemy state
    const enemyMatch = text.match(/parent\.enemy_army\.update\s*\(/);
    if (enemyMatch) {
        const enemies = extractLegacyArray(text, enemyMatch.index + enemyMatch[0].length);
        bindContent('fight_enemies', enemies);
    }
    // Parse global combat identifier
    const refMatch = text.match(/parent\.combat_panel\.reff\(\s*([^)]*?)\s*\);/);
    combatId = refMatch && refMatch[1] ? refMatch[1].trim() : 'undefined';
    // Update timer
    const timeoutMatch = text.match(/parent\.combat_panel\.oink\s*=\s*(\d+)\s*;/);
    const elapsedMatch = text.match(/parent\.combat_panel\.tm\s*=\s*d0\.getTime\(\)\s*-\s*(\d+)\s*;/);
    if (timeoutMatch) {
        const combatElapsed = elapsedMatch ? Number(elapsedMatch[1]) : 0;
        const timerElement = document.getElementById('timer');
        timerElement.setAttribute('data-seconds', Number(timeoutMatch[1]) - combatElapsed / 1000);
    }
    // Check exit link
    const exitRndMatch = text.match(/leave_combat\.php\?rnd=([^>\s"]+)/i);
    if (exitRndMatch) {
        const enemy = document.getElementById('fight_enemies');
        enemy.innerHTML = '';
        const exitLink = document.createElement('a');
        exitLink.href = `/cgi/leave_combat.php?rnd=${exitRndMatch[1]}`;
        exitLink.innerHTML = 'выйти>>>';
        enemy.appendChild(exitLink);
    }
    // Parse turn log entries, deduplicated by the addTurn turn id (2nd argument)
    const turnMatches = [...text.matchAll(/parent\.combat_panel\.addTurn\(\s*"([\s\S]*?)"\s*,\s*(\d+)\s*\)/g)];
    turnMatches.forEach(match => prependTurnLog(match[2], match[1]));
}

function updateOpponentState(opponent) {
    if (!Array.isArray(opponent) || opponent.length < 6) {
        return;
    }
    const desc = {
        id: opponent[5],
        title: opponent[0],
        img: opponent[1],
        title_scroll: opponent[2],
        img_scroll: opponent[3]
    };
    const army = document.querySelector(`#usr_${desc.id} .fight_army_follower img`);
    if (army) {
        const isEmpty = desc.img === '1x1_tr.gif';
        army.src = isEmpty ? 'https://www.fantasyland.ru/cgi/1x1_tr.gif' : 'https://www.fantasyland.ru/images/armies/' + desc.img;
        army.alt = desc.title;
    }
    const scroll = document.querySelector(`#usr_${desc.id} .fight_army_scroll img`);
    if (scroll) {
        const isEmpty = desc.img_scroll === '1x1_tr.gif';
        scroll.src = isEmpty ? 'https://www.fantasyland.ru/cgi/1x1_tr.gif' : 'https://www.fantasyland.ru/images/items/' + desc.img_scroll;
        scroll.alt = desc.title_scroll;
    }
}
