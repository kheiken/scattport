<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2>Serververwaltung: <?=$server->id?></h2>
	</div>

	<div class="box">
		<h3>Miscellaneous</h3>
		<h4>Location</h4>
		<p><?=nl2br($server->location)?></p>

		<h4>Owner</h4>
		<p><a href="#" title="Profil von Jörg Thomaschewski anzeigen">Jörg Thomaschewski</a></p>

		<h3>Technical information</h3>
		<h4>Hardware &amp; OS</h4>
		<p>
			CPU: Intel Xeon E5540, 2533 MHz<br />
			Uptime: 254 Tage, 13 Stunden<br />
			OS: Debian/GNU 5.0r1<br />
			Workload: 2.01, 1.05, 0.85
		</p>

		<h4>ScattPort-Statistics</h4>
		<p>
			Completed jobs: 47<br />
			Available programs: PYTHA, ABC, DEF
		</p>
	</div>

</div>

<?php $this->load->view('footer'); ?>
