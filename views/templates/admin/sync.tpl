<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/sync.css">
<script>
    const admin_sync_link = '{$admin_sync_link}';
</script>

<div class="panel">
    <div class="panel-body">
        <h4>Synchronization Status</h4>

        <p id="syncStatus">
            Sync Status: <span id="syncStatusText">Waiting...</span>
        </p>

        <button id="syncButton" class="btn btn-primary">Synchronize Now</button>

        <div class="progress" id="progressBarContainer">
            <div class="progress-bar" id="progressBar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                 aria-valuemax="100"></div>
        </div>

        <div id="syncResult"></div>
    </div>
</div>

<script src="{$module_dir}views/js/sync.js"></script>