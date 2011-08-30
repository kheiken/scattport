<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Project details');?></h2>
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
		<h3><?=_('Trials');?></h3>
		<table class="tableList">
			<thead>
				<tr>
					<th scope="col"><?=_('Trial');?></th>
					<th scope="col"><?=_('Jobs');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	if (count($trials) > 0):
		foreach ($trials as $trial):
?>
				<tr>
					<td><a href="<?=site_url('trials/' . $trial['id']);?>" title="<?=sprintf(_("Show trial '%s'"), $trial['name']);?>"><?=$trial['name'];?></a></td>
					<td><span class="active"><?=_('Completed');?></span></td>
					<td>
						<a href="<?=site_url('trials/results/' . $trial['id']);?>" title="<?=sprintf(_('Show results for the trial &quot;%s&quot;'), $trial['name']);?>"><?=_('Show results');?></a> |
						<a href="<?=site_url('trials/create/' . $project['id'] . '/' . $trial['id']);?>" title="<?=sprintf(_('Copy trial &quot;%s&quot;'), $trial['name']);?>"><?=_('Copy');?></a> |
						<a href="<?=site_url('trials/edit/' . $trial['id']);?>" title="<?=sprintf(_('Edit trial &quot;%s&quot;'), $trial['name']);?>"><?=_('Edit');?></a> |
						<a href="<?=site_url('trials/delete/' . $trial['id']);?>" title="<?=sprintf(_('Delete trial &quot;%s&quot;'), $trial['name']);?>"><?=_('Delete');?></a>
					</td>
				</tr>
<?php
		endforeach;
	else:
?>
				<tr>
					<td colspan="3"><?=_('No trials available.');?></td>
				</tr>
<?php
	endif;
?>
			</tbody>
		</table>

		<p><a class="button add" href="<?=site_url('trials/create/' . $project['id']);?>"><?=_('Create a new trial');?></a>
	</div>

	<div class="title">
		<h2><?=_('Recent jobs');?></h2>
	</div>

	<div class="box">
		<table class="tableList">
			<thead>
				<tr>
					<th scope="col"><?=_('Trial');?></th>
					<th scope="col"><?=_('Started');?></th>
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
					<td>
						<a href="<?=site_url('trials/results/' . $job['id']);?>" title="<?= sprintf(_('Show results for the trial &quot;%s&quot;'), $job['name']);?>"><?=_('Show results');?></a> |
						<a href="<?=site_url('trials/edit/' . $job['id']);?>" title="<?= sprintf(_('Edit trial &quot;%s&quot;'), $job['name']);?>"><?=_('Edit');?></td>
				</tr>
<?php
		endforeach;
	else:
?>
				<tr>
					<td colspan="3"><?=_('No jobs found.');?></td>
				</tr>
<?php
	endif;
?>
			</tbody>
		</table>
	</div>

</div>

<?php $this->load->view('footer');?>
