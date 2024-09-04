<link rel="stylesheet" type="text/css" href="{$module_dir}views/css/admin.css">

<div class="panel">
    <div class="panel-body">
        <img src="{$module_dir}views/img/engine.png" class="logo-img" alt="ChannelEngine Logo">
        <h3>Welcome to ChannelEngine</h3>
        <p>Connect, sync product data to ChannelEngine and orders to your shop.</p>
        <button onclick="window.location.href='{$login_url}'"
                id="connectBtn" class="btn btn-primary">Connect</button>
    </div>
</div>

<script>
    let admin_link = '{$link->getAdminLink('AdminChannelEngine')}';
</script>