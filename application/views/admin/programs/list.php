<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Programs');?></h2>
	</div>

	<div class="box">
		<h3><?=_('Available programs');?></h3>
		<table class="tableList paginated">
			<thead>
				<tr>
					<th scope="col"><?=_('Name');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach ($programs as $program):
?>
				<tr>
					<td><?=$program['name'];?></td>
					<td><?=anchor('admin/programs/edit/' . $program['id'], _('Edit'));?> | <a href="javascript:deleteConfirm('<?=site_url('admin/programs/delete/' . $program['id']);?>');"><?=_('Delete');?></a></td>
				</tr>
<?php
	endforeach;
?>
			</tbody>
		</table>

		<p><a class="button add" href="<?=site_url('admin/users/create')?>"><?=_('Create new user')?></a>
	</div>
</div>

<?php $this->load->view('footer');?>
