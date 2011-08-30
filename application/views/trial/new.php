<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Create a new trial');?></h2>
	</div>

	<form name="newTrial" method="post" action="<?=site_url('trials/create/' . $project['id']);?>" enctype="multipart/form-data">
		<div class="box">

			<h3><?=_('Required information');?></h3>
				<ul>
					<li>
						<?=form_label(_('Trial name'), 'name');?>
						<span class="req">*</span>
						<div>
							<input type="text" name="name" id="name" class="short text" value="<?=set_value('name');?>" />
							<?=form_error('name');?>
						</div>
					</li>
					<li>
						<?=form_label(_('Description'), 'description');?>
						<span class="req">*</span>
						<div>
							<textarea name="description" id="description" rows="6" cols="60" class="textarea"><?=set_value('description');?></textarea>
							<?=form_error('description');?>
						</div>
						<label class="note"><?=_('A description is useful if you want to share this trial with co-workers.');?></label>
					</li>
					<li>
						<h4><?=_('3D model');?></h4>
<?
	if (file_exists(FCPATH.'uploads/'.$project['id'].'/default.txt')):
?>
						<div class="notice">
							<strong><?=_('There is a default model set for this project.');?></strong><br />
							<?=_('If you want to use a different model for this trial, you can upload it here.');?>
						</div>
<?
	endif;
?>
						<div>
							<input type="file" class="file" name="3dmodel" value="<?=set_value('3dmodel');?>" />
							<?=form_error('3dmodel');?>
							<?=$this->upload->display_errors('<span class="error">', '</span>');?>
						</div>
					</li>
				</ul>
		</div>

		<div class="box">

			<h3><?=_('Application-specific parameters');?></h3>
<?
	if (isset($defaultconfig)):
?>
			<div class="notice">
				<strong><?=_('There is a default configuration set for this project.');?></strong><br />
				<?=_('This form contains the default values. You can adjust them for this trial.');?><br />
				<?=_('The default configuration will not be modified.');?>
			</div>
<?
	endif;
?>
			<h4><?=_('Application to use for the computation');?></h4>
			<input type="hidden" name="program_id" id="program_id" value="<?=set_value('program_id');?>" />
			<?=form_error('program_id');?>
			<p>
<?
	foreach ($programs as $program):
?>
				<a class="button" href="javascript:void(0);" onclick="$('.program-parameters').hide();$('#<?=$program['id'];?>-params').show();$('.button').removeClass('locked');$(this).addClass('locked');$('input[name=program_id]').val('<?=$program['id'];?>');return false;"><?=$program['name'];?></a>
<?
	endforeach;
?>
			</p>
<?
	foreach ($programs as $program):
?>

			<div class="program-parameters" id="<?=$program['id'];?>-params" style="display:none">
				<h4><?=sprintf(_('Parameters for %s'), $program['name']);?></h4>
				<p>
					<table>
						<thead>
							<tr>
								<th scope="col" width="40%"><?=_('Parameter');?></th>
								<th scope="col" width="40%"><?=_('Value');?></th>
								<th scope="col"><?=_('Unit');?></th>
							</tr>
						</thead>
						<tbody>
<?
	foreach ($parameters[$program['id']] as $param):
?>
							<tr>
								<td><?=$param['readable'];?></td>
								<td>
									<input type="text" name="param-<?=$param['id'];?>" class="long text" value="<?=(!empty($_POST['param-' . $param['id']]) ? $this->input->post('param-' . $param['id']) : $param['default_value']);?>" />
<?php
	if (!empty($param['description'])):
?>
									<span class="form_info">
										<a href="<?=site_url('ajax/parameter_help/' . $param['id']);?>" name="<?=_('Description');?>" id="<?=$param['id'];?>" class="jtip">&nbsp;</a>
									</span>
<?php
	endif;
?>
									<?=form_error('params');?>
								</td>
								<td><?=$param['unit'];?></td>
							</tr>
<?
	endforeach;
?>
						</tbody>
					</table>
				</p>
			</div>
<?
	endforeach;
?>
			<p>
				<a class="button save-big big" href="javascript:void(0);" onclick="$('form[name=newTrial]').submit();"><?=_('Save');?></a>
			</p>
		</div>
	</form>

</div>

<?php $this->load->view('footer');?>
