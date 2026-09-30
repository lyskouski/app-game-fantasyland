function checkState() {
    fetch('/cgi/ch_ref.php')
        .then(response => response.text())
        .then(data => {
            // skip
        })
        .catch(error => {
            alert('Ping error: ' + error.toString());
        });
}

const timerInterval = setInterval(checkState, 30000);