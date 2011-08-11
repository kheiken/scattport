<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>ScattPort</title>

<?=link_tag('assets/css/style.css');?>
<?=link_tag('assets/css/table.css');?>
<?=link_tag('assets/css/form.css');?>

<?=script_tag('assets/js/minmax.js');?>
<?=script_tag('https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');?>
<?=script_tag('assets/js/scattport.js');?>
<?=script_tag('assets/js/tablednd.jquery.js');?>
<script type="text/javascript">
	var SITE_URL = '<?=site_url()?>';
</script>
</head>
<body>

<div id="header">
	<h1><?=anchor('', img('assets/images/logo.png'))?></h1>
	<div class="status">
		<select name="activeProject">
			<option value="<?=site_url('');?>">Beispielprojekt</option>
			<option value="<?=site_url('projects');?>">Projekte verwalten</option>
		</select>
	</div>
	<div class="menu"><?= _('Hello,') ?> <a href="<?=site_url('');?>"><?=$this->user->profile()->firstname;?> <?=$this->user->profile()->lastname;?></a>! | <?=lang_select('assets/images');?> | <a href="#"><?=_('Help')?></a> | <?=anchor('settings', _('Settings'));?> | <?=anchor('auth/logout', _('Logout'));?></div>
</div>

<div id="wrapper">
<div id="notifications"></div>

	<div id="sidebar">

		<div class="title">
			<h2><?=_('Actions')?></h2>
		</div>
		<div class="navigation">
			<ul>
<?
	if($this->session->userdata('active_project')):
		$active_project = $this->project->getById($this->session->userdata('active_project'));
?>
				<li><?=_('Project')?> <?=$active_project['name']?>
					<ul>
						<li><a href="<?=site_url('projects/detail/'.$active_project['id'])?>" title="<?=_('Show overview')?>"><?=_('Overview')?></a></li>
						<li><a href="<?=site_url('trials/create/'.$active_project['id'])?>" title="<?=sprintf(_('Create a new trial for the project &quot;%s&quot;'), $active_project['name'])?>"><?=_('New trial')?></a></li>
						<li><a href="<?=site_url('results/project/'.$active_project['id'])?>" title="<?=sprintf(_('Show results for the project &quot;%s&quot;'), $active_project['name'])?>"><?=_('Show results')?></a></li>
					</ul>
				</li>
<?
	endif;
?>
				<li><?=_('Global')?>
					<ul>
						<li><a href="<?=site_url('projects/create')?>" title="<?=_('Create a new project')?>"><?=_('New project')?></a></li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="title">
			<h2><a href="<?=site_url('projects')?>" title="<?=_('Show all projects')?>"><?=_('Projects')?></a></h2>
		</div>
		<div class="navigation">
			<ul>
				<li><a href="#"><?=_('Own projects')?></a>
					<ul>
<?
	$projects = $this->project->getOwn();
	foreach($projects as $project):
?>
						<li><a href="<?=site_url('projects/detail/'.$project['id'])?>"><?=$project['name']?></a></li>
<?
	endforeach;
?>
					</ul>
				</li>
				<li><a href="#"><?=_('Projects shared with me')?></a>
					<ul>
						<li><a href="#">Prisma</a></li>
					</ul>
				</li>
				<li><a href="#"><?=_('Public projects')?></a>
					<ul>
						<li><a href="#">Beispielprojekt</a></li>
					</ul>
				</li>
			</ul>
		</div>

		<div class="title">
			<h2><?=_('Search')?></h2>
		</div>

		<div class="box">
			<form id="search-form" method="get" action="#">
				<input type="text" name="search" id="search-input" class="search-input">
				<input type="image" src="<?=site_url('assets/images/button-search.gif')?>" id="search-submit" class="search-submit">
			</form>
		</div>

		<div class="title">
			<h2><?=_('Recent events')?></h2>
		</div>

		<div class="box">
			<ul id="blog">
				<li><h4><a href="#" title="<?=_('Calculation done')?>"><?=_('Calculation done')?></a> <abbr title="22.07.2011">22.07.2011</abbr></h4><p><?=sprintf(_('Calculation successfully finished for project &quot;%s&quot;'), 'Gerstenkorn')?></i></p></li>
				<li><h4><a href="#" title="<?=_('Calculation done')?>"><?=_('Calculation done')?></a> <abbr title="22.07.2011">22.07.2011</abbr></h4><p><?=sprintf(_('Calculation successfully finished for project &quot;%s&quot;'), 'Gerstenkorn')?></i></p></li>
				<li><h4><a href="#" title="<?=_('Calculation done')?>"><?=_('Calculation done')?></a> <abbr title="22.07.2011">22.07.2011</abbr></h4><p><?=sprintf(_('Calculation successfully finished for project &quot;%s&quot;'), 'Gerstenkorn')?></i></p></li>
			</ul>
		</div>

	</div>
