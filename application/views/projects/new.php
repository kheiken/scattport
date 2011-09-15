<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Create a new project');?></h2>
	</div>

	<div class="box">
		<form method="post" name="createProject" action="<?=site_url('projects/create');?>" enctype="multipart/form-data">
			<h3><?=_('Required information');?></h3>
			<ul>
				<li>
					<?=form_label(_('Project name'), 'name');?> <span class="req">*</span>
					<div>
						<input type="text" name="name" id="name" class="short text" value="<?=set_value('name');?>" />
						<?=form_error('name');?>
					</div>
				</li>
				<li>
					<?=form_label(_('Description'), 'description');?> <span class="req">*</span>
					<div>
						<textarea name="description" id="description" rows="6" cols="60" class="textarea"><?=set_value('description');?></textarea>
						<?=form_error('description');?>
					</div>
					<label class="note"><?= _('A description is useful if you want to share this project with co-workers.') ?></label>
				</li>
			</ul>
			<h3><?=_('Optional information');?></h3>
			<ul>
				<li>
					<?=form_label(_('3D model'), 'defaultmodel');?>
					<div>
						<input type="file" name="defaultmodel" id="defaultmodel" class="file" />
						<?=form_error('defaultmodel');?>
					</div>
					<label class="note"><?=_('Upload a 3D model that is used as a default for new experiments.<br/>This model can be changed for every experiment.');?></label>
				</li>
				<li>
					<?=form_label(_('Default configuration'), 'defaultconfig');?>
					<div>
						<input type="file" name="defaultconfig" id="defaultconfig" class="file" />
						<?=form_error('defaultconfig');?>
					</div>
					<label class="note"><?=_('Upload a configuration that is used as a default for new experiments.<br/>This configuration can be changed for every experiment.');?></label>
				</li>
				<li>
					<?=form_label(_('Make the project public?'), 'public');?>
					<div>
						<?=form_yesno('public', set_value('public'), 'id="public" class="drop"');?>
					</div>
				</li>
				<li>
					<a href="javascript:void(0);" onclick="$('form[name=createProject]').submit();" class="button save"><?=_('Save');?></a>
				</li>
			</ul>
		</form>
	</div>

</div>

<?php $this->load->view('footer');?>
