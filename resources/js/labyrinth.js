window.ge = function (dir) {
    return document.getElementById(dir);
}

window.goTo = function (lt) {
    if (ge('cod').style.display == 'block') {
        lt += '&c=' + ge('codInput').value;
    }
    fetch(`/cgi/maze_move.php?dir=${lt}`)
        .then(response => response.text())
        .then(text => alert(text));
}

/*
SetRoomImg('base.jpg', false,'5');
a( 0, 'Выйти', '7', 'go_out.gif' );
b( 1 );
w( "Росомаха",125569,6,2,"FFFFFF",54,"Ветеран - Призрак)",28,"Ученик)[384]",0,"",0,"",0, "M" );
ge()

var strDiv = '';strDiv = '<b>Персонажи тут:</b><BR>';strDiv += '<table>';strDiv += '<tr><td></td><td>';strDiv += '<b title="Игрок" style=" color: #F9FBA8 ; ">PvP</b>'+w( "Росомаха",125569,6,2,"FFFFFF",54,"Ветеран - Призрак)",28,"Ученик)[384]",0,"",0,"",0, "M" ) + '<br>';strDiv += '</table>';ge( 'plrs' ).innerHTML = strDiv;var strDiv='';ge('plrs').innerHTML+=strDiv;timeout_vars = new Array( ); last_time_to = 1774802947;var strDiv = '';ge( 'fights' ).innerHTML = strDiv;var strDiv = ''; clearAction();ge( 'picks' ).innerHTML = strDiv;if( to_to ) clearTimeout( to_to );update_tos( );update_tos2( ); moo( 0, 12, 63, 1, false, true ); setStamina( 1000, 1000 );
*/