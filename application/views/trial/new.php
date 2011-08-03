<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2>Neuen Versuch erstellen</h2>
	</div>

	<form name="newtrial" method="post" action="<?=site_url('trials/create')?>">
		<div class="box">


			<h3>Erforderliche Angaben zum Versuch</h3>
				<ul>
					<li>
						<h4>Versuchsbezeichnung <span class="req">*</span></h4>
						<div>
							<input type="text" name="name" class="short text" value="<?=set_value('name')?>">
							<?=form_error('name')?>
						</div>
					</li>
					<li>
						<h4>Beschreibung</h4>
						<label class="note">Eine Beschreibung ist hilfreich, wenn andere Mitarbeiter an diesem Projekt mitarbeiten möchten.</label>
						<div>
							<textarea name="description" rows="6" cols="60" class="textarea"><?=set_value('description')?></textarea>
							<?=form_error('description')?>
						</div>
					</li>
					<li>
						<h4>3D-Modell</h4>
<?
	$defaultmodel = "foo";
	if(isset($defaultmodel)):
?>
						<div class="notice">
							<strong>Für dieses Projekt ist ein Standardmodell vorhanden.</strong><br />
							Wenn Sie hier eine neue Datei hochladen, wird für diesen Versuch das hier angegebene Modell verwendet.
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

			<h3>Programmspezifische Parameter für die Berechnung</h3>
<?
	$defaultconfig = "foo";
	if(isset($defaultconfig)):
?>
			<div class="notice">
				<strong>Für dieses Projekt ist eine Standardkonfiguration vorhanden.</strong><br />
				Das folgende Formular enthält die Standardparameter. Diese können für diesen Versuch nach Belieben angepasst werden. <br />
				Die Standardkonfiguration wird dabei nicht verändert.
			</div>
<?
	endif;
?>
			<h4>Programm zur Berechnung</h4>
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
				<h4>Parameter für <?=$program['name']?></h4>
				<p>
					<table>
						<thead>
							<tr>
								<th scope="col" width="40%">Parameter</th>
								<th scope="col" width="40%">Wert</th>
								<th scope="col">Einheit</th>
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
				<a class="button save-big big" href="#" onclick="document.forms.newtrial.submit();return false">Speichern</a>
			</p>
		</div>
	</form>

</div>

<?php $this->load->view('footer'); ?>
