<div class="adminUsers view">
	<legend style="text-align: center"><?php echo __('Admin User:'); ?></legend>
	<div class="centerObj">
	<?php echo $this->Html->link(__('<i class="glyphicon glyphicon-arrow-left"></i>'), array('action' => 'index'), array('escape' => false)); ?>
		</div>

		<table class="table table-striped">
			<tr>
				<th><h3>ID</h3></th>
				<th><h3>Username</h3></th>
				<th><h3>Password</h3></th>
				<th><h3>Created</h3></th>
				<th><h3>Modified</h3></th>
			</tr>

			<tr>
				<td>
					<?php echo h($adminUser['AdminUser']['id']); ?>
					&nbsp;
				</td>
				<td>
					<?php echo h($adminUser['AdminUser']['username']); ?>
					&nbsp;
				</td>
				<td>
					<?php echo h($adminUser['AdminUser']['password']); ?>
					&nbsp;
				</td>
				<td>
					<?php echo h($adminUser['AdminUser']['created']); ?>
					&nbsp;
				</td>
				<td>
					<?php echo h($adminUser['AdminUser']['modified']); ?>
					&nbsp;
				</td>
			</tr>


		</table>
</div>


