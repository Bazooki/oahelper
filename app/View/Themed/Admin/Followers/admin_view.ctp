<div class="followers view">
<legend style="text-align: center"><?php echo __('Follower:'); ?></legend>
	<div class="centerObj">
		<?php echo $this->Html->link(__('<i class="glyphicon glyphicon-arrow-left"></i>'), array('action' => 'index'), array('escape' => false)); ?>
	</div>
		<br><br>

	<div class="mainDiv">
		<div class="row">
			<?php
			$count = 0;
			$img = $follower['Follower']['image'];
?>
			<a href="#" class="list-group-item active"><h4>Image</h4></a>
				<div class="well">
					<?php
					echo '<img style="width:50%" src="'.$img.'" alt="" />';
					?>
				</div>
			</div>




			<?php
			foreach ($follower['Follower'] as $field=>$value){
				if (strtolower($field) != 'image'){
					?>
						<div class="col-xs-6">
							<a href="#" class="list-group-item active"><h4><?php echo ucfirst($field); ?></h4></a>
							<div class="well">
								<?php

									echo h($value);

								?>&nbsp;
							</div>
						</div>
					<?php
				}
					?>

				<?php
			}
			?>
		</div>
	</div>


</div>
</div>