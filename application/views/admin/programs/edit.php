<?php $this->load->view('header');?>

<script>
$(document).ready(function() {
	$('#parameters').tableDnD({
		onDrop: function(table, row) {
			$.post(SITE_URL + 'ajax/sort_parameters', $.tableDnD.serialize());
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
		<form name="editProgram" method="post" action="<?=site_url('admin/programs/edit/' . $program['id'])?>">
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
				<li>
					<?=form_label(_('Config file line'), 'input_line');?>
					<span class="req">*</span>
					<div>
						<textarea name="input_line" id="input_line" rows="6" cols="60" class="textarea"><?=set_value('input_line', $program['input_line']);?></textarea>
						<?=form_error('input_line')?>
					</div>
					<label class="note">
						<?=_('Here you can specify how a single line of a configuration file looks. You can use the following placeholders:');?><br />
						<strong>{type}</strong> <?=_('Parameter type');?><br />
						<strong>{param}</strong> <?=_('Parameter name');?><br />
						<strong>{value}</strong> <?=_('Value');?><br />
					</label>
				</li>
			</ul>
			<p>
				<a class="button save" href="javascript:void(0);" onclick="$('form[name=editProgram]').submit();"><?=_('Save');?></a>
				<a class="button cancel" href="<?=site_url('admin/programs');?>"><?=_('Cancel');?></a>
			</p>
		</form>

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
					<td><?=anchor('admin/parameters/edit/' . $parameter['id'], _('Edit'));?> | <a href="javascript:deleteConfirm('<?=site_url('admin/parameters/delete/' . $parameter['id']);?>');"><?=_('Delete');?></a></td>
				</tr>
<?php
	endforeach;
?>
			</tbody>
		</table>
		<p>
			<a class="button add" href="<?=site_url('admin/parameters/create/' . $program['id']);?>"><?=_('Add new parameter');?></a>
		</p>

		<form name="uploadCSV" method="post" action="<?=site_url('admin/parameters/upload_csv/' . $program['id'])?>" enctype="multipart/form-data">
		<ul>
			<li>
				<?=form_label(_('CVS file'), 'csv_file');?>
				<div>
					<input type="file" name="csv_file" id="csv_file" class="file" />
					<?=form_error('csv_file')?>
				</div>
				<label class="note">You can upload a CVS file, containing a bunch of parameters. The rows of the file must be in the following format: <em>parameter name, human-readable name, unit, type, default value, description</em>. The first row is reserved for headlines.</label>
			</li>
		</ul>
		<p>
			<a class="button upload" href="javascript:void(0);" onclick="$('form[name=uploadCSV]').submit();"><?=_('Upload');?></a>
		</p>
	</div>
</div>

<?php $this->load->view('footer');?>