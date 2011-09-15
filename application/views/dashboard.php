<?php $this->load->view('header');?>

<div id="content">
	<div id="dashboard">
		<div class="title">
			<h2><?=_('Dashboard');?></h2>
		</div>


		<div class="box">
			<h3><?=_('Quick actions');?></h3>
			<p>
<?php
	if (count($action_buttons) > 0):
		$i = 1;
		foreach ($action_buttons as $button):
			if ($i == 1 && count($action_buttons) == 1) {
				$button['class'] = '';
			} else if ($i == count($action_buttons)) {
				$button['class'] = 'right';
			} else if ($i == 1) {
				$button['class'] = 'left';
			} else {
				$button['class'] = 'middle';
			}
?><a class="button <?=$button['class'];?> big" href="<?=$button['target'];?>"><strong><?=image_asset($button['icon']);?></strong><br /><?=$button['text'];?></a><?
			$i++;
		endforeach;
	endif;
?>
			</p>
		</div>
		<div class="box">
			<h3><?=_('Recent events');?></h3>
			<p>
<?
	if (count($recent_buttons) > 0):
		$i = 1;
		foreach ($recent_buttons as $button):
			if ($i == 1 && count($recent_buttons) == 1) {
				$button['class'] = '';
			} else if ($i == count($recent_buttons)) {
				$button['class'] = 'right';
			} else if ($i == 1) {
				$button['class'] = 'left';
			} else {
				$button['class'] = 'middle';
			}
?><a class="button <?=$button['class'];?> big" href="<?=$button['target'];?>" title="<?=$button['title'];?>"><strong><?=$button['count'];?></strong><br /><?=$button['text'];?></a><?
			$i++;
		endforeach;
	endif;
?>
			</p>
		</div>
		<div class="box">
			<h3><?=_('Administration');?></h3>
			<p>
				<a class="button left big" href="<?=site_url('admin/servers');?>"><strong><?=image_asset('icons-big/server.png');?></strong><br /><?=_('Manage servers');?>
				</a><a class="button middle big" href="<?=site_url('admin/programs');?>"><strong><?=image_asset('icons-big/application.png');?></strong><br /><?=_('Manage programs');?>
				</a><a class="button right big" href="<?=site_url('admin/users');?>"><strong><?=image_asset('icons-big/user.png');?></strong><br /><?=_('Manage users');?></a>
			</p>
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>
