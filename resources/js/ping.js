function checkState() {
    fetch('/cgi/ch_ref.php')
        .then(response => response.text())
        .then(data => {
            if (data == 'REDIRECT_TO_MAIN') {
                window.location.href = '/';
            }
        })
        .catch(error => {
            alert('Ping error: ' + error.toString());
        });
}

const timerInterval = setInterval(checkState, 30000);