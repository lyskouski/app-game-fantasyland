window.getTime = function (a) {
    h = Math.round( a / 3600 - 0.5 );
    m = Math.round( ( a / 60 ) % 60 - 0.5 );
    s = Math.round( a % 60 );

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
