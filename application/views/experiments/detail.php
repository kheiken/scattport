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
