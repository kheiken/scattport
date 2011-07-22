<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2>Serververwaltung</h2>
	</div>

	<div class="box">
		<h3>Übersicht aller Server</h3>
		<table>
		<thead>
		<tr>
			<th scope="col">Bezeichnung</th>
			<th scope="col">Ort</th>
			<th scope="col">Status</th>
			<th scope="col">Aktion</th>
		</tr>
		</thead>
		<tbody>
		<tr class="odd">
			<td><a href="#"><abbr title="ScattPort Server 01, Emden">SP-EMD-01</abbr></a></td>
			<td><abbr title="Technikum, Raum E10">Emden, Deutschland</abbr></td>
			<td><span class="active">Idle</span></td>
			<td><a href="#">Bearbeiten</a> | <a href="#">Entfernen</a></td>
		</tr>
		<tr>
			<td><a href="#"><abbr title="ScattPort Server 02, Emden">SP-EMD-02</abbr></a></td>
			<td><abbr title="Technikum, Raum E10">Emden, Deutschland</abbr></td>
			<td><span class="pending">Ausgelastet</span></td>
			<td><a href="#">Bearbeiten</a> | <a href="#">Entfernen</a></td>
		</tr>
		<tr class="odd">
			<td><a href="#"><abbr title="ScattPort Server 01, Bremen">SP-HB-01</abbr></a></td>
			<td><abbr title="Rechenzentrum, Raum 42, Rack 17">Bremen, Deutschland</abbr></td>
			<td><span class="closed">Nicht erreichbar</span></td>
			<td><a href="#">Bearbeiten</a> | <a href="#">Entfernen</a></td>
		</tr>
		<tr>
			<td><a href="#"><abbr title="ScattPort Server 01, Bremen">SP-HB-02</abbr></a></td>
			<td><abbr title="Rechenzentrum, Raum 42, Rack 17">Bremen, Deutschland</abbr></td>
			<td><span class="active">Idle</span></td>
			<td><a href="#">Bearbeiten</a> | <a href="#">Entfernen</a></td>
		</tr>
		<tr class="odd">
			<td><a href="#"><abbr title="ScattPort Server 01, Bremen">SP-HB-03</abbr></a></td>
			<td><abbr title="Rechenzentrum, Raum 42, Rack 17">Bremen, Deutschland</abbr></td>
			<td><span class="pending">Ausgelastet</span></td>
			<td><a href="#">Bearbeiten</a> | <a href="#">Entfernen</a></td>
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
