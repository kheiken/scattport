<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=anchor('projects', _('Projects'));?> &raquo; <?=anchor('projects/detail/' . $project['id'], $project['name']);?> &raquo; <?=$experiment['name'];?></h2>
	</div>

	<div class="box">
		<h3><?=_('Description');?></h3>
		<div class="editInPlace"><?=auto_typography($experiment['description']);?></div>
		<p></p>

		<h3>Actions</h3>
		<p>
<?php
	if (isset($job['id'])):
?>
			<a class="button disabled job_start"><?=_('Start job');?></a>
<?php
	else:
?>
			<a href="<?=site_url('jobs/start/' . $experiment['id']);?>" class="button job_start"><?=_('Start job');?></a>
<?php
	endif;
?>
			<a href="<?=site_url('experiments/copy/' . $experiment['id']);?>" class="button left copy"><?=_('Copy experiment');?>
			</a><a href="javascript:deleteConfirm('<?=site_url('experiments/delete/' . $experiment['id']);?>');" class="button right delete"><?=_('Delete experiment');?></a>
			<a href="javascript:changeTitle('<?=$experiment['name'];?>', '<?=site_url('ajax/rename_experiment/' . $experiment['id']);?>');" class="button rename"><?=_('Change title');?></a>
		</p>
	</div>

	<div class="box">
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
						<input type="text" name="param-<?=$param['parameter_id'];?>" class="long text" value="<?=(!empty($_POST['param-' . $param['parameter_id']]) ? $this->input->post('param-' . $param['parameter_id']) : $param['value']);?>" />
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
					</td>
					<td><?=$param['unit'];?></td>
				</tr>
<?php
	endforeach;
?>
			</tbody>
		</table>
		<p>
			<a href="javascript:void(0);" class="button save"><?=_('Save changes');?></a>
		</p>
	</div>

<?php
	if (isset($job['id'])):
?>
	<div class="box">
		<h3><?=_('Job details');?></h3>
		<p>
			<strong><?=_('Date started');?>:</strong> <?=relative_time($job['started_at']);?><br />
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
		<p><a href="<?=site_url('results/show/' . $experiment['id']);?>" class="button results"><?=_('Show results');?></a></p>
<?php
		endif;
?>
	</div>
<?php
	endif;
?>

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
