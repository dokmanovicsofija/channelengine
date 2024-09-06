document.getElementById('syncButton').addEventListener('click', function () {
    document.getElementById('syncStatusText').textContent = 'In progress...';
    document.getElementById('syncStatusText').className = 'status-progress';

    const syncUrl = admin_sync_link;

    fetch(syncUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                document.getElementById('syncStatusText').textContent = 'Done';
                document.getElementById('syncStatusText').className = 'status-done';
            } else {
                document.getElementById('syncStatusText').textContent = 'Error';
                document.getElementById('syncStatusText').className = 'status-error';
            }
        })
        .catch(error => {
            document.getElementById('syncStatusText').textContent = 'Error';
            document.getElementById('syncStatusText').className = 'status-error';
            console.error('Synchronization error:', error);
        });
});