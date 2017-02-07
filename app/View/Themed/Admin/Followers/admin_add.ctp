<div class="centerDiv">
<div class="followers form" style="text-align:center">
<?php echo $this->Form->create('Follower'); ?>
	<fieldset>
		<legend><?php echo __('Admin: Add Follower'); ?></legend>

		<?php echo $this->Html->link(__('<i class="glyphicon glyphicon-arrow-left"></i>'), array('action' => 'index'), array('escape' => false)); ?>
	</div>
		<?php
		echo $this->Form->input('openid', array('class'=> 'form-control'));
		echo $this->Form->input('first_name', array('class'=> 'form-control'));
		echo $this->Form->input('nickname', array('class'=> 'form-control'));
		echo $this->Form->input('location_latitude', array('class'=> 'form-control'));
		echo $this->Form->input('location_longitude', array('class'=> 'form-control'));
	?>
	</fieldset>
	<br>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
