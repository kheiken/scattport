<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2>Projektübersicht</h2>
	</div>

	<div class="box">
		<h3>Übersicht aller Projekte</h3>
		<table>
		<thead>
		<tr>
			<th scope="col">Projekt</th>
			<th scope="col">Besitzer</th>
			<th scope="col">Berechnungen</th>
			<th scope="col">Aktion</th>
		</tr>
		</thead>
		<tbody>
		<?
			foreach($projects as $project):
		?>
			<tr>
				<td><a href="<?=site_url('projects/detail/' . $project['id'])?>"><abbr title="<?=$project['description']?>"><?=$project['name']?></abbr></a></td>
				<td><?=$project['owner']?></td>
				<td><span class="active">Erfolgreich abgeschlossen</span></td>
				<td><a href="#">Ergebnisse anzeigen</a> | <a href="#">Entfernen</a></td>
			</tr>
		<?
			endforeach;
		?>
		<tr>
			<td><a href="#"><abbr title="Beschreibung des Projekts">Blutkörperchen</abbr></a></td>
			<td>Karsten Heiken</td>
			<td><span class="closed">Fehlgeschlagen</span></td>
			<td><a href="#">Fehlerbericht</a> | <a href="#">Entfernen</a></td>
		</tr>
		<tr>
			<td><a href="#"><abbr title="Beschreibung des Projekts">Blutkörperchen</abbr></a></td>
			<td>Karsten Heiken</td>
			<td><span class="pending">Berechnung im Gange: 20%</span></td>
			<td><a href="#">Anhalten</a> | <a href="#">Entfernen</a></td>
		</tr>
		<tr>
			<td><a href="#"><abbr title="Beschreibung des Projekts">Blutkörperchen</abbr></a></td>
			<td>Karsten Heiken</td>
			<td><span class="pending">Berechnung im Gange: 60%</span></td>
			<td><a href="#">Anhalten</a> | <a href="#">Entfernen</a></td>
		</tr>
		<tr>
			<td><a href="#"><abbr title="Beschreibung des Projekts">Blutkörperchen</abbr></a></td>
			<td>Karsten Heiken</td>
			<td><span class="pending">Berechnung im Gange: 0%</span></td>
			<td><a href="#">Anhalten</a> | <a href="#">Entfernen</a></td>
		</tr>
		<tr>
			<td><a href="#"><abbr title="Beschreibung des Projekts">Blutkörperchen</abbr></a></td>
			<td>Karsten Heiken</td>
			<td><span class="closed">Fehlgeschlagen</span></td>
			<td><a href="#">Fehlerbericht</a> | <a href="#">Entfernen</a></td>
		</tr>
		<tr>
			<td><a href="#"><abbr title="Beschreibung des Projekts">Blutkörperchen</abbr></a></td>
			<td>Karsten Heiken</td>
			<td><span class="closed">Fehlgeschlagen</span></td>
			<td><a href="#">Fehlerbericht</a> | <a href="#">Entfernen</a></td>
		</tr>
		</tbody>
		</table>

		<div class="pagination">
			<ul>
				<li class="pages">Seite:</li>
				<li>1</li>
				<li><a href="#">2</a></li>
				<li><a href="#">3</a></li>
				<li><a href="#">4</a></li>
				<li><a href="#">5</a></li>
				<li>...</li>
				<li><a href="#">10</a></li>
			</ul>
		</div>
	</div>

</div>

<?php $this->load->view('footer'); ?>