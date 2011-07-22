<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2>Neues Projekt erstellen</h2>
	</div>

	<div class="box">
		<h3>Angaben zum Projekt</h3>
		<form method="post" action="#">

		<ul>
			<li>
				<label>Projektname <span class="req">*</span></label>
				<div><input type="text" name="title" class="short text" value=""> <span class="error"><strong>ERROR:</strong> Error message.</span></div>
			</li>
			<li>
				<label>Beschreibung</label>
				<div><textarea name="description" rows="6" cols="60" tabindex="1" class="textarea"></textarea></div>
			</li>
			<li>
				<label>3D-Modell <span class="req">*</span></label>
				<div><input type="file" class="file" name="model"></div>
			</li>
			<li>
				<label>Konfiguration <span class="req">*</span></label>
				<div><input type="file" class="file" name="model"></div>
			</li>
			<li>
				<input type="button" value="Submit" class="button"> <input type="button" value="Reset" class="button">
			</li>
		</ul>
		</form>
	</div>

</div>

<?php $this->load->view('footer'); ?>
