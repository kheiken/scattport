<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Search');?></h2>
	</div>

	<div class="box">
		<form name="search" method="get" action"">
			<h3><?=_('New search')?></h3>
			<ul>
				<li>
					<?=form_label(_('Keyword'), 'query');?>
					<div>
						<input type="text" name="query" id="query" class="medium text" />
					</div>
				</li>
				<li>
					<a href="javascript:void(0);" onclick="$('form[name=search]').submit();" class="button search"><?=_('Search');?></a>
				</li>
			</ul>
		</form>
	</div>

<?php
	$results = count(array_merge($projects, $experiments));
?>
	<div class="box" name="search">
		<h3><?=sprintf(ngettext('%s Result found', '%s Results found', $results), $results);?> (<?=sprintf(_('%s seconds'), $this->benchmark->elapsed_time());?>)</h3>
<?php
	if ($results > 0):
		if (count($projects) > 0):
?>
		<h4>Projects</h4>
<?php
			foreach($projects as $project):
?>
			<p>
				<strong><?=anchor('projects/detail/' . $project['id'], $project['name']);?></strong><br />
				<?=character_limiter($project['description'], 255);?><br />
			</p>
<?php
			endforeach;
		endif;
		if (count($experiments)):
?>
		<h4>Experiments</h4>
<?php
			foreach($experiments as $experiment):
?>
			<p>
				<strong><?=anchor('experiments/detail/' . $experiment['id'], $experiment['name']);?></strong><br />
				<?=character_limiter($experiment['description'], 255);?><br />
			</p>
<?php
			endforeach;
		endif;
	else:
?>
		<p><?=_('No results found.');?></p>
<?php
	endif;
?>
	</div>

</div>

<?php $this->load->view('footer');?>
