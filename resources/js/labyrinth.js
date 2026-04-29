import { Device } from '#nativephp';

Number.prototype.in_array = String.prototype.in_array = function (a) {
    if (a.length) {
        for (var i = 0; i < a.length; i++) {
            if (this === a[i]) {
                return true;
            }
        }
    } else {
        for (var i in a) {
            if (this === a[i]) {
                return true;
            }
        }
    }
    return false;
};

window.goTo = function (lt) {
    if (ge('cod').style.display == 'block') {
        lt += '&c=' + ge('codInput').value;
    }
    fetch(`/cgi/maze_move.php?dir=${lt}`)
        .then(response => response.text())
        .then(text => parse(text));
}
document.addEventListener('DOMContentLoaded', () => window.goTo(0));

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
    const aParams = {loc: {}, curr: [], info: [], type: 0, time: 0};
    // Get out from labyrinth
    if (text.includes("location.href='no_combat.php';") || text.includes('location.href="no_combat.php";')) {
        window.location.href = '/cgi/no_combat.php';
        return;
    }
    // Show verification code
    if (text.includes('parent.no_combat.ShowCod()')) {
        const captchaMatch = text.match(/captcha\[([^\]]+)\]/);
        if (captchaMatch) {
            ge('codImage').src = captchaMatch[1];
        }
        ge('cod').style.display = 'block';
        ge('codInput').value = '';
        ge('codInput').focus();
        Device.vibrate();
    } else {
        ge('cod').style.display = 'none';
    }
    // Buttons
    const aMatches = text.matchAll(/a\s*\(\s*(\d+)\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*\)/g);
    for (const match of aMatches) {
        const id = parseInt(match[1], 10);
        const img = match[4];
        a(id, match[2], match[3], img);
        aParams.loc[id] = parseInt(match[3], 10);
        if ([0, 2, 6, 8].includes(id)) {
            aParams.type = 7;
            aParams.info.push([img, match[2]]);
        } else if (~img.indexOf('go') && !~img.indexOf('go_')) {
            aParams.loc[id] += '-' + parseInt(img.split('go')[1], 10);
        }
    }
    const bMatches = text.matchAll(/b\s*\(\s*(\d+)\s*\)/g);
    for (const match of bMatches) {
        const id = parseInt(match[1], 10);
        b(id);
        aParams.loc[id] = 0;
    }
    // Stamina
    const staminaMatch = text.match(/setStamina\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)/);
    if (staminaMatch) {
        setStamina(parseInt(staminaMatch[1], 10), parseInt(staminaMatch[2], 10));
    }
    // Position
    const mooMatch = text.match(/moo\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(false|true)\s*,\s*(false|true)\s*\)/);
    if (mooMatch) {
        var z, x, y, s, isTrap, preserveTrapOnMap;
        moo(
            z = parseInt(mooMatch[1], 10),
            x = parseInt(mooMatch[2], 10),
            y = parseInt(mooMatch[3], 10),
            s = parseInt(mooMatch[4], 10),
            isTrap = mooMatch[5] === 'true',
            preserveTrapOnMap = mooMatch[6] === 'true'
        );
        aParams.curr = [z, x, y];
        if (isTrap && preserveTrapOnMap || text.includes('trap')) {
            aParams.type = 9;
            switch (text.split('trap')[1].split('.')[0]) {
                case '_ally_00':
                case '_00':
                    aParams.info.push(['hp.gif', 'Ловушка (Минус к здоровью)']);
                    break;
                case '_ally_01':
                case '_01':
                    aParams.info.push(['hp.gif', 'Ловушка (Порезы)']);
                    break;
                case '_02':
                case '_ally_02':
                    aParams.info.push(['regen_hp.gif', 'Ядовитый газ']);
                    break;
            }
        }
    }
    if (~text.indexOf("RoomImg('flag.jpg', true")) {
        aParams.type = 8;
        aParams.info.push(['q_action.gif', 'Флаг']);
    }
    // Quest Action
    const questMatch = text.match(/ShowQuestAction\s*\(\s*'([^']*)'\s*,\s*(\d+)\s*,\s*'([^']*)'\s*,\s*'([^']*)'\s*\)/);
    ge('quest').innerHTML = '';
    if (questMatch) {
        ShowQuestAction(questMatch[1], parseInt(questMatch[2], 10), questMatch[3], questMatch[4]);
        aParams.type = 8;
        // Extract quest description from HTML
        const questDescMatch = text.match(/ShowQuestAction.*?<b>([^<]+)<\/b>/);
        if (questDescMatch) {
            ge('quest').innerHTML = questDescMatch[1];
            aParams.info.push([questMatch[3], questDescMatch[1]]);
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
            aParams.info.push(['pick_up.gif', item.name]);
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
    // Draw the cell on map
    aParams.loc = {...aParams.loc, 1: aParams.loc[0], 2: aParams.loc[1], 3: aParams.loc[2], 4: aParams.loc[3]};
    aParams.time = Math.floor(new Date().getTime() / 1000);
    drawMap({[aParams.curr[1]]: {[aParams.curr[2]]: aParams}});
    save(aParams).then((_) => saveToCitadel(aParams));
}

function save(aParams) {
    var o = ge('cimap');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    return fetch('/labyrinth/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            location_id: parseInt(o.dataset.loc, 10),
            place_id: parseInt(o.dataset.place, 10),
            z: aParams.curr[0],
            x: aParams.curr[1],
            y: aParams.curr[2],
            type: aParams.type,
            info: aParams.info,
            loc: aParams.loc,
        })
    }).catch(error => alert('Failed to save map: ' + error));
}

