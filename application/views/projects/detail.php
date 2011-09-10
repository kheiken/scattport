<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2>
			<?=anchor('projects', _('Projects'));?> &raquo; <?=$project['name'];?>

			<a class="share" href="<?=site_url('projects/shares/' . $project['id']);?>"><?=_(sprintf('Shared with %s people', count($shares)));?></a>
		</h2>
	</div>

	<div class="box">
		<h3><?=_('Description');?></h3>
		<div class="editInPlace"><?=auto_typography($project['description']);?></div>
		<p></p>

<?php
	if ($project['default_model'] != ''):
?>
		<canvas id="cv" style="border: #e8e8e8 1px solid;" width="120" height="120"></canvas>
		<p></p>

		<script type="text/javascript">
		var canvas = document.getElementById('cv');
		var viewer = new JSC3D.Viewer(canvas);
		viewer.setParameter('SceneUrl', BASE_URL + 'uploads/<?=$project['id'];?>/<?=$project['default_model'];?>');
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

		<h3><?=_('Experiments');?></h3>
		<table class="tableList">
			<thead>
				<tr>
					<th scope="col"><?=_('Name');?></th>
					<th scope="col"><?=_('Jobs');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	if (count($experiments) > 0):
		foreach ($experiments as $experiment):
?>
				<tr>
					<td><a href="<?=site_url('experiments/detail/' . $experiment['id']);?>" title="<?=sprintf(_('Show experiment &quot;%s&quot;'), $experiment['name']);?>"><?=$experiment['name'];?></a></td>
					<td><span class="active"><?=_('Completed');?></span></td>
					<td>
						<a href="<?=site_url('experiments/results/' . $experiment['id']);?>" title="<?=sprintf(_('Show results for this experiment'), $experiment['name']);?>"><?=_('Show results');?></a> |
						<a href="<?=site_url('experiments/create/' . $project['id'] . '/' . $experiment['id']);?>" title="<?=sprintf(_('Copy experiment &quot;%s&quot;'), $experiment['name']);?>"><?=_('Copy');?></a> |
						<a href="<?=site_url('experiments/edit/' . $experiment['id']);?>" title="<?=sprintf(_('Edit this experiment'), $experiment['name']);?>"><?=_('Edit');?></a> |
						<a href="<?=site_url('experiments/delete/' . $experiment['id']);?>" title="<?=sprintf(_('Delete experiment'), $experiment['name']);?>"><?=_('Delete');?></a>
					</td>
				</tr>
<?php
		endforeach;
	else:
?>
				<tr>
					<td colspan="3"><?=_('No experiments available.');?></td>
				</tr>
<?php
	endif;
?>
			</tbody>
		</table>

		<p><a class="button add" href="<?=site_url('experiments/create/' . $project['id']);?>"><?=_('Create experiment');?></a></p>
	</div>

	<div class="title">
		<h2><?=_('Recent jobs');?></h2>
	</div>

	<div class="box">
		<table class="tableList">
			<thead>
				<tr>
					<th scope="col"><?=_('Experiment');?></th>
					<th scope="col"><?=_('Started');?></th>
					<th scope="col"><?=_('Finished');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	if (count($jobs) > 0):
		foreach ($jobs as $job):
?>
				<tr>
					<td><?=$job['name'];?></td>
					<td><?=$job['started_at'];?></td>
					<td><?=$job['finished_at'] != '0000-00-00 00:00:00' ? $job['finished_at'] : _('Currently running');?></td>
					<td>
						<a href="<?=site_url('experiments/results/' . $job['id']);?>" title="<?= sprintf(_('Show results for this experiment'), $job['name']);?>"><?=_('Show results');?></a> |
						<a href="<?=site_url('experiments/edit/' . $job['id']);?>" title="<?= sprintf(_('Edit this experiment'), $job['name']);?>"><?=_('Edit');?></td>
				</tr>
<?php
		endforeach;
	else:
?>
				<tr>
					<td colspan="4"><?=_('No jobs found.');?></td>
				</tr>
<?php
	endif;
?>
			</tbody>
		</table>
	</div>

</div>

<?php $this->load->view('footer');?>
