<?php $this->load->view('header');?>

<div id="content">

	<div class="title">
		<h2><?=_('Programs');?></h2>
	</div>

	<div class="box">
		<h3><?=_('Available programs');?></h3>
		<table class="tableList">
			<thead>
				<tr>
					<th scope="col"><?=_('Name');?></th>
					<th scope="col"><?=_('Actions');?></th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach ($programs as $program):
?>
				<tr>
					<td><?=$program['name'];?></td>
					<td>
						<?=anchor('admin/programs/edit/' . $program['id'], _('Edit'));?> |
						<a href="javascript:deleteConfirm('<?=site_url('admin/programs/delete/' . $program['id']);?>');"><?=_('Delete');?></a>
					</td>
				</tr>
<?php
	endforeach;
?>
			</tbody>
		</table>
		<h3><?=_('Actions');?></h3>
		<p>
			<a class="button disabled program_add"><?=_('Add program');?></a>
		</p>
	</div>
</div>

<?php $this->load->view('footer');?>
