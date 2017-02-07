<div class="centerDiv">
        <legend style="text-align:center">Please log in:</legend>

        <div class="centerDiv">
                <?php

                echo $this->Form->create();
                echo $this->Form->input('username', array('class'=> 'form-control'));
                echo $this->Form->input('password', array('class'=> 'form-control'));

                ?>
                <br>
                <?php
                echo $this->Form->end('Log in', array('class'=> 'form-control'));
                ?>
        </div>
</div>
