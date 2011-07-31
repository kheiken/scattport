<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2>Neuen Versuch erstellen</h2>
	</div>

	<form method="post" action="<?=site_url('trials/create')?>">
		<div class="box">


			<h3>Erforderliche Angaben zum Versuch</h3>
			<?php echo validation_errors(); ?>
				<ul>
					<li>
						<label>Versuchname <span class="req">*</span></label>
						<div>
							<input type="text" name="name" class="short text" value="<?=set_value('name')?>">
							<?=form_error('name')?>
						</div>
					</li>
					<li>
						<label>Beschreibung</label><br />
						<label class="note">Eine Beschreibung ist hilfreich, wenn andere Mitarbeiter an diesem Projekt mitarbeiten möchten.</label>
						<div>
							<textarea name="description" rows="6" cols="60" class="textarea"><?=set_value('description')?></textarea>
							<?=form_error('description')?>
						</div>
					</li>
					<li>
						<label>3D-Modell</label><br />
<?
	if(isset($defaultmodel)):
?>
						<label class="note"><strong>Für dieses Projekt ist ein Standardmodell vorhanden.</strong><br />
							Wenn Sie hier eine neue Datei hochladen, wird für diesen Versuch das hier angegebene Modell verwendet.</label>
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

			<h3>Parameter für die Berechnung</h3>
<?
if(isset($defaultmodel)):
?>
			<p>
				Für dieses Projekt ist eine Standardkonfiguration vorhanden.<br />
				Das folgende Formular enthält die Standardparameter. Diese können für diesen Versuch nach belieben angepasst werden. <br />
				Die Standardkonfiguration wird dabei nicht verändert.
			</p>
<?
endif;
?>
			<table>
				<thead>
					<tr>
						<th scope="col" width="40%">Parameter</th>
						<th scope="col" width="40%">Wert</th>
						<th scope="col">Einheit</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Wellenlänge</td>
						<td><input type="text" name="lambda" class="short text" value="<?=set_value('lambda')?>"><?=form_error('lambda')?></td>
						<td>nm</td>
					</tr>
					<tr>
						<td>Lichtrichtung</td>
						<td><input type="text" name="angle" class="short text" value="<?=set_value('angle')?>"><?=form_error('angle')?></td>
						<td>&deg;</td>
					</tr>
					<tr>
						<td>Parameter #3</td>
						<td><input type="text" name="foobar1" class="short text" value="<?=set_value('foobar1')?>"><?=form_error('foobar1')?></td>
						<td>foo</td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>

</div>

<?php $this->load->view('footer'); ?>
