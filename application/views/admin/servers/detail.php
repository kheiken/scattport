<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=anchor('admin/servers', _('Servers'));?> &raquo; <?=$server->id;?></h2>
	</div>

	<div class="box">
		<h3><?=_('Miscellaneous');?></h3>
		<h4><?=_('Location');?></h4>
		<?=auto_typography($server->location);?>

		<h4><?=_('Owner');?></h4>
		<p>
			<a href="<?=site_url('users/profile/' . urlencode($owner['username']));?>" title="<?=sprintf(_("Show %s's profile"), $owner['firstname'] . ' ' . $owner['lastname']);?>"><?=$owner['firstname'] . ' ' . $owner['lastname'];?></a>
		</p>

		<h4><?=_('Description');?></h4>
		<?=auto_typography($server->description);?>

		<h3><?=_('Technical information');?></h3>
		<h4><?=_('Hardware & OS');?></h4>
		<p>
			<?=_('CPU');?>: <?=$server->hardware;?><br />
			<?=_('Uptime');?>: <?=$server->uptimestring;?><br />
			<?=_('OS');?>: <?=$server->os;?><br />
			<?=_('Workload');?>: <?=sprintf('%.02f', $server->workload);?><br />
			<?=_('Last heartbeat');?>: <?=$server->lastheartbeat;?>
		</p>

		<h4>ScattPort-<?=_('Statistics');?></h4>
		<p>
			<?=_('Completed jobs');?>: <br />
			<?=_('Available programs');?>:
		</p>
	</div>

</div>

<?php $this->load->view('footer');?>
