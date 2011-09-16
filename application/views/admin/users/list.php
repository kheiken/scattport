<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Users');?></h2>
	</div>

	<div class="box">
		<h3><?=_('Available users');?></h3>
		<table class="tableList paginated">
			<thead>
				<tr>
					<th scope="col"><?=_('Username');?></th>
					<th scope="col"><?=_('Full name');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach ($users as $user):
?>
				<tr>
					<td><?=$user['username'];?></td>
					<td><?=$user['firstname'] . ' ' . $user['lastname'];?></td>
					<td>
						<?=anchor('admin/users/edit/' . $user['id'], _('Edit'));?> |
						<a href="javascript:deleteConfirm('<?=site_url('admin/users/delete/' . $user['id']);?>');"><?=_('Delete');?></a>
					</td>
				</tr>
<?php
	endforeach;
?>
			</tbody>
		</table>
		<h3><?=_('Actions');?></h3>
		<p>
			<a href="<?=site_url('admin/users/create')?>" class="button user_add"><?=_('Create user');?></a>
			<a class="button disabled search"><?=_('Search user');?></a>
		</p>
	</div>
</div>

<?php $this->load->view('footer');?>
