<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $this->Html->charset();?>

    <title>
        <?php echo $this->fetch('title'); ?>
    </title>
    <meta name="generator" content="Bootply" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php
    echo $this->Html->meta('icon');

    echo $this->Html->css('bootstrap.min');
    echo $this->Html->css('bootstrap');
    echo $this->Html->css('styles');

    echo '<!--[if lt IE 9]>';
    echo $this->Html->script('html5');
	echo '<![endif]-->';





    echo $this->Html->script('jquery.min');
    echo $this->Html->script('bootstrap.min');
    echo $this->Html->script('scripts');



    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');

    ?>
</head>
<body>
<!-- Header -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php
           echo $this->Html->link('Dashboard', array('controller' => 'admin_users', 'action' => 'dash'), array('class' => 'navbar-brand'));
                ?>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php

                if (AuthComponent::user()){

                ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-user"></i> Admin <span class="caret"></span></a>
                    <ul id="g-account-menu" class="dropdown-menu" role="menu">
                        <li><a><?php echo $this->Html->link(__('Dashboard'), array('controller'=>'admin_users', 'action'=>'dash')); ?></a></li>
                        <li><a><?php echo $this->Html->link(__('Administrators'), array('controller'=>'admin_users', 'action'=>'index')); ?></a></li>
                        <li><a><?php echo $this->Html->link(__('Console'), array('controller'=>'administrators', 'action'=>'index')); ?></a></li>
                        <li><a><?php echo $this->Html->link(__('Followers'), array('controller'=>'followers', 'action'=>'index')); ?></a></li>
                        <li><a><?php echo $this->Html->link(__('QR Code'), array('controller'=>'pages', 'action'=>'home', 'admin' => false)); ?></a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-plus-sign"></i> Add <span class="caret"></span></a>
                    <ul id="g-account-menu" class="dropdown-menu" role="menu">
                        <li><a><?php echo $this->Html->link(__('New Admin User'), array('controller'=>'admin_users','action' => 'add')); ?></a></li>
                        <li><a><?php echo $this->Html->link(__('Fetch Followers'), array('controller'=>'followers','action' => 'fetch_followers')); ?></a></li>
                    </ul>
                </li>
               <li>
                   <?php } ?>

                   <?php


                        if (AuthComponent::user()){

                            echo $this->Html->link('Log out', array('controller' => 'admin_users', 'action' => 'logout'));

                        }

                        ?></li>
            </ul>
        </div>
    </div><!-- /container -->
</div>
<!-- /Header -->
<div id="container">
    <div id="header">

    </div>
    <div id="content">


        <?php echo $this->Session->flash(); ?>

        <?php echo $this->fetch('content'); ?>
    </div>
    <div id="footer">


    </div>
</div>
</body>
</html>