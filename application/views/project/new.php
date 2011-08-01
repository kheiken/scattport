<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2>Neues Projekt erstellen</h2>
	</div>

	<div class="box">

		
		<form method="post" name="createproject" action="<?=site_url('projects/create')?>">
		<h3>Erforderliche Angaben zum Projekt</h3>
			<ul>
				<li>
					<h4>Projektname <span class="req">*</span></h4>
					<div>
						<input type="text" name="name" class="short text" tabindex="1" value="<?=set_value('name')?>">
						<?=form_error('name')?>
					</div>
				</li>
				<li>
					<h4>Beschreibung</h4>
					<label class="note">Eine Beschreibung ist hilfreich, wenn Sie dieses Projekt später für andere Mitarbeiter freigeben möchten.</label>
					<div>
						<textarea name="description" rows="6" cols="60" tabindex="2" class="textarea"><?=set_value('description')?></textarea>
						<?=form_error('description')?>
					</div>
				</li>
			</ul>
			<h3>Optionale Angaben</h3>
			<ul>
				<li>
					<h4>3D-Modell</h4>
					<label class="note">Falls ein 3D-Modell für jeden Versuch als Standard definiert werden soll, so können Sie dieses hier hochladen.<br />
						Es kann weiterhin bei jedem Versuch ein anderes Modell gewählt werden.</label>
					<div>
						<input type="file" class="file" name="defaultmodel" tabindex="3" value="<?=set_value('defaultmodel')?>">
						<?=form_error('defaultmodel')?>
					</div>
				</li>
				<li>
					<h4>Standard-Konfiguration <span class="req">*</span></h4>
					<label class="note">Laden Sie eine Konfiguration hoch, die als Vorgabe für alle Versuche verwendet wird.<br />
						Diese Konfiguration kann bei jedem Versuch geändert werden.</label>
					<div>
						<input type="file" class="file" name="defaultconfig" tabindex="4" value="<?=set_value('defaultconfig')?>">
						<?=form_error('defaultconfig')?>
					</div>
				</li>
				<li>
					<a href="#" onclick="document.forms.createproject.submit()" class="button">Speichern</a>
				</li>
			</ul>
		</form>
	</div>

</div>

<?php $this->load->view('footer'); ?>
