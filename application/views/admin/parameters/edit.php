<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?php printf(_("Edit parameter '%s'"), $parameter['name']);?></h2>
	</div>

	<div class="box">
		<form name="editParameter" method="post" action="<?=site_url('admin/parameters/edit/' . $parameter['id']);?>">
			<ul>
				<li>
					<?=form_label(_('Name'), 'name');?>
					<span class="req">*</span>
					<div>
						<input type="text" name="name" id="name" class="short text" value="<?=set_value('name', $parameter['name']);?>" />
						<?=form_error('name');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Human-readable name'), 'readable');?>
					<span class="req">*</span>
					<div>
						<input type="text" name="readable" id="readable" class="medium text" value="<?=set_value('readable', $parameter['readable']);?>" />
						<?=form_error('readable');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Unit'), 'unit');?>
					<div>
						<input type="text" name="unit" id="unit" class="short text" value="<?=set_value('unit', $parameter['unit']);?>" />
						<?=form_error('unit');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Description'), 'description');?>
					<div>
						<textarea name="description" id="description" rows="6" cols="60" class="textarea"><?=set_value('description', $parameter['description']);?></textarea>
						<?=form_error('description');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Type'), 'type');?>
					<span class="req">*</span>
					<div>
						<select name="type" id="type" class="drop">
<?php
	foreach ($types as $type):
?>
							<option value="<?=$type;?>" <?=set_select('type', $type, $parameter['type'] == $type);?>><?=$type;?></option>
<?php
	endforeach;
?>
						</select>
						<?=form_error('type');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Default value'), 'default_value');?>
					<div>
						<input type="text" name="default_value" id="default_value" class="short text" value="<?=set_value('default_value', $parameter['default_value']);?>" />
						<?=form_error('default_value');?>
					</div>
				</li>
			</ul>
			<p>
				<a class="button save" href="javascript:void(0);" onclick="$('form[name=editParameter]').submit();"><?=_('Save');?></a>
				<a class="button cancel" href="<?=site_url('admin/programs/edit/' . $parameter['program_id']);?>"><?=_('Cancel');?></a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>