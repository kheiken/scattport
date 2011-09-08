<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Project');?> &raquo;<?=$project['name'];?>&laquo;</h2>
	</div>

	<div class="box">
		<table class="tableList">
			<thead>
				<tr>
					<th scope="col"><?=_('User');?></th>
					<th scope="col"><?=_('Rights');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach ($shares as $share):
?>
				<tr>
					<td><a href="<?=site_url('users/profile/' . $share['user_id']);?>"><?=$share['firstname'];?> <?=$share['lastname'];?></a></td>
					<td><?=form_dropdown('rights', array('Can edit', 'Can view'), $share['can_edit'], 'class="drop"')?></td>
					<td><a href="?action=delete&user_id=<?=$share['user_id'];?>"><?=_('Delete');?></a></td>
				</tr>
<?php
	endforeach;
?>
			</tbody>
		</table>

		<form method="post" name="addShare" action="<?=site_url('projects/share/' . $project['id']);?>">
			<ul>
				<li>
					<div>
						<?=form_label(_('Add person:'), 'user_id');?>
						<select name="user_id" id="user_id" class="drop">
<?php
	foreach ($this->user->getAll() as $user):
?>
							<option value="<?=$user['id'];?>"><?=$user['firstname'];?> <?=$user['lastname'];?></option>
<?php
	endforeach;
?>
						</select>
						<?=form_submit('', _('Share'), 'class="submit"');?>
					</div>
				</li>
			</ul>
		</form>

		<p><a class="button save" href="javascript:history.back();"><?=_('Save and back');?></a></p>
	</div>

</div>

<?php $this->load->view('footer');?>
