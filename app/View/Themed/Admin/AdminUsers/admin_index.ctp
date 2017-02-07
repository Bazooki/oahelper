
<div class="adminUsers index">
	<legend style="text-align: center"><?php echo __('Admin Users'); ?></legend>
	<table class="table table-striped">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('username'); ?></th>
			<th><?php echo $this->Paginator->sort('password'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($adminUsers as $adminUser): ?>
	<tr>
		<td><?php echo h($adminUser['AdminUser']['id']); ?>&nbsp;</td>
		<td><?php echo h($adminUser['AdminUser']['username']); ?>&nbsp;</td>
		<td><?php echo h($adminUser['AdminUser']['password']); ?>&nbsp;</td>
		<td><?php echo h($adminUser['AdminUser']['created']); ?>&nbsp;</td>
		<td><?php echo h($adminUser['AdminUser']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), array('action' => 'view', $adminUser['AdminUser']['id']), array('escape' => false)); ?>
			<?php echo $this->Html->link(__('<i class="glyphicon glyphicon-cog"></i>'), array('action' => 'edit', $adminUser['AdminUser']['id']), array('escape' => false)); ?>
			<?php echo $this->Form->postLink(__('<i class="glyphicon glyphicon-minus-sign"></i>'), array('action' => 'delete', $adminUser['AdminUser']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $adminUser['AdminUser']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
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
	<?php }?>
</div>

