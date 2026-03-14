window.f1 = function(op, cl) {
    var textarea = document.getElementById('ta_message');
    var la;
    if (op == "[и=") {
        var nick = prompt("Введите ник персонажа","");
        op += nick;
    }
    if ('selectionStart' in textarea) {
        var startPos = textarea.selectionStart;
        var endPos = textarea.selectionEnd;
        var sel = op + textarea.value.substring(startPos, endPos) + cl;
        var newPos = startPos + sel.length;
        textarea.value = textarea.value.substring(0, startPos) + sel +
        textarea.value.substring(endPos, textarea.value.length);
        textarea.setSelectionRange(newPos, newPos);
        textarea.focus();
    }
    else if (document.selection) {
        textarea.focus();
        sel = document.selection.createRange();
        sel.text = op + sel.text + cl;
        sel.collapse(true);
        newPos = sel.text.length;
        sel.moveStart('character', newPos);
        sel.moveEnd('character', newPos);
        sel.select();
        textarea.focus();
    } else {
        textarea.value += op + textarea.value + cl;
        textarea.focus();
    }
}

window.quoteSelection = function() {
    var sel;
    if (document.selection) {
        sel = document.selection.createRange().text;
        if (sel) {
            if (quoteUsername == '') { emoticon( '[ц:' + sel + ']\n'); }
            else { emoticon( '[ц=' + quoteUsername + ':' + sel + ']\n'); }
            return true;
        }
    } else if (document.getSelection) {
        sel = document.getSelection();
        if (sel) {
            if (quoteUsername == '') { emoticon( '[ц:' + sel + ']\n'); }
            else { emoticon( '[ц=' + quoteUsername + ':' + sel + ']\n'); }
            return true;
        }
    }

    alert('Ничего не выделено');
}

window.emoticon = function(text) {
    var textarea = document.getElementById('ta_message');
    if (textarea.createTextRange && textarea.caretPos) {
        var caretPos = textarea.caretPos;
        caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
        textarea.focus();
    } else {
        textarea.value += text;
        textarea.focus();
    }
}

window.storeCaret = function(textEl) {
    if (textEl.createTextRange) {
        textEl.caretPos = document.selection.createRange().duplicate();
    } else if (typeof(textEl.selectionStart)=="number") {
        textEl.caretPos = textEl.selectionStart;
    }
}