<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2>
			<?=anchor('projects', _('Projects'));?> &raquo; <?=$project['name'];?>
			<a class="share" href="<?=site_url('projects/shares/' . $project['id']);?>"><?=_(sprintf('Shared with %s people', count($shares)));?></a>
		</h2>
	</div>

	<div class="box">
<?php
	if ($project['owner'] == $this->access->profile()->id || $this->access->isAdmin()):
?>
		<h3><?=_('Description');?></h3>
		<p>
			<div class="editInPlace"><?=auto_typography($project['description']);?></div>
		</p>

		<h3><?=_('Actions');?></h3>
		<p>
			<a href="javascript:deleteConfirm('<?=site_url('projects/delete/' . $project['id']);?>');" class="button delete"><?=_('Delete project');?></a>
			<a href="javascript:changeTitle('<?=$project['name'];?>', '<?=site_url('ajax/rename_project/' . $project['id']);?>');" class="button project_rename"><?=_('Change title');?></a>
		</p>
<?php
	else:
?>
		<h3><?=_('Description');?></h3>
		<?=auto_typography($project['description']);?>
<?php
	endif;
?>
	</div>

	<div class="box">
		<h3><?=_('Experiments');?></h3>
		<table class="tableList">
			<thead>
				<tr>
					<th scope="col"><?=_('Name');?></th>
<?php
	if (count($shares) > 0 || $project['public'] == 1):
?>
					<th scope="col"><?=_('Creator');?></th>
<?php
	endif;
?>
					<th scope="col"><?=_('Jobs');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	if (count($experiments) > 0):
		foreach ($experiments as $experiment):
			if (empty($experiment['job_id'])) {
				$job['css'] = '';
				$job['status'] = _('No job started');
			} else if ($experiment['started_at'] == 0) {
				$job['css'] = 'closed';
				$job['status'] = _('Waiting');
			} else if ($experiment['finished_at'] == 0) {
				$job['css'] = 'pending';
				$job['status'] = _('Running');
			} else {
				$job['css'] = 'active';
				$job['status'] = _('Completed');
			}
?>
				<tr>
					<td><a href="<?=site_url('experiments/detail/' . $experiment['id']);?>" title="<?=sprintf(_('Show experiment &quot;%s&quot;'), $experiment['name']);?>"><?=$experiment['name'];?></a></td>
<?php
			if (count($shares) > 0 || $project['public'] == 1):
				if ($experiment['creator_id'] == $this->access->profile()->id):
?>
					<td><?=_('You');?></td>
<?php
				else:
					$user = $this->user->getById($experiment['creator_id']);
?>
					<td><?=anchor('users/profile/' . urlencode($user['username']), $user['firstname'].' '.$user['lastname']);?></td>
<?php
				endif;
			endif;
?>
					<td><span class="<?=$job['css'];?>"><?=$job['status'];?></span></td>
					<td>
<?php
			if ($experiment['finished_at'] != 0):
?>
						<a href="<?=site_url('results/experiment/' . $experiment['id']);?>" title="<?=sprintf(_('Show results for this experiment'), $experiment['name']);?>"><?=_('Show results');?></a> |
<?php
			endif;
?>
						<a href="<?=site_url('experiments/create/' . $project['id'] . '/' . $experiment['id']);?>" title="<?=sprintf(_('Copy experiment &quot;%s&quot;'), $experiment['name']);?>"><?=_('Copy');?></a>
<?php
			if ($experiment['creator_id'] == $this->access->profile()->id || $this->access->isAdmin()):
?>
						| <a href="<?=site_url('experiments/edit/' . $experiment['id']);?>" title="<?=sprintf(_('Edit this experiment'), $experiment['name']);?>"><?=_('Edit');?></a>
<?php
				if ($job['css'] == 'closed' || $job['css'] == ''):
?>
						| <a href="javascript:deleteConfirm('<?=site_url('experiments/delete/' . $experiment['id']);?>');" title="<?=sprintf(_('Delete this experiment'), $experiment['name']);?>"><?=_('Delete');?></a>
<?php
				endif;
			endif;
?>
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
	</div>

	<div class="box">
		<h3><?=_('Recent jobs');?></h3>
		<table class="tableList">
			<thead>
				<tr>
					<th scope="col"><?=_('Experiment');?></th>
					<th scope="col"><?=_('Status');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	if (count($jobs) > 0):
		foreach ($jobs as $job):
			if($job['status'] == 'pending') {
				$job['humanstatus'] = _('Pending');
				$job['cssclass'] = 'closed';
			} elseif($job['status'] == 'running') {
				$job['humanstatus'] = _('Simulation running');
				$job['cssclass'] = 'pending';
			} elseif($job['status'] == 'complete') {
				$job['humanstatus'] = _('Simulation complete');
				$job['cssclass'] = 'active';
			}
?>
				<tr>
					<td><?=$job['name'];?></td>
					<td><span class="<?=$job['cssclass'];?>"><?=$job['humanstatus'];?></span></td>
					<td>
						<a href="<?=site_url('results/experiment/' . $job['id']);?>" title="<?= sprintf(_('Show results for this experiment'), $job['name']);?>"><?=_('Show results');?></a> |
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

<script>
$('.editInPlace').editInPlace({
	url: BASE_URL + 'ajax/update_project/' + '<?=$project['id']?>',
	saving_image: SITE_URL + 'assets/images/ajax-loader.gif',
	update_value: 'description',
	value_required: true
});
</script>

<?php $this->load->view('footer');?>
