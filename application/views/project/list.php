<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2>Projektübersicht</h2>
	</div>

	<div class="box">
		<h3>Übersicht aller Projekte</h3>
		<table class="tableList paginated sortable">
			<thead>
				<tr>
					<th scope="col">Projekt</th>
					<th scope="col">Besitzer</th>
					<th scope="col">Berechnungen</th>
					<th scope="col">Aktion</th>
				</tr>
			</thead>
			<tbody>
<?php
foreach($projects as $project):
?>
				<tr>
					<td><a href="<?=site_url('projects/detail/' . $project['id'])?>"><abbr title="<?=$project['description']?>"><?=$project['name']?></abbr></a></td>
					<td><?=$project['firstname'] . " " . $project['lastname']?></td>
					<td><span class="active">Erfolgreich abgeschlossen</span></td>
					<td><a href="#">Ergebnisse anzeigen</a> | <?=anchor('projects/delete/' . $project['id'], "Entfernen");?>
					</td>
				</tr>
<?php
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
	</div>

</div>

<?php $this->load->view('footer');?>
