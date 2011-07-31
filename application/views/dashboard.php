<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2>Dashboard</h2>
	</div>

	<div class="box">
		<h3>Projekte</h3>
		<p>
			<a class="button left big" href="projects/create">Projekt anlegen</a><a class="button middle big" href="projects">Projekte verwalten</a><a class="button right big" href="#">Projekt suchen</a>
		</p>
	</div>
	<div class="box">
		<h3>Berechnungen</h3>
		<p>
			<a class="button left big" href="#">Neueste Ergebnisse</a><a class="button middle big" href="#">Laufende Berechnungen</a>
		</p>
	</div>
	<div class="box">
		<h3>Administration</h3>
		<p>
			<a class="button left big" href="#">Server verwalten</a><a class="button middle big" href="#">Programme verwalten</a><a class="button right big" href="#">Benutzer verwalten</a>
		</p>
	</div>

</div>

<?php $this->load->view('footer'); ?>
