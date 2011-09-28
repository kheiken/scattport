<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=$user->firstname;?> <?=$user->lastname;?></h2>
	</div>

	<div class="box">
		<h3><?=_('Personal information');?></h3>
		<p>
			<strong><?=_('Name');?>:</strong> <?=$user->firstname;?> <?=$user->lastname;?><br />
			<strong><?=_('Group');?>:</strong> <?=$user->group_description;?><br />
			<strong><?=_('Institution');?>:</strong> <?=$user->institution;?><br />
			<strong><?=_('Phone number');?>:</strong> <?=$user->phone;?><br />
			<strong><?=_('Last activity');?>:</strong> <?=relative_time((integer) $user->last_activity);?><br />
		</p>
		<p>
			<?=safe_mailto($user->email, _('Send email'), 'class="button mail"');?>
		</p>

		<h3>ScattPort-<?=_('Statistics');?></h3>
		<p>
			<strong><?=_('Projects');?>:</strong> <?=$this->project->count($user->id);?><br />
			<strong><?=_('Experiments');?>:</strong> <?=$this->experiment->count($user->id);?><br />
		</p>
	</div>

</div>

<?php $this->load->view('footer');?>
