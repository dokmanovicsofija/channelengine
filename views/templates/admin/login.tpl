<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/login.css">

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Login to ChannelEngine</h4>
        <form id="loginForm">
            <div class="form-group">
                <label for="account_name">Account name</label>
                <input type="text" id="account_name" name="account_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="api_key">Api key</label>
                <input type="text" id="api_key" name="api_key" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Connect</button>
        </form>
    </div>
</div>
