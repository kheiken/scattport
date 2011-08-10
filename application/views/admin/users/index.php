<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=lang('users');?></h2>
	</div>

	<div class="box">
		<h3><?=lang('available_users');?></h3>
		<table class="tableList paginated">
			<thead>
				<tr>
					<th scope="col"><?=lang('username');?></th>
					<th scope="col"><?=lang('realname');?></th>
					<th scope="col"><?=lang('options');?></th>
				</tr>
			</thead>
<?php
foreach ($users as $user):
?>
				<tr>
					<td><?=$user['username'];?></td>
					<td><?=$user['firstname'];?> <?=$user['lastname'];?></td>
					<td><?=anchor('users/edit/' . $user['id'], lang('user_edit'));?> | <a href="javascript:deleteConfirm('<?=site_url('users/delete/' . $user['id']);?>');" title="User"><?=lang('user_delete');?></a></td>
				</tr>
<?php
endforeach;
?>
			<tbody>
			</tbody>
		</table>

		<p><a class="button add" href="<?=site_url('users/create')?>"><?=lang('user_create');?></a>
	</div>
</div>

<?php $this->load->view('footer');?>