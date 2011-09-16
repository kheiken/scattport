<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Servers');?></h2>
	</div>

	<div class="box">
		<h3><?=_('Available servers');?></h3>
		<table class="tableList paginated">
			<thead>
				<tr>
					<th scope="col"><?=_('ID');?></th>
					<th scope="col"><?=_('Location');?></th>
					<th scope="col"><?=_('Status');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach ($servers as $server):
		if ($server['available'] != 0) {
			if ($server['workload'] > 0.8) {
				$server['class'] = 'pending';
				$server['status'] = 'busy';
			} else {
				$server['class'] = 'active';
				$server['status'] = 'available';
			}
		} else {
			$server['class'] = 'closed';
			$server['status'] = 'offline';
		}
?>
					<tr>
						<td><a href="<?= site_url('admin/servers/detail/' . $server['id']) ?>"><?= $server['id'] ?></a></td>
						<td><abbr title="Technikum, Raum E10"><?= $server['location'] ?></abbr></td>
						<td><span class="<?= $server['class'] ?>"><?= $server['status'] ?></span></td>
						<td>
							<a href="<?=site_url('admin/servers/detail/' . $server['id']);?>" title="<?=_('Show details');?>"><?=_('Show');?></a> |
							<a href="<?=site_url('admin/servers/edit/' . $server['id']);?>" title="<?=_('Edit this server');?>"><?=_('Edit');?></a> |
							<a href="javascript:deleteConfirm('<?=site_url('admin/servers/delete/' . $server['id']);?>');" title="<?=_('Delete this server');?>"><?=_('Delete');?></a>
						</td>
					</tr>
<?php
	endforeach;
?>
			</tbody>
		</table>
	</div>
</div>

<?php $this->load->view('footer');?>
