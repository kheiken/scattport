<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2><?= _('Create a new trial') ?></h2>
	</div>

	<form name="newtrial" method="post" action="<?=site_url('trials/create')?>">
		<div class="box">


			<h3><?= _('Required information') ?></h3>
				<ul>
					<li>
						<h4><?= _('Trial name') ?> <span class="req">*</span></h4>
						<div>
							<input type="text" name="name" class="short text" value="<?=set_value('name')?>">
							<?=form_error('name')?>
						</div>
					</li>
					<li>
						<h4><?= _('Description') ?></h4>
						<label class="note"><?= _('A description is useful if you want to share this trial with co-workers.') ?></label>
						<div>
							<textarea name="description" rows="6" cols="60" class="textarea"><?=set_value('description')?></textarea>
							<?=form_error('description')?>
						</div>
					</li>
					<li>
						<h4><?= _('3D model') ?></h4>
<?
	$defaultmodel = "foo";
	if(isset($defaultmodel)):
?>
						<div class="notice">
							<strong><?= _('There is a default model set for this project.') ?></strong><br />
								<?= _('If you want to use a different model for this trial, you can upload it here.') ?>
						</div>
<?
	endif;
?>
						<div>
							<input type="file" class="file" name="defaultmodel" value="<?=set_value('defaultmodel')?>">
							<?=form_error('defaultmodel')?>
						</div>
					</li>
				</ul>
		</div>

		<div class="box">

			<h3><?= _('Application-specific parameters') ?></h3>
<?
	$defaultconfig = "foo";
	if(isset($defaultconfig)):
?>
			<div class="notice">
				<strong><?= _('There is a default configuration set for this project.') ?></strong><br />
				<?= _('This form contains the default values. You can adjust them for this trial.') ?><br />
				<?= _('The default configuration will not be modified.') ?>
			</div>
<?
	endif;
?>
			<h4><?= _('Application to use for the computation') ?></h4>
			<p>
<?
	foreach($programs as $program):
?><a class="button" onclick="$('.program-parameters').hide();$('#<?=$program['id']?>-params').show();$('.button').removeClass('locked');$(this).addClass('locked');return false;" href="#"><?=$program['name']?></a>
<?
	endforeach;
?>
			</p>
<?
	foreach($programs as $program):
?>

			<div class="program-parameters" id="<?=$program['id']?>-params" style="display:none">
				<h4><?= sprintf(_('Parameters for %s'), $program['name'])?></h4>
				<p>
					<table>
						<thead>
							<tr>
								<th scope="col" width="40%"><?= _('Parameter') ?></th>
								<th scope="col" width="40%"><?= _('Value') ?></th>
								<th scope="col"><?= _('Unit') ?></th>
							</tr>
						</thead>
						<tbody>
<?
	foreach($parameters[$program['id']] as $param):
?>
							<tr>
								<td><abbr title="<?=$param['description']?>"><?=$param['readable']?></abbr></td>
								<td><input type="text" name="<?=$param['fieldname']?>" class="long text" value="<?=set_value($param['fieldname'])?>"><?=form_error($param['fieldname'])?></td>
								<td><?=$param['unit']?></td>
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
				<a class="button save-big big" href="#" onclick="document.forms.newtrial.submit();return false"><?= _('Save') ?></a>
			</p>
		</div>
	</form>

</div>

<?php $this->load->view('footer'); ?>
