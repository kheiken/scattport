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
			CPU: <?=$server->hardware;?><br />
			Uptime: <?=$server->uptimestring;?><br />
			OS: <?=$server->os;?><br />
			Workload: <?=sprintf('%.02f', $server->workload);?><br />
			Last heartbeat: <?=$server->lastheartbeat;?>
		</p>

		<h4>ScattPort-Statistics</h4>
		<p>
			Completed jobs: 47<br />
			Available programs: PYTHA, ABC, DEF
		</p>
	</div>

</div>

<?php $this->load->view('footer'); ?>
