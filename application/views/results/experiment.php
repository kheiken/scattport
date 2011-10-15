<?php $this->load->view('header');?>

<div id="content">
	<div class="title">
		<h2><?=anchor('projects', _('Projects'));?> &raquo; <?=anchor('projects/detail/' . $project['id'], $project['name']);?> &raquo; <?=anchor('experiments/detail/' . $experiment['id'], $experiment['name']);?> &raquo; <?=_('Results');?></h2>
	</div>

	<div class="box">
		<h3><?=_('Results');?></h3>
<?php
	if (count($results) > 0):
?>
		<p>
		<h4>Downloads results</h4>
			<a href="<?=site_url('results/download/'.$experiment['id']).'/out';?>" class="button left download"><?=_('Results (.out)');?></a><a href="<?=site_url('results/download/'.$experiment['id']).'/tma';?>" class="button middle download"><?=_('T-Matrix (.tma)');?></a><a href="<?=site_url('results/download/'.$experiment['id']).'/log';?>" class="button right download"><?=_('Application output (.log)');?></a>
		</p>
<?php
	endif;
?>
		<table class="tableList">
<?php
	$i = 0;
	foreach ($results as $result):
		if ($i == 0):
?>
			<thead>
				<tr>
<?php
			foreach ($result as $headline):
?>
					<th scope="col"><?=$headline;?></th>
<?php
			endforeach;
?>
				</tr>
			</thead>
<?php
		else:
			if ($i == 1):
?>
			<tbody>
<?php
			endif;
?>
				<tr>
<?php
			foreach ($result as $value):
?>
					<td><?=$value;?></td>
<?php
			endforeach;
?>
				</tr>
<?php
			if ($i == sizeof($results)):
?>
			</tbody>
<?php
			endif;
		endif;
		$i++;
	endforeach;
?>
		</table>
	</div>
</div>

<?php $this->load->view('footer');?>
