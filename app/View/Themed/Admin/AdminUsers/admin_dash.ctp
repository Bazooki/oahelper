<legend style="text-align: center">Dashboard</legend>
<br>
<div class="mainDiv">
    <div class="panel panel-default">


        <div class="panel-body">
            <div class="list-group">
                <a href="#" class="list-group-item active"><h4>Paste the following URL into your sandbox:</h4></a>
                <div class="well"> <?php echo $this->Html->url(array('controller' => 'responder', 'action' => 'index', 'admin' => false), true) ?></div>
            </div>
            <div class="list-group">
                <a href="#" class="list-group-item active"><h4>This is your token, which MUST be modified in core.php:</h4></a>
                <div class="well"> <?php echo Configure::read('token')?></div>
            </div>
            <div class="list-group">
                <a href="#" class="list-group-item active"><h4>This is your "App ID", which MUST be modified in core.php:</h4></a>
                <div class="well"> <?php echo Configure::read('appID')?></div>
            </div>
            <div class="list-group">
                <a href="#" class="list-group-item active"><h4>This is your "App Secret", which MUST be modified in core.php:</h4></a>
                <div class="well"> <?php echo Configure::read('appSecret')?></div>
            </div>
        </div>
    </div>
</div>