function saveToCitadel(aParams) {
    if (localStorage.citadel_sync != '1') {
        return;
    }
    const dataset = ge('cimap').dataset;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    aParams = {...aParams,
        url: '/cgi/maze_ref.php',
        action: 'coordinates',
        curr: [...aParams.curr, 0, aParams.type == 9 ? 'true' : 'false', 'true )'],
        version: dataset.version
    };
    return fetch('/labyrinth/citadel/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify(aParams)
    })
        .then(response => response.text())
        .then(text => {
            const drawMapMatch = text.match(/drawMap\s*\(\s*\{/);
            if (drawMapMatch) {
                const startIndex = drawMapMatch.index + drawMapMatch[0].length - 1;
                let braceCount = 1;
                let endIndex = startIndex + 1;
                while (braceCount > 0 && endIndex < text.length) {
                    if (text[endIndex] === '{') braceCount++;
                    if (text[endIndex] === '}') braceCount--;
                    endIndex++;
                }
                try {
                    const jsonStr = text.substring(startIndex, endIndex);
                    const data = JSON.parse(jsonStr);
                    drawMap(data);
                } catch (e) {
                    alert('Failed to parse drawMap data: ' + e);
                }
            }
        })
        .catch(error => alert('Failed to save C-map: ' + error));
}

function initToCitadel() {
    if (localStorage.citadel_sync === '1') {
        fetch('/labyrinth/citadel/init')
            .then(response => response.text())
            .then(text => drawMap(JSON.parse(text)))
            .catch(error => alert('Failed to init C-map: ' + error));
    }
}
document.addEventListener('DOMContentLoaded', initToCitadel);

function fcs(id) {
    if (
        !~ge('btn' + id).src.indexOf('_s.gif') ||
        id == 7 && !~ge('btn' + id).src.indexOf('_s_s.gif')
    ) {
        ge('btn' + id).src = ge('btn' + id).src.replace('.gif', '_s.gif');
    }
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
    ge('stamina').innerHTML = stamina++;
    if (stamina > maxStamina) {
        stamina = maxStamina;
        Device.vibrate();
        return;
    }
    tm = setTimeout(updateStamina, 1000);
}

function setStamina(x, max) {
    stamina = Math.round(x / 10);
    maxStamina = Math.round(max / 10);
    clearTimeout(tm);
    updateStamina();
}

function moo(z, x, y, s, isTrap, preserveTrapOnMap=true) {
    if (z != window.aCur[0]) {
        window.aMap = {};
        window.location.href = '/cgi/no_combat.php';
    }
    window.aCur = [z, x, y];
    ge('position').innerHTML = 'L-' + z + ' (' + x + ', ' + y + ')';
    ge('position').style.color = s ? 'white' : 'greenyellow';
}

function ShowQuestAction(s, id, im, im_s) {
    if (s) {
        ge('btn6').src = 'https://www.fantasyland.ru/images/miscellaneous/' + im;
        ge('btn6').onclick = function() { fcs(6); doQuestAction(id) };
        ge('btn6').title = s;
    }
}

// ----------- Map -----------
window.aCur = [0, 1, 1];
window.aIniScrolling = [];
window.aScrolling = [];
window.bScrolling = false;
window.aFocus = [];
window.aMap = {};
window.oCanvas = null;
document.addEventListener('DOMContentLoaded', () => {
    const canvasElement = ge('cimap');
    const rect = canvasElement.getBoundingClientRect();
    // Set canvas resolution to match displayed size
    canvasElement.width = Math.floor(rect.width);
    canvasElement.height = Math.floor(rect.height);
    window.oCanvas = canvasElement.getContext('2d');
});
window.aConfig = JSON.parse(localStorage.config || null) || {
    space: 20,
    border: 4,
    scroll: 10,
    colors: {
        black: '000000',
        white: 'ffffff',
        none: '373747',
        wall_none: '333030',
        wall: '828181',
        fone: ['404040','4d4e4e','333232'],
        type: {9: 'ff0000', 8: '5c7efd', 7: 'ffb881', 6: 'ffb881', 5: 'ffb881'},
        doors: {
            2: 'A10B0B',
            3: '12A10B',
            4: '0B55A1',
            5: 'ffffff',
            6: 'A16E0B',
            7: 'F9B93E',
            8: '3E65F9',
            9: 'E1E1E2',
            10: 'a9ac74',
            11: 'f8aa48',
            12: '14ff00'
        },
        trap: 'ff0000',
        loc:  '9fa06b',
        scroll:'9fa06b',
        focus: 'eee285',
        marker:'e5e285',
        item: '66bb62'
    }
};

window.canvasZoomIn = function() {
    window.aConfig.space  *= 1.05;
    window.aConfig.border *= 1.05;
    window.aConfig.scroll *= 1.05;
    updateConfigState();
};

window.canvasZoomOut = function() {
    window.aConfig.space  *= 0.95;
    window.aConfig.border *= 0.95;
    window.aConfig.scroll *= 0.95;
    updateConfigState();
};

function updateConfigState() {
    localStorage.config = JSON.stringify(window.aConfig);
    drawMap();
}

window.canvasMouseMove = function(event) {
    if (window.getSelection) {
        window.getSelection().removeAllRanges();
    } else if (document.selection && document.selection.clear) {
        document.selection.clear();
    }
    var iSizeSpace = window.aConfig.space,
        aCoord = [Math.floor(event.layerX  / iSizeSpace), Math.floor(event.layerY  / iSizeSpace)];
        aFocus = [aCoord[0]*iSizeSpace + iSizeSpace/2, aCoord[1]*iSizeSpace + iSizeSpace/2, aCoord[0], aCoord[1]];

    if (window.bScrolling) {
        if (window.aIniScrolling.length == 0) {
            window.aIniScrolling = aCoord;
        }
        window.aScrolling = [aCoord[0] - window.aIniScrolling[0], aCoord[1] - window.aIniScrolling[1]];
    }

    if (typeof window.aFocus === 'undefined' || aFocus[0] != window.aFocus[0] || aFocus[1] != window.aFocus[1]) {
        drawMap(null, aFocus);
        window.aFocus = aFocus;
    }
}

window.canvasMouseDown = function(event) {
    if (!window.bScrolling) {
        window.aIniScrolling = [];
        var o = ge('return_focus');
        o.style.display = 'block';
        o.addEventListener('click', function() {
            this.style.display = 'none';
            window.aScrolling = [];
            window.bScrolling = false;
            drawMap();
        });
    }
    window.bScrolling = true;
}

window.canvasMouseUp = function(event) {
    window.bScrolling = false;
}

window.canvasMouseOut = function(event) {
    drawMap();
    window.bScrolling = false;
}

window.canvasTouchStart = function(event) {
    event.preventDefault();
    const touchEvent = new MouseEvent('mousedown', {
        bubbles: true,
        cancelable: true,
        view: window
    });
    Object.defineProperty(touchEvent, 'layerX', {
        value: getTouchLayerX(event),
        enumerable: true
    });
    Object.defineProperty(touchEvent, 'layerY', {
        value: getTouchLayerY(event),
        enumerable: true
    });
    window.canvasMouseDown(touchEvent);
}

window.canvasTouchMove = function(event) {
    event.preventDefault();
    const touchEvent = new MouseEvent('mousemove', {
        bubbles: true,
        cancelable: true,
        view: window
    });
    Object.defineProperty(touchEvent, 'layerX', {
        value: getTouchLayerX(event),
        enumerable: true
    });
    Object.defineProperty(touchEvent, 'layerY', {
        value: getTouchLayerY(event),
        enumerable: true
    });
    window.canvasMouseMove(touchEvent);
}

window.canvasTouchEnd = function(event) {
    event.preventDefault();
    const touchEvent = new MouseEvent('mouseup', {
        bubbles: true,
        cancelable: true,
        view: window
    });
    window.canvasMouseUp(touchEvent);
}

window.canvasTouchCancel = function(event) {
    event.preventDefault();
    const touchEvent = new MouseEvent('mouseleave', {
        bubbles: true,
        cancelable: true,
        view: window
    });
    window.canvasMouseOut(touchEvent);
}

function getTouchLayerX(event) {
    const canvas = ge('cimap');
    const rect = canvas.getBoundingClientRect();
    return event.touches[0].clientX - rect.left;
}

function getTouchLayerY(event) {
    const canvas = ge('cimap');
    const rect = canvas.getBoundingClientRect();
    return event.touches[0].clientY - rect.top;
}

window.drawMap = function(aCurr, aFocus) {
    for (var x in aCurr) {
        for (var y in aCurr[x]) {
            if (typeof window.aMap[x] === 'undefined') {
                window.aMap[x] = {};
            }
            if (typeof window.aMap[x][y] !== 'undefined' && window.aMap[x][y].time > aCurr[x][y].time) {
                continue;
            }
            window.aMap[x][y] = aCurr[x][y];
        }
    }
    var aData = window.aMap;

    var o = ge('cimap');
    var computedStyle = window.getComputedStyle(o);
    var iPlotWidth = parseFloat(computedStyle.width);
    var iPlotHeight = parseFloat(computedStyle.height);
    var aMapSize = [parseInt(o.dataset.x, 10), parseInt(o.dataset.y, 10)];

    var iSizeSpace = window.aConfig.space,
        iSizeBorder = window.aConfig.border,
        iSizeCell = iSizeSpace - iSizeBorder,
        iInitSpace = window.aConfig.scroll,
        aColors = window.aConfig.colors,
        sColor = '',
        sColorB = '',
        iTime = Math.floor(new Date().getTime() / 1000),
        a = {},
        aTemp = [],
        bExist = false,
        bKeysRequired = false,
        aCurr = [window.aCur[1], window.aCur[2]],
        aNum  = [Math.ceil(iPlotWidth  / iSizeSpace), Math.ceil(iPlotHeight / iSizeSpace)],
        aFirst = [aCurr[0] - Math.round(aNum[0]/2), aCurr[1] - Math.round(aNum[1]/2)];

    for (var i = 0; i < 2; i++) {
        if (aCurr[i] + Math.round(aNum[i]/2) > aMapSize[i]) {
            aFirst[i] = aMapSize[i] - aNum[i] + 2;
        }
        if (aFirst[i] < 1) {
            aFirst[i] = 1;
        }

        if (typeof window.aScrolling[i] !== 'undefined') {
            if (window.bScrolling) {
                aFirst[i] += parseInt(aMapSize[i] * window.aScrolling[i] / (aNum[i]-1), 10);
                if (aFirst[i] + Math.round(aNum[i]/2) > aMapSize[i]) {
                    aFirst[i] = aMapSize[i] - aNum[i] + 2;
                }
                if (aFirst[i] < 1) {
                    aFirst[i] = 1;
                }
                window.aScrollingStep = aFirst;
            } else {
                aFirst = window.aScrollingStep;
            }
        }
    }

    ge('cimap').style.backgroundColor = '#' + aColors.wall_none;

    draw(['-', [0, 0, iPlotWidth, iPlotHeight]]);

    for (var x = 0; x < aNum[0]; x++) {
        for (var y = 0; y < aNum[1]; y++) {
            if (x + aFirst[0] > aMapSize[0] || y + aFirst[1] > aMapSize[1]) {
                continue;
            }

            if (typeof aData[x + aFirst[0]] !== 'undefined' && typeof aData[x + aFirst[0]][y + aFirst[1]] !== 'undefined') {
                a = aData[x + aFirst[0]][y + aFirst[1]];
            } else {
                a = {type: 0, time: 0};
            }

            if (parseInt(a.type, 10) === 0) {
                if (a.time && a.time > iTime - 86400) {
                    sColor = aColors.fone[aColors.fone.length - 1];
                    fcolor: for (i = 1; i < aColors.fone.length; i++) {
                        if (Math.ceil(a.time / 3600) !== Math.ceil(iTime / 3600)) {
                            break fcolor;
                        }
                        if (a.time > iTime - 3600/i) {
                            sColor = aColors.fone[i-1];
                        }
                    }
                    bExist = true;
                } else {
                    bExist = false;
                    sColor = aColors.none;
                }
            } else {
                sColor = aColors.type[a.type];
            }
            draw(['s', aColors.black]);
            draw(['f', sColor]);

            if (bExist) {
                draw(['[]', [
                    iInitSpace + x*iSizeSpace - iSizeBorder/2,
                    iInitSpace + y*iSizeSpace - iSizeBorder/2,
                    iSizeCell + iSizeBorder,
                    iSizeCell + iSizeBorder
                ]]);
            } else {
                draw(['[]', [
                    iInitSpace + x*iSizeSpace,
                    iInitSpace + y*iSizeSpace,
                    iSizeCell,
                    iSizeCell
                ]]);
            }
        }
    }

    draw(['s', aColors.type[8]]);
    var iShift = (iSizeSpace - iSizeBorder) / (iSizeSpace * 2);
    var aTemp = [iInitSpace+1+(aCurr[0]-aFirst[0]+iShift)*iSizeSpace, iInitSpace+1+(aCurr[1]-aFirst[1]+iShift)*iSizeSpace, iSizeCell * 0.85];
    draw(['a', [aTemp[0], aTemp[1], aTemp[2], Math.PI*0.4, Math.PI*0.6, 0]]);
    draw(['a', [aTemp[0], aTemp[1], aTemp[2], Math.PI*0.9, Math.PI*1.1, 0]]);
    draw(['a', [aTemp[0], aTemp[1], aTemp[2], Math.PI*1.4, Math.PI*1.6, 0]]);
    draw(['a', [aTemp[0], aTemp[1], aTemp[2], Math.PI*1.9, Math.PI*2.1, 0]]);
    draw(['s', aColors.black]);
    draw(['i', ['/images/lab/icon.gif', iInitSpace+1+(aCurr[0]-aFirst[0])*iSizeSpace, iInitSpace+1+(aCurr[1]-aFirst[1])*iSizeSpace, iSizeCell-1, iSizeCell-1]]);

    for (var x = 0; x < aNum[0]; x++) {
        for (var y = 0; y < aNum[1]; y++) {
            if (x + aFirst[0] > aMapSize[0] || y + aFirst[1] > aMapSize[1]) {
                continue;
            }

            if (typeof window.aMap[x + aFirst[0]] !== 'undefined' && typeof window.aMap[x + aFirst[0]][y + aFirst[1]] !== 'undefined') {
                a = window.aMap[x + aFirst[0]][y + aFirst[1]];
            } else {
                a = {time: 0};
            }
            if (a.time) {
                for (i in {2:'t',4:'l',5:'r',7:'b'}) {
                    if (a.loc[i] == 0) {
                        sColorB = aColors.wall;
                    } else if(a.loc[i] == parseInt(a.loc[i], 10)) {
                        continue;
                    } else {
                        sColorB = aColors.doors[a.loc[i].split('-')[1]] || '000000';
                        bKeysRequired = true;
                    }
                    draw(['f', sColorB]);
                    aTemp = [iInitSpace + x*iSizeSpace - iSizeBorder/2, iInitSpace + y*iSizeSpace - iSizeBorder/2, iSizeBorder, iSizeBorder, iSizeCell];
                    switch (parseInt(i, 10)) {
                        case 7: aTemp[1] += iSizeCell + iSizeBorder;
                        case 2: aTemp[2] += iSizeCell; aTemp[1] -= iSizeBorder/2; break;
                        case 5: aTemp[0] += iSizeCell + iSizeBorder;
                        case 4: aTemp[3] += iSizeCell; aTemp[0] -= iSizeBorder/2; break;
                    }
                    draw(['<>', aTemp]);
                }

                var sTemp = '';
                for (i in {1:0, 3:0, 6:0, 8:0}) {
                    if (a.loc[i] != 0) {
                        switch (parseInt(a.loc[i], 10)) {
                            case 7: sTemp = 'go_out.gif'; break;
                            case 5: sTemp = 'go_u.gif'; break;
                            case 6: sTemp = 'go_d.gif'; break;
                            default: continue;
                        }
                        draw(['i', ['https://www.fantasyland.ru/images/miscellaneous/'+sTemp, iInitSpace+x*iSizeSpace, iInitSpace+y*iSizeSpace, iSizeCell, iSizeCell]]);
                    }
                }
                if (typeof a.info !== 'undefined' && a.info.length && sTemp === '') {
                    let color = aColors.trap;
                    if (a.info[0][0].in_array(['q_action.gif', 'pick_up.gif', 'pick_up_gold.gif']) || ~a.info[0][0].indexOf('unlock_')) {
                        color = aColors.item;
                    }
                    draw(['f', color]);
                    draw(['a', [iInitSpace+x*iSizeSpace+iSizeBorder+iSizeCell/3, iInitSpace+y*iSizeSpace+iSizeBorder+iSizeCell/3, iSizeCell/3-1, 0, Math.PI*2, 0]]);
                    draw(['fi']);
                    draw(['i', ['https://www.fantasyland.ru/images/miscellaneous/'+a.info[0][0], iInitSpace+x*iSizeSpace+iSizeBorder, iInitSpace+y*iSizeSpace+iSizeBorder, iSizeCell - 2*iSizeBorder, iSizeCell - 2*iSizeBorder]]);
                }
            }

            if (!x || !y) {
                draw(['f', aColors.loc]);
                if (x) {
                    draw(['txt', [x + aFirst[0], iInitSpace + x*iSizeSpace + iSizeSpace/2, iInitSpace + y*iSizeSpace + iSizeSpace/4 + iSizeBorder, '8px Arial', 'center']]);
                } else {
                    draw(['txt', [y + aFirst[1], iInitSpace + x*iSizeSpace + iSizeSpace/4, iInitSpace + y*iSizeSpace + iSizeSpace/2, '8px Arial', 'center']]);
                }
            }
        }
    }

    if (typeof aFocus !== 'undefined') {
        aFocus[0] += iInitSpace-iSizeBorder/2;
        aFocus[1] += iInitSpace-iSizeBorder/2;
        aFocus[2] += aFirst[0];
        aFocus[3] += aFirst[1];
        draw(['f', aColors.focus]);
        draw(['[]', [0, aFocus[1], aFocus[0]-iSizeSpace, 1]]);
        draw(['[]', [aFocus[0]+iSizeSpace, aFocus[1], iPlotWidth, 1]]);
        draw(['[]', [aFocus[0], 0, 1, aFocus[1]-iSizeSpace]]);
        draw(['[]', [aFocus[0], aFocus[1]+iSizeSpace, 1, iPlotHeight]]);
        draw(['s', aColors.focus]);
        draw(['a',  [aFocus[0], aFocus[1], iSizeSpace*3/4, 0, Math.PI*2, 0]]);
        if (typeof window.aMap[aFocus[2]] !== 'undefined' && typeof window.aMap[aFocus[2]][aFocus[3]] !== 'undefined') {
            a = window.aMap[aFocus[2]][aFocus[3]];
        } else {
            a = {type:0, time:0, info:[]};
        }
        var sText = window.usc.temp.getTypeInfo(a, 'L-'+window.usc.temp.aCur[0]+' ('+aFocus[2]+', '+aFocus[3]+')');

        var b = aNum[0]/2 > aFocus[2]-aFirst[0] ? true : false;
        draw(['f', aColors.white]);
        draw(['txt', [sText, aFocus[0]+(b ? iSizeSpace : -iSizeSpace), aFocus[1]-2, '12px Arial', (b ? 'start' : 'end')]]);
        if (a.info.length) {
            sText = '';
            for (i = 0; i < a.info.length; i++) {
                if (a.info[i][1]) {
                    sText += window.usc.trim(a.info[i][1]);
                    if (sText.substr(-1) === ')') {
                        sText = sText.slice(0, -1);
                    }
                    sText += '\r\n';
                }
            }
            draw(['txt', [sText, aFocus[0]+(b ? iSizeSpace : -iSizeSpace), aFocus[1]+14, '12px Arial', (b ? 'start' : 'end')]]);
        }
        draw(['f', aColors.loc]);
        if (typeof a.user !== 'undefined') {
            iTime = Math.floor((iTime - a.time)/60);

            sText = a.user + ' (';
            if (iTime > 60) {
                sText += Math.floor(iTime/60)+' ч. назад)';
            } else {
                sText += iTime+' мин. назад)';
            }
            draw(['txt', [sText, aFocus[0]+(b ? -iSizeSpace : iSizeSpace), aFocus[1]+14, '12px Arial', (b ? 'end' : 'start')]]);
        }
    }

    draw(['s', aColors.black]);
    draw(['f', aColors.black]);
    draw(['[]', [0,0,iInitSpace-iSizeBorder*2/3, iPlotHeight]]);
    draw(['[]', [0,0,iPlotWidth, iInitSpace-iSizeBorder*2/3]]);

    draw(['s', aColors.black]);
    draw(['f', aColors.scroll]);
    var iSize = iPlotWidth*iPlotWidth / (aMapSize[0] * iSizeSpace);
    draw(['<>', [ iPlotWidth*aFirst[0]/aMapSize[0], -iSizeBorder, iSize, iInitSpace-iSizeBorder/2, iInitSpace ]]);
    iSize = iPlotWidth*iPlotHeight / (aMapSize[1] * iSizeSpace);
    draw(['<>', [ -iSizeBorder, iPlotHeight*aFirst[1]/aMapSize[1], iInitSpace-iSizeBorder/2, iSize, iInitSpace ]]);

    return bKeysRequired;
}

function draw(a) {
    var o = window.oCanvas;
    if (!o) return;
    switch (a[0]) {
        case 'txt':
            if (typeof a[1][3] !== 'undefined' && a[1][3] !== '') {
                o.font = a[1][3];
            }
            if (a[1][4]) {
                o.textAlign = a[1][4];
            }
            o.fillText(a[1][0], a[1][1], a[1][2]);
            break;
        case 's':
            o.strokeStyle = '#' + a[1];
            break;
        case 'f':
            o.fillStyle = '#' + a[1];
            break;
        case 'w':
            o.lineWidth = a[1];
            break;
        case 'b':
            o.beginPath();
            if (a[1][0]!='') o.moveTo(a[1][0], a[1][1]);
            break;
        case 'm':
            o.moveTo(a[1][0], a[1][1]);
            break;
        case '' :
            o.lineTo(a[1][0], a[1][1]);
            break;
        case 'i':
            var sTempId = a[1][0].split('.').join('').split('/').join('').replace('http:', '');
            var image = ge(sTempId);
            if (!image) {
                image = document.createElement('img');
                image.src = a[1][0];
                image.id = sTempId;
                ge('cibuffer').appendChild(image);
            }
            o.drawImage(image, a[1][1], a[1][2], a[1][3], a[1][4]);
            break;
        case 'c':
            if (!a[1][2]) o.closePath();
            if (a[1][0]) o.stroke();
            if (a[1][1]) o.fill();
            break;
        case 'st':
            o.stroke();
            break;
        case 'fi':
            o.fill();
            break;
        case 'a':
            o.beginPath();
            o.arc(a[1][0],a[1][1],a[1][2],a[1][3],a[1][4],a[1][5]);
            o.stroke();
            break;
        case '[]':
            o.beginPath();
            o.rect(a[1][0],a[1][1],a[1][2],a[1][3]);
            o.stroke();
            o.fill();
            break;
        case '<>':
            a = a[1];
            o.beginPath();
            if (a[4] > a[2]) {
                o.lineTo(a[0]+a[2]/2, a[1]);
                o.lineTo(a[0]+a[2], a[1]+a[2]/2);

                o.lineTo(a[0]+a[2], a[1]+a[3]-a[2]/2);
                o.lineTo(a[0]+a[2]/2, a[1]+a[3]);

                o.lineTo(a[0], a[1]+a[3]-a[2]/2);

                o.lineTo(a[0], a[1]+a[2]/2);
            } else {
                o.lineTo(a[0]+a[2]-a[3]/2, a[1]);
                o.lineTo(a[0]+a[2], a[1]+a[3]/2);

                o.lineTo(a[0]+a[2]-a[3]/2, a[1]+a[3]);

                o.lineTo(a[0]+a[3]/2, a[1]+a[3]);
                o.lineTo(a[0], a[1]+a[3]-a[3]/2);

                o.lineTo(a[0]+a[3]/2, a[1]);
            }
            o.stroke();
            o.fill();
        case 'v':
            o.bezierCurveTo(a[1][0],a[1][1],a[1][2],a[1][3],a[1][4],a[1][5]);
            break;
        case '-':
            o.clearRect(a[1][0],a[1][1],a[1][2],a[1][3],a[1][4]);
            break;
        case 't':
            o.globalCompositeOperation = a[1];
            break;
        case 'r':
            o.translate(a[1][0],a[1][1]);
            o.rotate(a[1][2].degree());
            o.translate(-a[1][0],-a[1][1]);
            break;
        case 'd':
            o.translate(a[1][0],a[1][1]);
            break;
        case '>':
            o.save();
            break;
        case '<':
            o.restore();
            break;
        case 'x':
            if (typeof a[1][3] === 'undefined') {
                o.fillText(a[1][0], a[1][1], a[1][2]);
                o.strokeText(a[1][0], a[1][1], a[1][2]);
            } else {
                o.fillText(a[1][0], a[1][1], a[1][2], a[1][3]);
                o.strokeText(a[1][0], a[1][1], a[1][2]);
            }
            break;
        case 'o':
            o.translate(a[1][0],a[1][1]);
            o.scale(a[1][2],a[1][3]);
            o.translate(-a[1][0],-a[1][1]);
            break;
    }
    o = null;
}

window.addEventListener('error', (e) => {
    alert('🔴 JS Error: "' + e.message + '" at ' + e.filename + ' line ' + e.lineno);
});

window.addEventListener('unhandledrejection', (e) => {
    alert('🔴 Promise Rejection: "' + e.reason + '"');
});
