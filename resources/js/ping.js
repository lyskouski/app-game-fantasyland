function checkState() {
    fetch('/cgi/ch_ref.php')
        .then(response => response.text())
        .then(data => {
            // TBD: handle response data
        })
        .catch(error => {
            console.error('Ping error:', error);
        });
}

const timerInterval = setInterval(checkState, 30000);