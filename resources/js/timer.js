window.getTimer = function (a) {
    var h = Math.round( a / 3600 - 0.5 );
    var m = Math.round( ( a / 60 ) % 60 - 0.5 );
    var s = Math.round( a % 60 );
    var d = '';
    var res = '';

    if (s == 60) {
        ++ m;
        s = 0;
    }

    if (h >= 1) {
        d = '';
        if (h >= 24) {
            d = Math.floor(h / 24);
            h -= d * 24;
            d = d + 'дн. ';
        }
        res = d + h + ":" + ( ( m < 10 ) ? "0" : "" ) + m + ":" + ( ( s < 10 ) ? "0" : "" ) + s;
    } else {
        res = m + ":" + ( ( s < 10 ) ? "0" : "" ) + s;
    }
    return res;
}

window.startTimer = function() {
    var timerElement = document.getElementById('timer');
    var seconds = parseInt(timerElement.getAttribute('data-seconds'), 10);
    function updateTimer() {
        if (seconds > 0) {
            seconds--;
            timerElement.innerHTML = window.getTimer(seconds);
        } else {
            clearInterval(timerInterval);
            timerElement.click();
        }
    }
    var timerInterval = setInterval(updateTimer, 1000);
}

document.addEventListener('DOMContentLoaded', window.startTimer);