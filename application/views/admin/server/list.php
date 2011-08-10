<?php $this->load->view('header'); ?>

<div id="content">

	<div class="title">
		<h2>Server management</h2>
	</div>

	<div class="box">
		<h3>List of all available servers</h3>
		<table>
			<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col">Location</th>
					<th scope="col">Status</th>
					<th scope="col">Actions</th>
				</tr>
			</thead>
			<tbody class="tableList">
<?
	foreach ($servers as $server):
		if ($server['available']) {
			if ($server['workload'] > 2) {
				$server['class'] = "pending";
				$server['status'] = 'busy';
			} else {
				$server['class'] = "active";
				$server['status'] = 'available';
			}
		} else {
			$server['class'] = "closed";
			$server['status'] = "offline";
		}
?>
					<tr>
						<td><a href="<?= site_url('admin/servers/detail/' . $server['id']) ?>"><?= $server['id'] ?></a></td>
						<td><abbr title="Technikum, Raum E10"><?= $server['location'] ?></abbr></td>
						<td>
							<?
							?>
							<span class="<?= $server['class'] ?>"><?= $server['status'] ?></span></td>
						<td><a href="#">Edit</a> | <a href="#">Delete</a></td>
					</tr>
<?
	endforeach;
?>
			</tbody>
		</table>
	</div>

</div>

<?php $this->load->view('footer'); ?>
