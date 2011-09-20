<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Projects shared with me');?></h2>
	</div>

	<div class="box">
		<h3><?=_('List of projects');?></h3>
		<table class="tableList paginated">
			<thead>
				<tr>
					<th scope="col"><?=_('Project');?></th>
					<th scope="col"><?=_('Owner');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach ($projects as $share):
		$project = $this->project->getById($share['project_id']);
		$user = $this->user->getUserById($project['owner']);
?>
				<tr>
					<td>
						<a href="<?=site_url('projects/detail/' . $project['id'] . '?active_project=' . $project['id']);?>"><abbr title="<?=$project['name'] . "\n\n" . $project['description'];?>"><?=$project['mediumname'];?></abbr></a>
						<?=($share['seen'] == 0) ? image_asset('icons/new-text.png', 'class="icon"') : '';?>
					</td>
					<td><a href="<?=site_url('users/profile/' . urlencode($user['username']));?>" title="<?=_('Show profile');?>"><?=$user['firstname'] . ' ' . $user['lastname'];?></a></td>
				</tr>
<?php
	endforeach;
?>
			</tbody>
		</table>
		<h3><?=_('Actions');?></h3>
		<p>
			<a href="<?=site_url('shares/mark_all_seen');?>" class="button"><?=_('Mark all as seen');?></a>
		</p>
	</div>

</div>

<?php $this->load->view('footer');?>
