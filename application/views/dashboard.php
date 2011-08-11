<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2><?=_('Dashboard');?></h2>
	</div>

	<div class="box">
		<h3><?=_('Projects');?></h3>
		<p>
			<a class="button left big" href="projects/create"><?=_('Create a project');?></a><a class="button middle big" href="projects"><?=_('Show projects');?></a><a class="button right big" href="#"><?=_('Search projects');?></a>
		</p>
	</div>
	<div class="box">
		<h3><?=_('Calculations');?></h3>
		<p>
			<a class="button left big" href="#"><?=_('Newest results');?></a><a class="button middle big" href="#"><?=_('Running calculations');?></a>
		</p>
	</div>
	<div class="box">
		<h3><?=_('Administration');?></h3>
		<p>
			<a class="button left big" href="#"><?=_('Manage servers');?></a><a class="button middle big" href="#"><?=_('Manage applications');?></a><a class="button right big" href="<?=site_url('users');?>"><?=_('Manage users');?></a>
		</p>
	</div>

</div>

<?php $this->load->view('footer'); ?>
