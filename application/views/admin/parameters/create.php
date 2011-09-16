<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=anchor('admin/programs', _('Programs'));?> &raquo; <?=anchor('admin/programs/edit/' . $program['id'], $program['name']);?> &raquo; <?=_('Add a new parameter');?></h2>
	</div>

	<div class="box">
		<form name="addParameter" method="post" action="<?=site_url('admin/parameters/create/' . $program['id']);?>">
			<h3><?=_('Required information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Name'), 'name');?> <span class="req">*</span>
					<div>
						<input type="text" name="name" id="name" class="short text" value="<?=set_value('name');?>" />
						<?=form_error('name');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Human-readable name'), 'readable');?> <span class="req">*</span>
					<div>
						<input type="text" name="readable" id="readable" class="medium text" value="<?=set_value('readable');?>" />
						<?=form_error('readable');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Type'), 'type');?> <span class="req">*</span>
					<div>
						<select name="type" id="type" class="drop">
<?php
	foreach ($types as $type):
?>
							<option value="<?=$type;?>" <?=set_select('type', $type);?>><?=$type;?></option>
<?php
	endforeach;
?>
						</select>
						<?=form_error('type');?>
					</div>
				</li>
			</ul>
			<h3><?=_('Optional information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Unit'), 'unit');?>
					<div>
						<input type="text" name="unit" id="unit" class="short text" value="<?=set_value('unit');?>" />
						<?=form_error('unit');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Description'), 'description');?>
					<div>
						<textarea name="description" id="description" rows="6" cols="60" class="textarea"><?=set_value('description');?></textarea>
						<?=form_error('description');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Default value'), 'default_value');?>
					<div>
						<input type="text" name="default_value" id="default_value" class="short text" value="<?=set_value('default_value');?>" />
						<?=form_error('default_value');?>
					</div>
				</li>
			</ul>
			<p>
				<a href="javascript:void(0);" onclick="$('form[name=addParameter]').submit();" class="button save"><?=_('Save');?></a>
				<a href="<?=site_url('admin/programs/edit/' . $program['id']);?>" class="button cancel"><?=_('Cancel');?></a>
			</p>
		</form>
	</div>
</div>

<?php $this->load->view('footer');?>