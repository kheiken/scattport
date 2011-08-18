<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2><?= _('Create a new project') ?></h2>
	</div>

	<div class="box">


		<form method="post" name="createproject" action="<?=site_url('projects/create')?>" enctype="multipart/form-data">
			<h3><?= _('Required information') ?></h3>
			<ul>
				<li>
					<h4><?= _('Project name') ?> <span class="req">*</span></h4>
					<div>
						<input type="text" name="name" class="short text" tabindex="1" value="<?=set_value('name') == '' ? $this->input->post('name') : set_value('name');?>">
						<?=form_error('name')?>
					</div>
				</li>
				<li>
					<h4><?= _('Description') ?></h4>
					<label class="note"><?= _('A description is useful if you want to share this project with co-workers.') ?></label>
					<div>
						<textarea name="description" rows="6" cols="60" tabindex="2" class="textarea"><?=set_value('description') == '' ? $this->input->post('description') : set_value('description');?></textarea>
						<?=form_error('description')?>
					</div>
				</li>
			</ul>
			<h3><?= _('Optional information') ?></h3>
			<ul>
				<li>
					<h4><?= _('3D model') ?></h4>
					<label class="note"><?= _('Upload a 3D model that is used as a default for new trials. <br/>This model can be changed for every trial.')?></label>
					<div>
						<input type="file" class="file" name="defaultmodel" tabindex="3" value="<?=set_value('defaultmodel')?>">
						<?=$model['success'] ? '' : $this->upload->display_errors('<span class="error">', '</span>');?>
					</div>
				</li>
				<li>
					<h4><?= _('Default configuration') ?> <span class="req">*</span></h4>
					<label class="note"><?= _('Upload a configuration that is used as a default for new trials. <br/>This configuration can be changed for every trial.')?></label>
					<div>
						<input type="file" class="file" name="defaultconfig" tabindex="4" value="<?=set_value('defaultconfig')?>">
						<?=$config['success'] ? '' : $this->upload->display_errors('<span class="error">', '</span>');?>
					</div>
				</li>
				<li>
					<a href="#" onclick="document.forms.createproject.submit()" class="button"><?= _('Save') ?></a>
				</li>
			</ul>
		</form>
	</div>

</div>

<?php $this->load->view('footer'); ?>
