<!DOCTYPE html>
<html lang="<?=substr($this->config->item('language'), 0, 2);?>">
<head>

<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7,9; chrome=1" />

<title>ScattPort</title>

<?=css_asset('style.css');?>
<?=css_asset('table.css');?>
<?=css_asset('form.css');?>

<?=script_tag('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');?>

<?=js_asset('tablednd.jquery.js');?>
<?=js_asset('jsc3d.min.js');?>
<?=js_asset('scattport.js');?>
<?=js_asset('jtip.js');?>

<script type="text/javascript">
	var SITE_URL = '<?=site_url("/");?>';
	var BASE_URL = '<?=base_url("/");?>';
</script>

</head>

<body>

<div id="header">
	<h1><?=anchor('', image_asset('logo.png'));?></h1>
	<div class="status">
		<select name="activeProject">
<?php
	$projects = $this->project->getAll();
	foreach ($projects as $project):
?>
			<option value="<?=site_url('projects/detail/' . $project['id'] . '?active_project=' . $project['id']);?>"<?=($this->input->get('active_project') == $project['id']) ? ' selected' : '';?>><?=$project['name'];?></option>
<?php
	endforeach;
?>
		</select>
	</div>
	<div class="menu"><?= _('Hello,') ?> <a href="<?=base_url();?>"><?=$this->user->profile()->firstname;?> <?=$this->user->profile()->lastname;?></a>! | <?=lang_select('assets/images');?> | <a href="#"><?=_('Help');?></a> | <?=anchor('auth/settings', _('Settings'));?> | <?=anchor('auth/logout', _('Logout'));?></div>
</div>

<div id="wrapper">
<div id="notifications"></div>

	<div id="sidebar">

		<div class="title">
			<h2><?=_('Actions');?></h2>
		</div>
		<div class="navigation">
			<ul>
<?php
	if ($this->input->get('active_project')):
		$active_project = $this->project->getById($this->input->get('active_project'));
?>
				<li>
					<a href="javascript:void(0);" onclick="$(this).parent().toggleClass('active').find('ul').toggle();"><?=_('Project');?> <?=$active_project['name'];?></a>
					<ul>
						<li><a href="<?=site_url('projects/detail/' . $active_project['id']);?>" title="<?=_('Show overview');?>"><?=_('Overview');?></a></li>
						<li><a href="<?=site_url('trials/create/' . $active_project['id']);?>" title="<?=sprintf(_('Create a new trial for the project &quot;%s&quot;'), $active_project['name']);?>"><?=_('New trial');?></a></li>
						<li><a href="<?=site_url('results/project/' . $active_project['id']);?>" title="<?=sprintf(_('Show results for the project &quot;%s&quot;'), $active_project['name']);?>"><?=_('Show results');?></a></li>
					</ul>
				</li>
<?php
	endif;
?>
				<li class="togglable" id="nav_projects">
					<a href="javascript:void(0);"><?=_('Projects');?></a>
					<ul>
						<li><a href="<?=site_url('projects/create');?>" title="<?=_('Create a new project');?>"><?=_('New project');?></a></li>
						<li><a href="<?=site_url('projects');?>" title="<?=_('Shows a list of all projects');?>"><?=_('Show projects');?></a></li>
						<li><a href="<?=site_url('projects/search');?>" title="<?=_('Search for a project');?>"><?=_('Search');?></a></li>
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
			<h2><a href="<?=site_url('projects');?>" title="<?=_('Show all projects');?>"><?=_('Projects');?></a></h2>
		</div>
		<div class="navigation">
			<ul>
				<li class="togglable" id="nav_own_projects">
					<a href="javascript:void(0);"><?=_('Own projects');?></a>
					<ul>
<?php
	$projects = $this->project->getOwn();
	foreach($projects as $project):
?>
						<li><a href="<?=site_url('projects/detail/'.$project['id']);?>"><?=$project['name'];?></a></li>
<?php
	endforeach;
?>
					</ul>
				</li>
				<li class="togglable" id="nav_shared_projects">
					<a href="javascript:void(0);"><?=_('Projects shared with me');?></a>
					<ul>
						<li><a href="#">Prisma</a></li>
					</ul>
				</li>
				<li class="togglable" id="nav_public_projects">
					<a href="javascript:void(0);"><?=_('Public projects');?></a>
					<ul>
						<li><a href="#">Beispielprojekt</a></li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="title">
			<h2><?=_('Search');?></h2>
		</div>

		<div class="box">
			<form id="search-form" method="get" action="#">
				<input type="text" name="search" id="search-input" class="search-input" />
				<input type="image" src="<?=asset_url('images', 'button-search.gif');?>" id="search-submit" class="search-submit" />
			</form>
		</div>

		<div class="title">
			<h2><?=_('Recent events');?></h2>
		</div>

		<div class="box">
			<ul id="blog">
				<li><h4><a href="#" title="<?=_('Calculation done');?>"><?=_('Calculation done');?></a> <abbr title="22.07.2011">22.07.2011</abbr></h4><p><?=sprintf(_('Calculation successfully finished for project &quot;%s&quot;'), 'Gerstenkorn');?></i></p></li>
				<li><h4><a href="#" title="<?=_('Calculation done');?>"><?=_('Calculation done');?></a> <abbr title="22.07.2011">22.07.2011</abbr></h4><p><?=sprintf(_('Calculation successfully finished for project &quot;%s&quot;'), 'Gerstenkorn');?></i></p></li>
				<li><h4><a href="#" title="<?=_('Calculation done');?>"><?=_('Calculation done');?></a> <abbr title="22.07.2011">22.07.2011</abbr></h4><p><?=sprintf(_('Calculation successfully finished for project &quot;%s&quot;'), 'Gerstenkorn');?></i></p></li>
			</ul>
		</div>

	</div>
