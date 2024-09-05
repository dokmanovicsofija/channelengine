<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/login.css">

<div class="panel">
    <div class="panel-body">
        <h4>Login to ChannelEngine</h4>
        {if isset($error)}
            <div class="alert alert-danger">{$error}</div>
        {/if}
        <form id="loginForm" method="POST" action="{$link->getAdminLink('AdminChannelEngine')}&action=processLogin">
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
