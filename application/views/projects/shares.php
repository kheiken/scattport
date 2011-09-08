<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=anchor('projects', _('Projects'));?> &raquo; <?=anchor('projects/detail/' . $project['id'], $project['name']);?> &raquo; <?=_('Shares');?></h2>
	</div>

	<div class="box">
		<form method="post" name="updateShares" action="?action=update">
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
	if (count($shares) > 0):
		foreach ($shares as $share):
?>
					<tr>
						<td><a href="<?=site_url('users/profile/' . $share['username']);?>"><?=$share['firstname'];?> <?=$share['lastname'];?></a></td>
						<td><?=form_dropdown('rights[' . $share['user_id'] . ']', array('Can view', 'Can edit'), $share['can_edit'], 'class="drop"')?></td>
						<td><a href="?action=delete&user_id=<?=$share['user_id'];?>"><?=_('Delete');?></a></td>
					</tr>
<?php
		endforeach;
	else:
?>
					<tr>
						<td colspan="3"><?=_('This project is not yet shared with anyone.');?></td>
					</tr>
<?php
	endif;
?>
				</tbody>
			</table>
		</form>

		<p><a class="button save" href="javascript:void(0);" onclick="$('form[name=updateShares]').submit();"><?=_('Save and back');?></a></p>

		<h3><?=_('Share with someone');?></h3>
		<form method="post" name="addShare" action="?action=add">
			<ul>
				<li>
					<div>
						<select name="user_id" id="user_id" class="drop">
<?php
	foreach ($this->user->getAll() as $user):
?>
							<option value="<?=$user['id'];?>"><?=$user['firstname'];?> <?=$user['lastname'];?></option>
<?php
	endforeach;
?>
						</select>
						<?=form_dropdown('rights', array('Can view', 'Can edit'), 0, 'class="drop"')?>
						<?=form_submit('add', _('Share'), 'class="submit"');?>
					</div>
				</li>
			</ul>
		</form>
	</div>

</div>

<?php $this->load->view('footer');?>
