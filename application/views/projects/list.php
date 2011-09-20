<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Projects');?></h2>
	</div>

	<div class="box">
		<h3><?=_('List of all your accessible projects');?></h3>
		<table class="tableList paginated sortable">
			<thead>
				<tr>
					<th scope="col"><?=_('Project');?></th>
					<th scope="col"><?=_('Owner');?></th>
					<th scope="col"><?=_('Jobs');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach($projects as $project):
?>
				<tr>
					<td><a href="<?=site_url('projects/detail/' . $project['id'] . '?active_project=' . $project['id']);?>"><abbr title="<?=$project['name'] . "\n\n" . $project['description'];?>"><?=$project['mediumname'];?></abbr></a></td>
					<td><a href="<?=site_url('users/profile/' . $project['username']);?>" title="<?=_('Show profile');?>"><?=$project['firstname'] . ' ' . $project['lastname'];?></a></td>
					<td><span class="active"><?=_('Successfully finished');?></span></td>
					<td><a href="#"><?=_('Show results');?></a> | <?=anchor('projects/delete/' . $project['id'], _('Delete'));?>
					</td>
				</tr>
<?php
	endforeach;
?>
			</tbody>
		</table>
	</div>

</div>

<?php $this->load->view('footer');?>
