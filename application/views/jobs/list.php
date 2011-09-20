<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Jobs');?></h2>
	</div>

	<ul class="tabs">
		<li class="active"><a href="#finished"><?=sprintf(ngettext('%d finished job', '%d finished jobs', count($finished_jobs)), count($finished_jobs));?></a></li>
		<li><a href="#running"><?=sprintf(ngettext('%d running job', '%d running jobs', count($running_jobs)), count($running_jobs));?></a></li>
		<li><a href="#pending"><?=sprintf(ngettext('%d pending job', '%d pending jobs', count($pending_jobs)), count($pending_jobs));?></a></li>
	</ul>

	<div class="tab_container">
		<div id="finished" class="tab_content">
			<h3><?=_('List of all finished jobs');?></h3>
			<table class="tableList">
				<thead>
					<tr>
						<th scope="col"><?=_('Name');?></th>
						<th scope="col"><?=_('Finished at');?></th>
						<th scope="col"><?=_('Actions');?></th>
					</tr>
				</thead>
				<tbody>
<?php
	if (count($finished_jobs) > 0):
		foreach ($finished_jobs as $job):
?>
					<tr>
						<td>
							<?=anchor('experiments/detail/' . $job['experiment_id'], $job['name']);?>
							<?=($job['seen'] == 0) ? image_asset('icons/new-text.png', 'class="icon"') : '';?>
						</td>
						<td><?=relative_time($job['finished_at']);?></td>
						<td>
							<a href="<?=site_url('results/experiment/' . $job['experiment_id']);?>" title="<?= sprintf(_('Show results for this experiment'), $job['name']);?>"><?=_('Show results');?></a>
						</td>
					</tr>
<?php
		endforeach;
	else:
?>
					<tr>
						<td colspan="3"><?=_('No finished jobs found.');?></td>
					</tr>
<?php
	endif;
?>
				</tbody>
			</table>
			<h3><?=_('Actions');?></h3>
			<p>
				<a href="<?=site_url('jobs/mark_all_seen');?>" class="button"><?=_('Mark all as seen');?></a>
				<a class="button disabled search"><?=_('Search job');?></a>
			</p>
		</div>
		<div id="running" class="tab_content">
			<h3><?=_('List of all running jobs');?></h3>
			<table class="tableList">
				<thead>
					<tr>
						<th scope="col"><?=_('Name');?></th>
						<th scope="col"><?=_('Started at');?></th>
						<th scope="col"><?=_('Progress');?></th>
					</tr>
				</thead>
				<tbody>
<?php
	if (count($running_jobs) > 0):
		foreach ($running_jobs as $job):
?>
					<tr>
						<td><?=anchor('experiments/detail/' . $job['experiment_id'], $job['name']);?></td>
						<td><?=relative_time($job['started_at']);?></td>
						<td><?=$job['progress'];?>%</td>
					</tr>
<?php
		endforeach;
	else:
?>
					<tr>
						<td colspan="3"><?=_('No running jobs found.');?></td>
					</tr>
<?php
	endif;
?>
				</tbody>
			</table>
		</div>
		<div id="pending" class="tab_content">
			<h3><?=_('List of all pending jobs');?></h3>
			<table class="tableList">
				<thead>
					<tr>
						<th scope="col"><?=_('Name');?></th>
						<th scope="col"><?=_('Created at');?></th>
						<th scope="col"><?=_('Actions');?></th>
					</tr>
				</thead>
				<tbody>
<?php
	if (count($pending_jobs) > 0):
		foreach ($pending_jobs as $job):
?>
					<tr>
						<td><?=anchor('experiments/detail/' . $job['experiment_id'], $job['name']);?></td>
						<td><?=relative_time($job['created_at']);?></td>
						<td>
							<?=anchor('jobs/cancel/' . $job['id'], _('Cancel'));?>
						</td>
					</tr>
<?php
		endforeach;
	else:
?>
					<tr>
						<td colspan="3"><?=_('No pending jobs found.');?></td>
					</tr>
<?php
	endif;
?>
				</tbody>
			</table>
		</div>
	</div>

</div>

<?php $this->load->view('footer');?>
