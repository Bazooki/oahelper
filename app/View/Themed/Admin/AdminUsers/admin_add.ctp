<div class="centerDiv">
	<div class="adminUsers form" style="text-align:center">
		<?php echo $this->Form->create('AdminUser'); ?>
		<fieldset>
			<legend style="text-align: center"><?php echo __('Add Admin User'); ?></legend>


			<?php echo $this->Html->link(__('<i class="glyphicon glyphicon-arrow-left"></i>'), array('action' => 'index'), array('escape' => false)); ?>
			</div>

			<?php echo $this->Form->input('username', array('class'=> 'form-control')); ?>
			<br>

			<?php echo $this->Form->input('password', array('class'=> 'form-control')); ?>
			<br>

			<?php echo $this->Form->input('password_confirm', array('type' => 'password',  'class'=> 'form-control')); ?>


		</fieldset>
		<br>
		<?php echo $this->Form->end(__('Submit')); ?>
	</div>
</div>
