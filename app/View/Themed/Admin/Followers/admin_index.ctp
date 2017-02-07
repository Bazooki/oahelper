<div class="followers index">
	<legend style="text-align: center"><?php echo __('Followers'); ?></legend>

	<table class="table table-striped">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('openid'); ?></th>
			<th><?php echo $this->Paginator->sort('first_name'); ?></th>
			<th><?php echo $this->Paginator->sort('nickname'); ?></th>
			<th><?php echo $this->Paginator->sort('location_latitude'); ?></th>
			<th><?php echo $this->Paginator->sort('location_longitude'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>

	<?php foreach ($followers as $follower): ?>
		<?php if($follower['Follower']['nickname'] == 'Chris van Vuuren'){ ?>
			<tr>
				<td><?php echo h($follower['Follower']['id']); ?>&nbsp;</td>
				<td><?php echo h($follower['Follower']['openid']); ?>&nbsp;</td>
				<td><?php echo h($follower['Follower']['first_name']); ?>&nbsp;</td>
				<td><?php echo h($follower['Follower']['nickname']); ?>&nbsp;</td>
				<td><?php echo h($follower['Follower']['location_latitude']); ?>&nbsp;</td>
				<td><?php echo h($follower['Follower']['location_longitude']); ?>&nbsp;</td>
				<td><?php echo h($follower['Follower']['created']); ?>&nbsp;</td>
				<td><?php echo h($follower['Follower']['modified']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('<i class="glyphicon glyphicon-eye-open"></i>'), array('action' => 'view', $follower['Follower']['id']), array('escape' => false)); ?>
			</tr>
		<?php } ?>
<?php endforeach; ?>
	<h2>Please note: For the purpose of this demo, followers identities blocked out to protect their privacy</h2>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
	if($this->Paginator->hasNext() || $this->Paginator->hasPrev()) {
		?>

						<nav>
                         	<ul class="pagination pagination-large pull-left">
								<?php
								echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
								echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
								echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
								?>
							</ul>
						</nav>
	<?php }
	?>
	</div>
</div>
