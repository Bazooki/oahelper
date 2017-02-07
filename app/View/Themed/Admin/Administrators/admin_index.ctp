<legend style="text-align: center">Administrative Console</legend>

<div class="container" style="margin-top: 50px;">
    <div class="col-md-4">
        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#profile" data-toggle="tab"><h4><i class="glyphicon glyphicon-user"></i>Send a Push Message</h4></a></li>
            <li><a href="#Edit" data-toggle="tab"><h4><i class="glyphicon glyphicon-list"></i> Edit the OA Menu</h4></a></li>
            <li><a href="#Config" data-toggle="tab"><h4><i class="glyphicon glyphicon-cog"></i>Config</h4></a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="profile">
                <?php
                echo '<form action="administrators" method="post">';

                echo "<br />";
                echo '</form>';
                ?>

                <table class="table table-striped">
                    <tr>
                        <th><h3><?php echo $this->Paginator->sort('created', 'Created');?></h3></th>
                        <th><h3><?php echo $this->Paginator->sort('nickname', 'Nickname');?></h3></th>
                        <th><h3><?php echo $this->Paginator->sort('openid', 'Open Id');?></h3></th>
                        <th><h3><?php echo $this->Paginator->sort('msgtype', 'Message Type');?></h3></th>
                        <th><h3><?php echo $this->Paginator->sort('content', 'Content');?></h3></th>
                        <th><h3>&nbsp;</h3></th>
<!--                        <th><h3>&nbsp;</h3></th>-->
                    </tr>
                    <?php
                    foreach ($tableData as $row) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            $xml = Xml::build($row['XmlLog']['xml_in']);
                            $unixTime = strtotime($row['XmlLog']['created']);
                            echo $row['XmlLog']['created'];

                            ?>
                        </td>
                        <td><?php echo $row['Follower']['nickname']; ?></td>
                        <td><?php echo $xml->FromUserName; ?></td>
                        <td><?php echo $xml->MsgType; ?></td>
                        <td>
                            <?php
                            //            debug(Xml::toArray($xml));
                            if ($xml->MsgType == 'event') {
                                echo $xml->Event . " " . $xml->EventKey;
                            } else {
                                echo $xml->Content;
                            }
                            ?>
                        </td>
                        <td style="width: 350px">
                            <?php

                            $result = ((time() - $unixTime) / 3600);

                            echo $this->Form->create();
                            echo $this->Form->input('id', array('type' => 'hidden', 'value' => $row['XmlLog']['id']));
                            echo $this->Form->input('FromUserName', array('type' => 'hidden', 'value' => $xml->FromUserName));
                            echo $this->Form->input('yourPush', array('class' => 'form-control-small', 'label' => false, 'div' => false));
                            if ($result < 48) {

                            echo $this->Form->submit('Reply', array('name' => 'submit', 'div' => false, 'style' => array('display' => 'inline-block')));
                            }else{
                                //A message that has been sent in to the OA more than 48 hours ago will expire automatically and will not be usable for service messages.

                                echo "Expired";
                            }
                                echo $this->Form->end();
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <?php
                echo ($totalRows) . " total rows" . "<br><br>" ;
                ?>
                <?php
                if($this->Paginator->hasNext() || $this->Paginator->hasPrev()) { ?>
                    <nav>
                         <ul class="pagination pagination-large pull-left">
                            <?php
                            echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
                            echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
                            echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
                            ?>
                        </ul>
                    </nav>
                <?php } ?>
            </div>
            <div class="tab-pane" id="Edit">
                <div container>
                    <div class="centerObj">
                        <br><br>
                        <h4>Change the values below and press this button to update your menu</h4>
                        <i class="glyphicon glyphicon-arrow-down"></i>
                                <div style = 'text-align: center'>
                                    <?php
                                    echo $this->Form->create();
                                    echo $this->Form->submit('Update Menu', array('name' => 'updateMenu', 'style' => array('display' => 'inline-block'), 'class' => 'btn btn-primary'));
                                    echo '<br><br>';
                                    echo '<pre style="text-align: left;">';
                                    echo $this->Form->input('theMenu',array('type'=>'textarea','value'=>print_r($theMenu,1),'style'=>'width:100%;height:500px','name'=>'theMenu'));
                                    echo '</pre>';

                                    echo $this->Form->end();
                                    ?>

                                </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="Config">
                <div class="centerObj">

                    <div class="mainDiv">
                        <br><br>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="list-group">
                                    <a href="#" class="list-group-item active"><h4>Access token</h4></a>
                                    <div class="well"> <?php echo $accessToken; ?></div>
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item active"><h4>Expires:</h4></a>
                                    <div class="well"> <?php $expires = Cache::read('oa_token_expires', 'default'); echo date('D H:i:s',$expires);
                                        ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>