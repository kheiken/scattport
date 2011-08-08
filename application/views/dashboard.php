<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2><?=lang('dashboard');?></h2>
	</div>

	<div class="box">
		<h3><?=lang('projects');?></h3>
		<p>
			<a class="button left big" href="projects/create"><?=lang('projects_create');?></a><a class="button middle big" href="projects"><?=lang('projects_manage');?></a><a class="button right big" href="#"><?=lang('projects_search');?></a>
		</p>
	</div>
	<div class="box">
		<h3><?=lang('calculations');?></h3>
		<p>
			<a class="button left big" href="#"><?=lang('newest_results');?></a><a class="button middle big" href="#"><?=lang('calculations_running');?></a>
		</p>
	</div>
	<div class="box">
		<h3><?=lang('administration');?></h3>
		<p>
			<a class="button left big" href="#"><?=lang('servers_manage');?></a><a class="button middle big" href="#"><?=lang('programs_manage');?></a><a class="button right big" href="#"><?=lang('users_manage');?></a>
		</p>
	</div>

</div>

<?php $this->load->view('footer'); ?>
