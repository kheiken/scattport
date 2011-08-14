<?php $this->load->view('header');?>

<script>
$(document).ready(function() {
	$('#parameters').tableDnD({
		onDrop: function(table, row) {
			/*var rows = table.tBodies[0].rows;
			for (var i = 0; i < rows.length; i++) {
				$(rows[i]).children().find('input[type=hidden]').val(i + 1);
			}*/
			$.post(SITE_URL + 'ajax/sort_parameters/<?=$program['id'];?>', $.tableDnD.serialize());
		},
		dragHandle: 'drag_handle'
	});
});
</script>

<div id="content">

	<div id="debug"></div>

	<div class="title">
		<h2><?php printf(_("Edit program '%s'"), $program['name']);?></h2>
	</div>

	<div class="box">
		<form name="createUser" method="post" action="<?=site_url('admin/programs/edit/' . $program['id'])?>">
			<h3><?=_('Required information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Name of the program'), 'name');?>
					<span class="req">*</span>
					<div>
						<input type="text" name="name" id="name" class="medium text" value="<?=set_value('name', $program['name']);?>" />
						<?=form_error('name')?>
					</div>
				</li>
			</ul>
			<p>
				<a class="button save" href="javascript:void(0);" onclick="$('form[name=createUser]').submit();"><?=_('Save');?></a>
				<a class="button cancel" href="<?=site_url('admin/programs');?>"><?=_('Cancel');?></a>
			</p>

			<h3><?=_('Parameters');?></h3>
			<table class="tableList sortable" id="parameters">
				<thead>
					<tr>
						<th scope="col">&nbsp;</th>
						<th scope="col"><?=_('Readable name');?></th>
						<th scope="col"><?=_('Unit');?></th>
						<th scope="col"><?=_('Type');?></th>
						<th scope="col"><?=_('Actions');?></th>
					</tr>
				</thead>
				<tbody>
<?php
	foreach ($parameters as $parameter):
?>
					<tr id="<?=$parameter['id'];?>">
						<td class="drag_handle"></td>
						<td><?=$parameter['readable'];?></td>
						<td><?=$parameter['unit'];?></td>
						<td><?=$parameter['type'];?></td>
						<td><?=anchor('admin/programs/edit_parameter/' . $parameter['id'], _('Edit'));?> | <a href="javascript:deleteConfirm('<?=site_url('admin/programs/delete_parameter/' . $parameter['id']);?>');"><?=_('Delete');?></a></td>
					</tr>
<?php
	endforeach;
?>
				</tbody>
			</table>
			<p>
				<a class="button add" href="<?=site_url('admin/programs/add_parameter');?>"><?=_('Add new parameter');?></a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>