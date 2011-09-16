<!DOCTYPE html>
<html lang="<?=substr($this->config->item('language'), 0, 2);?>">
<head>

<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7,9; chrome=1" />

<title>ScattPort</title>

<?=css_asset('style.css');?>
<?=css_asset('table.css');?>
<?=css_asset('form.css');?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" language="javascript" type="text/javascript"></script>

<?=js_asset('jquery.editinplace.js');?>
<?=js_asset('jquery.tablednd.js');?>
<?=js_asset('jquery.alerts.js');?>

<?=js_asset('minmax.js');?>
<?=js_asset('jsc3d.min.js');?>
<?=js_asset('scattport.js');?>
<?=js_asset('jtip.js');?>

<script type="text/javascript">
	var SITE_URL = '<?=site_url("/");?>';
	var BASE_URL = '<?=base_url("/");?>';
	var JOBS_CHECK_INTERVAL = <?=$this->access->settings('jobs_check_interval');?>;
</script>

</head>

<body>

<div id="header">
	<h1><?=anchor('', image_asset('logo.png'));?></h1>
	<div class="status">
		<?=_('Current project');?>:&nbsp;
		<select name="activeProject">
<?php
	// get the active project, if there is one
	if ($this->input->get('active_project'))
		$active_project = $this->project->getById($this->input->get('active_project'));
	else if (isset($project['id']))
		$active_project = $project;
	else
		$active_project = false;

	if(!$active_project):
?>
			<option disabled="disabled" selected="selected"><strong><?=_('Select a project');?></strong></option>
<?php
	endif;
	if ($this->access->isAdmin()) {
		$theProjects = $this->project->getAll();
	} else {
		$theProjects = $this->project->getAccessible();
	}
	foreach ($theProjects as $project):
?>
			<option title="<?=$project['name']?>" value="<?=site_url('projects/detail/' . $project['id']) . '?active_project=' . $project['id'];?>"<?=($active_project['id'] == $project['id']) ? ' selected' : '';?>><?=$project['mediumname'];?></option>
<?php
	endforeach;
?>
		</select>
	</div>
	<div class="menu"><?=_('Hello,');?> <a href="<?=site_url('users/profile/' . $this->access->profile()->username);?>"><?=$this->access->profile()->firstname;?> <?=$this->access->profile()->lastname;?></a>! | <?=lang_select('assets/images');?> | <a href="#"><?=_('Help');?></a> | <?=anchor('auth/settings', _('Settings'));?> | <?=anchor('auth/logout', _('Logout'));?></div>
</div>

<div id="wrapper">
<div id="notifications"></div>

	<div id="sidebar">
<?php
	if ($active_project):
?>
		<div class="title">
			<h2><?=_('Current project');?></h2>
		</div>

		<div class="box">
<?php
		if ($active_project['default_model'] != ''):
?>
			<canvas id="model" width="190" height="160"></canvas>

			<script type="text/javascript">
			var canvas = document.getElementById('model');
			var viewer = new JSC3D.Viewer(canvas);
			viewer.setParameter('SceneUrl', BASE_URL + 'uploads/<?=$active_project['id'];?>/<?=$active_project['default_model'];?>');
			viewer.setParameter('InitRotationX', -20);
			viewer.setParameter('InitRotationY', 20);
			viewer.setParameter('InitRotationZ', 0);
			viewer.setParameter('ModelColor', '#cccccc');
			viewer.setParameter('BackgroundColor1', '#ffffff');
			viewer.setParameter('BackgroundColor2', '#ffffff');
			viewer.setParameter('RenderMode', 'flat');
			viewer.init();
			viewer.update();
			</script>
<?php
		endif;
?>
			<p>
				<strong><?=_('Name');?>:</strong> <?=anchor('projects/detail/' . $active_project['id'], $active_project['shortname']);?><br />
				<strong><?=_('Experiments');?>:</strong> <?=$this->project->countExperiments($active_project['id']);?><br />
			</p>
			<p>
				<a href="<?=site_url('experiments/create/' . $active_project['id']);?>" class="button add" title="<?=sprintf(_('Create a new experiment for the project &quot;%s&quot;'), $active_project['name']);?>"><?=_('New experiment');?></a>
			</p>
		</div>

<?php
	endif;
?>
		<div class="title">
			<h2><?=_('Navigation');?></h2>
		</div>
		<div class="navigation">
			<ul>
				<li class="togglable" id="nav_projects">
					<a href="javascript:void(0);"><?=_('Projects');?></a>
					<ul>
						<li><a href="<?=site_url('projects/create');?>" title="<?=_('Create a new project');?>"><?=_('New project');?></a></li>
						<li><a href="<?=site_url('projects');?>" title="<?=_('Shows a list of all projects');?>"><?=_('Show projects');?></a></li>
					</ul>
				</li>
				<li class="togglable" id="nav_calculations">
					<a href="javascript:void(0);"><?=_('Calculations');?></a>
					<ul>
						<li><a href="<?=site_url('jobs/results');?>" title="<?=_('Show the newest results');?>"><?=_('Newest results');?></a></li>
						<li><a href="<?=site_url('jobs/running');?>" title="<?=_('Shows a list of running calculations');?>"><?=_('Running calculations');?></a></li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="title">
			<h2><?=_('Projects');?></h2>
		</div>
		<div class="navigation">
			<ul>
				<li class="togglable" id="nav_own_projects">
					<a href="javascript:void(0);"><?=_('My projects');?></a>
					<ul>
<?php
	$projects = $this->project->getOwn();
	if (count($projects) > 0):
		foreach ($this->project->getOwn() as $project):
?>
						<li><a href="<?=site_url('projects/detail/' . $project['id'] . '?active_project=' . $project['id']);?>"<?=($project['public'] == 1) ? ' class="public"' : (($project['shares'] > 0) ? ' class="share"' : ' class="folder"');?>><?=$project['mediumname'];?></a></li>
<?php
		endforeach;
	else:
?>
						<li><?=_("You haven't created a project yet.");?></li>
						<li><?=anchor('projects/create', _('Create a project now'));?></li>
<?php
	endif;
?>
					</ul>
				</li>
<?php
	$projects = $this->project->getShared();
	if (count($projects) > 0):
?>
				<li class="togglable" id="nav_shared_projects">
					<a href="javascript:void(0);"><?=_('Projects shared with me');?></a>
					<ul>
<?php
	foreach ($projects as $project):
?>
						<li><?=anchor('projects/detail/' . $project['id'] . '?active_project=' . $project['id'], $project['mediumname'], 'class="folder"');?></li>
<?php
	endforeach;
?>
					</ul>
				</li>
<?php
	endif;

	$projects = $this->project->getPublic();
	if (count($projects) > 0):
?>
				<li class="togglable" id="nav_public_projects">
					<a href="javascript:void(0);"><?=_('Public projects');?></a>
					<ul>
<?php
	foreach ($projects as $project):
?>
						<li><?=anchor('projects/detail/' . $project['id'] . '?active_project=' . $project['id'], $project['mediumname'], 'class="folder"');?></li>
<?php
	endforeach;
?>
					</ul>
				</li>
<?php
	endif;
?>
			</ul>
		</div>

		<div class="title">
			<h2><?=_('Search');?></h2>
		</div>

		<div class="box">
			<form id="search-form" method="get" action="<?=site_url('search');?>">
				<input type="text" name="query" id="search-input" class="search-input" />
				<input type="submit" id="search-submit" class="search-submit" value="<?=_('Search');?>" />
			</form>
		</div>

	</div>
