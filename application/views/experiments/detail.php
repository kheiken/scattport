<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=anchor('projects', _('Projects'));?> &raquo; <?=anchor('projects/detail/' . $project['id'], $project['name']);?> &raquo; <?=$experiment['name'];?></h2>
	</div>

	<div class="box">
		<h3><?=_('Description');?></h3>
<?php
	if ($experiment['creator_id'] == $this->access->profile()->id || $this->access->isAdmin()):
?>
		<div class="editInPlace"><?=auto_typography($experiment['description']);?></div>
		<p></p>
<?php
	else:
?>
		<?=auto_typography($experiment['description']);?>	
<?php
	endif;
?>

		<h3>Actions</h3>
		<p>
<?php
	if ($experiment['creator_id'] == $this->access->profile()->id || $this->access->isAdmin()):
		if (isset($job['id'])):
			$disabled = true;
?>
			<a class="button disabled job_start"><?=_('Start job');?></a>
<?php
		else:
			$disabled = false
?>
			<a href="<?=site_url('jobs/start/' . $experiment['id']);?>" class="button job_start"><?=_('Start job');?></a>
<?php
		endif;
	else:
		$disabled = true;
	endif;
	if (!$disabled):
?>
			<a href="<?=site_url('experiments/copy/' . $experiment['id']);?>" class="button left copy"><?=_('Copy experiment');?>
			</a><a href="javascript:deleteConfirm('<?=site_url('experiments/delete/' . $experiment['id']);?>');" class="button right delete"><?=_('Delete experiment');?></a>
<?php
	else:
?>
			<a href="<?=site_url('experiments/copy/' . $experiment['id']);?>" class="button copy"><?=_('Copy experiment');?></a>
<?php
	endif;
	if ($experiment['creator_id'] == $this->access->profile()->id || $this->access->isAdmin()):
		if (isset($job['id']) && $job['finished_at'] != '0000-00-00 00:00:00'):
?>
			<a href="javascript:deleteConfirm('<?=site_url('experiments/delete/' . $experiment['id']);?>');" class="button delete"><?=_('Delete experiment');?></a>
<?php
		endif;
?>
			<a href="javascript:changeTitle('<?=$experiment['name'];?>', '<?=site_url('ajax/rename_experiment/' . $experiment['id']);?>');" class="button experiment_rename"><?=_('Change title');?></a>
<?php
	endif;
?>
		</p>
<?php
	if(ENVIRONMENT != "production" && !empty($job)):
?>
		<h3>Debugging actions</h3>
		<p>
			<a href="<?=site_url('debug/cancel_job/'.$experiment['id'].'/'.$job['id']);?>" onclick="javascript:return confirm('This will NOT have any effect on the calculation that may or may not be running in a simulator!\nThis simply resets the job to >not started< in the database')" class="button danger">Reset this job</a>
		</p>
<?php
	endif;
?>
	</div>
<?php
	if (isset($job['id'])):
?>

	<div class="box">
		<h3><?=_('Job details');?></h3>
		<p>
			<strong><?=_('Date started');?>:</strong> <?=relative_time($job['created_at']);?><br />
			<strong><?=_('Starter');?>:</strong> <?=anchor('users/profile/' . urldecode($job['username']), $job['firstname'] . ' ' . $job['lastname']);?><br />
			<strong><?=_('Server');?>:</strong> <?=(!empty($job['server'])) ? anchor('admin/servers/detail/' . urldecode($job['server']), $job['server']) : _('Not yet picked');?>

			<div class="progress_bar" style="width: 300px;">
				<strong><?=$job['progress']?>%</strong>
				<span style="width: <?=$job['progress']?>%;">&nbsp;</span>
			</div>
		</p>
<?php
		if ($job['finished_at'] != '0000-00-00 00:00:00'):
?>
		<p><a href="<?=site_url('results/experiment/' . $experiment['id']);?>" class="button results"><?=_('Show results');?></a></p>
<?php
		endif;
?>
	</div>
<?php
	endif;
?>

	<div class="box">
<?php 
	if (!$disabled && ($experiment['creator_id'] == $this->access->profile()->id || $this->access->isAdmin())):
?>
		<form name="editExperiment" method="post" action="<?=site_url('experiments/detail/' . $experiment['id']);?>">
<?php
	endif;
?>
			<h3><?=_('Configuration');?></h3>
			<table class="tableList">
				<thead>
					<tr>
						<th scope="col" width="40%"><?=_('Parameter');?></th>
						<th scope="col" width="40%"><?=_('Value');?></th>
						<th scope="col"><?=_('Unit');?></th>
					</tr>
				</thead>
				<tbody>
<?php
	foreach ($parameters as $param):
?>
					<tr>
						<td width="40%"><?=$param['readable'];?></td>
						<td width="41%">
<?php
		if ($experiment['creator_id'] == $this->access->profile()->id || $this->access->isAdmin()):
?>
							<input type="text" name="param-<?=$param['parameter_id'];?>" class="long text" value="<?=(!empty($_POST['param-' . $param['parameter_id']]) ? $this->input->post('param-' . $param['parameter_id']) : $param['value']);?>"<?=($disabled) ? ' disabled="disabled"' : '';?> />
<?php
			if (!empty($param['description'])):
?>
							<span class="form_info">
								<a href="<?=site_url('ajax/parameter_help/' . $param['parameter_id']);?>" name="<?=_('Description');?>" id="<?=$param['parameter_id'];?>" class="jtip">&nbsp;</a>
							</span>
<?php
			endif;
?>
							<?=form_error('params');?>
<?php
		else:
?>
							<?=($param['value']) ? $param['value'] : '-';?>
<?php
		endif;
?>
						</td>
						<td><?=$param['unit'];?></td>
					</tr>
<?php
	endforeach;
?>
				</tbody>
			</table>
<?php
	if ($experiment['creator_id'] == $this->access->profile()->id || $this->access->isAdmin()):
		if ($disabled):
?>
			<p>
				<a class="button save disabled"><?=_('Save changes');?></a>
			<p>
<?php
		else:
?>
			<p>
				<strong><?=_('Note');?>:</strong> <?=_('The existing job will be deleted.');?>
			</p>
			<p>
				<a href="javascript:void(0);" onclick="$('form[name=editExperiment]').submit();" class="button save"><?=_('Save changes');?></a>
			</p>
		</form>
<?php
		endif;
	endif;
?>
	</div>
</div>

<script>
$('.editInPlace').editInPlace({
	url: BASE_URL + 'ajax/update_experiment/' + '<?=$experiment['id']?>',
	saving_image: SITE_URL + 'assets/images/ajax-loader.gif',
	update_value: 'description',
	value_required: true
});
</script>

<?php $this->load->view('footer');?>
