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
		<h2><?=anchor('admin/programs', _('Programs'));?> &raquo; <?=sprintf(_("Edit program &quot;%s&quot;"), $program['name']);?></h2>
	</div>

	<div class="box">
		<form name="editProgram" method="post" action="<?=site_url('admin/programs/edit/' . $program['id'])?>">
			<h3><?=_('Required information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Name of the program'), 'name');?> <span class="req">*</span>
					<div>
						<input type="text" name="name" id="name" class="medium text" value="<?=set_value('name', $program['name']);?>" />
						<?=form_error('name')?>
					</div>
				</li>
				<li>
					<?=form_label(_('Config template'), 'config_template');?> <span class="req">*</span>
					<div>
						<textarea name="config_template" id="config_template" rows="6" cols="60" class="textarea"><?=set_value('config_template', $program['config_template']);?></textarea>
						<?=form_error('config_template')?>
					</div>
					<label class="note">
						<?=_('Here you can specify how the configuration file looks. You can use the following placeholders:');?><br />
						<strong>{parameters}{/parameters}</strong> <?=_('Parameter loop');?><br />
						<strong>{name}</strong> <?=_('Parameter name');?><br />
						<strong>{value}</strong> <?=_('Value');?><br />
					</label>
				</li>
			</ul>
			<p>
				<a href="javascript:void(0);" onclick="$('form[name=editProgram]').submit();" class="button save"><?=_('Save');?></a>
				<a href="<?=site_url('admin/programs');?>" class="button cancel"><?=_('Cancel');?></a>
			</p>
		</form>
	</div>

	<div class="box">
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
			<label class="note"><?=_('Entries of this table are draggable.');?></label>
		</p>

		<h3><?=_('Actions');?></h3>
		<p>
			<a class="button parameter_add" href="<?=site_url('admin/parameters/create/' . $program['id']);?>"><?=_('Add parameter');?></a>
		</p>

		<form name="uploadCSV" method="post" action="<?=site_url('admin/parameters/upload_csv/' . $program['id'])?>" enctype="multipart/form-data">
			<ul>
				<li>
					<?=form_label(_('CSV file'), 'csv_file');?>
					<div>
						<input type="file" name="csv_file" id="csv_file" class="file" />
						<?=form_error('csv_file');?>
					</div>
					<label class="note"><?=_('You can upload a CSV file, containing a bunch of parameters.');?> <?=anchor('uploads/csv_template.csv', _('Here you can download a template.'));?></label>
				</li>
			</ul>
		</form>
		<p>
			<a href="javascript:void(0);" onclick="$('form[name=uploadCSV]').submit();" class="button upload"><?=_('Upload');?></a>
		</p>
	</div>
</div>

<?php $this->load->view('footer');?>