<?php $this->load->view('header'); ?>

<div id="content">
	<form action="#" method="post">
		<div class="title">
			<h2>Einstellungen</h2>
		</div>
		<ul class="tabs">
			<li class="active"><a href="#personal">Persönliche Daten</a></li>
			<li><a href="#settings">Einstellungen</a></li>
		</ul>

		<div class="tab_container">
			<div id="personal" class="tab_content">
				<table>
<?
	foreach($profile_fields as $field):
?>
					<tr>
						<td><?=$field[1]?></td>
						<td><input type="<?=$field[2]?>" name="<?=$field[0]?>" class="text"</td>
					</tr>
<?
	endforeach;
?>
				</table>
			</div>
			<div id="settings" class="tab_content">
				<ul>
					<li>
						<input type="checkbox" id="projects_sortrecently" name="projects_sortrecently" value="1" class="checkbox"/>
						<label for="projects_sortrecently">Projekte nach Zeitpunkt des letzten Zugriffs sortieren</label><br />
						<label class="note">Werden die Projekte nach dem Zeitpunkt des letzten Zugriffs sortiert, &quot;rutschen&quot; die selten verwendeten Projekte in der Liste nach unten.</label>
					</li>
				</ul>
			</div>
		</div>
	</form>
</div>

<?php $this->load->view('footer'); ?>
